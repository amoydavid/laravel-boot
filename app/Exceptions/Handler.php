<?php

namespace App\Exceptions;

use EasyWeChat\Kernel\Exceptions\Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use Illuminate\Support\Arr;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        ApiException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Determine if the exception handler response should be JSON.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return bool
     */
    protected function shouldReturnJson($request, Throwable $e)
    {
        return $request->expectsJson() || $request->routeIs('api.*') || $request->routeIs('admin.api.*');
    }

    /**
     * Convert the given exception to an array.
     *
     * @param  \Throwable  $e
     * @return array
     */
    protected function convertExceptionToArray(Throwable $e)
    {
        $message = $e->getMessage();
        if($e instanceof Exception && !config('app.debug')) {
            $message = 'Server Error';
        }
        return config('app.debug') ? [
            'code' => $e->getCode()?:500,
            'message' => $message,
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => collect($e->getTrace())->map(fn ($trace) => Arr::except($trace, ['args']))->all(),
        ] : [
            'code' => $e->getCode()?:500,
            'message' => $this->isHttpException($e) ? $message : 'Server Error',
        ];
    }

    public function render($request, Throwable $e)
    {
        if($e instanceof NeedPaymentException) {
            return \Response::json($e->renderJson());
        }elseif($e instanceof ApiException) {
            return \Response::json($this->convertExceptionToArray($e));
        }elseif($e instanceof ValidationException) {
            if($this->shouldReturnJson($request, $e)) {
                return Response::fail($e->getMessage(), $e->getCode()?:500, [
                    'errors'=>$e->errors()
                ]);
            }
        }elseif($e instanceof \PDOException){
            if(config('app.env')!='production') {
                return parent::render($request, $e);
            } else {
                return parent::render($request, new ApiException('数据库执行错误'));
            }
        }elseif($e instanceof HttpException) {
            return \Response::json($this->convertExceptionToArray($e), $e->getStatusCode());
        } elseif ($e instanceof AuthorizationException) {
            return Response::fail("您没有权限访问此功能", 500);
        } elseif ($e instanceof ModelNotFoundException) {
            if(config('app.env')!='production') {
                return parent::render($request, $e);
            } else {
                return parent::render($request, new ApiException('未找到对应数据'));
            }
        }
        return parent::render($request, $e);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->shouldReturnJson($request, $exception)
            ? response()->json(['code'=>401, 'message' => $exception->getMessage()], 401)
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }
}
