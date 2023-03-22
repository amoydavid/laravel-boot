<?php

namespace App\Http\Controllers;

//use App\Attributes\MethodTitle;
//use App\Http\Controllers\Admin\PermissionController;
//use App\Http\Controllers\Admin\SystemController;
//use Illuminate\Support\Str;
//use ReflectionFunction;

class HelloController extends Controller
{

    public function hello()
    {
//        $routes = \Route::getRoutes();
//        foreach ($routes as $route) {
//            if (!empty($route->action["as"])) {
//                if(!Str::is('admin.*', $route->action['as']) ) {
//                    continue;
//                }
//                $classMethod = explode('@', $route->action['controller']);
//                if($classMethod[0][0] != '\\') {
//                    $classMethod[0] = '\\'.$classMethod[0];
//                }
//                $titleArr = [];
//                $classRef = new \ReflectionClass($classMethod[0]);
//                $classAttrs = $classRef->getAttributes(MethodTitle::class);
//                if($classAttrs) {
//                    $titleArr[] = $classAttrs[0]->newInstance()->title;
//                }
//
//                $ref = new \ReflectionMethod($classMethod[0], $classMethod[1]);
//                $attrs = $ref->getAttributes(MethodTitle::class);
//                if($attrs) {
//                    $methodTitle = $attrs[0]->newInstance()->title;
//                    $titleArr[] = $methodTitle;
//                }
//
//                $data[] = [
//                    'controller' => $classMethod,
//                    'title' => join("::", $titleArr),
//                    // "name" => !empty($route->action["as"]) ?   $route->action["as"] : '',
//                    // "prefix" => !empty($route->action["prefix"]) ?   $route->action["prefix"] : '',
//                    "uri" => $route->uri,
//                    "method" => $route->methods[0],
//                ];
//            }
//        }
        return response()->json([
            'hello' => app()->version()
        ]);
    }
}
