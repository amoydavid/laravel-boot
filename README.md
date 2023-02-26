# Laravel-Boot

## 本地开发环境快速搭建指南

如果需要在本地架设服务器，需要先设置好 `.env` 文件，这样docker的MySQL的密码会按.env里的设置好。
如果是把代码提交到远程服务器的，不需要在本地跑docker/sail，请跳过此章节。

```shell
# 安装依赖，如果本地没有php和composer的话可以使用已经准备好的docker包
chmod u+x ./php-composer.sh
./php-composer.sh install --prefer-dist -vvv
```

启动 docker 的 mini-sail 环境
```shell
# 添加可执行权限
chmod u+x ./sail.sh
# 启动docker
./vendor/bin/sail up -d
# 或使用
./sail.sh up -d
```

首次运行前需要生成加密串

```shell
./sail.sh artisan key:generate
```

之后就可以顺利使用`sail`命令了，本地同步数据库结构：`sail artisan migrate`

使用PHP运行本地服务器：`php artisan serve`

## 开发指南

1. Models 解决持久化问题
2. Services 解决跨Model并且需要返回结果的
3. Actions 解决不需要返回结果的动作，如进队列之后要做的事情，不需要返回值的

## 路由

### 使用传统路由

可在原本的 `routes/api.php` 下添加需要的API路由。

### 使用路由注册商 `Registrar`

1. 创建路由注册商 `app/Routing/Api/UserRegistrar.php`, 实现 `\App\Routing\Contracts\RouteRegistrar` 接口，并在 `map` 函数里实现子路由
2. 将刚刚的注册商挂到 `app/Routing/ApiRegistrar.php` 的 `protected array $registrars`中，此时 `UserRegistrar` 成为 `ApiRegistrar` 的子路由。

路由注册商将覆盖传统路由中的同路径路由。

## 快速返回 JSON

```php
// 推荐使用这个类，会有提示
use Illuminate\Http\Response;

// 返回正确, code = 0, data为返回值
return Response::ok([
    'token'=>$token
]);

// 返回出错，code=500, message = 出错信息
return Response::fail('出错信息', 500);

// 出错时也可以直接抛ApiException出来
throw new \App\Exceptions\ApiException('出错信息')
```

## Service 如何使用

可直接通过DI获得，或自己在后面new起来。

```php
use App\Http\Controllers\Controller;use App\Http\Requests\Api\User\CreateMiniUserRequest;use App\Services\MiniUserService;use Illuminate\Http\Response;

class UserController extends Controller
{
    //第一个参数始终是Request对象（如果是FormRequest对象会自动验证表单）
    //第二个参数即是通过DI自动实例化的
    public function login(CreateMiniUserRequest $request, MiniUserService $miniUserService) {
        //到这里时，已经经过了表单验证，直接写主要的业务逻辑即可
        
        // service 可以传入 request 参数处理
        // 或传入数组处理
        // 有错误直接对外抛ApiException即可
        $wxUser = $miniUserService->createUser($request);
    
        $token = $wxUser->createToken("API", ['*'], 120); //表示token在120天后过期
        return Response::ok([
            'token' => $token->plainTextToken,
            'expires' => $token->accessToken->expired_at
        ]);
    }
}
```

## 表单验证

如果需要表单验证，可写至单独的表单类里。如 `app/Http/Requests/User/CreateMiniUserRequest.php`
然后在Controller里首个参数的类型换成新的表单类，此时表单会自动验证。表单未验证通过会自动报错。

表单类重要方法：

* `public function rules():array` 定义一个验证规则数组，参见 `https://learnku.com/docs/laravel/9.x/validation/12219#quick-writing-the-validation-logic` ，可用的验证包参见 `https://learnku.com/docs/laravel/9.x/validation/12219#available-validation-rules`
* `public function attributes():array` 定义属性名，验证不通过时会用到此项数据
* `public function messages():array` 定义出错的信息。
* `public function authorize():bool` 可在这个方法内定义`$this->user()`是否有权限访问此表单操作

自定义出错信息

```php
public function messages()
{
    return [
        'code.required' => '登录码未提供', // code 未通过 required 规则时报错
    ];
}
```

或

```php
public function messages()
{
    return [
        'code' => '登录码字段不正确', // code任一规则未通过时报错
    ];
}
```

## 语言包

使用 [laravel-lang](https://laravel-lang.com/) 官方语言包，主要有以下指令

```shell
composer require laravel-lang/common --dev
php artisan lang:add zh_CN
php artisan lang:update
```

项目下载后直接修改`lang`目录下的内容即可。不需要再添加和更新了。

## 权限判断

主要的权限策略在 `app\Policies` 目录下。
在 controller 中使用 `$this->authorize('destroy', $permission);` 来判断当前用户是否有权限 `destroy $permission`, 将会通过`$permission`的类型交由对应的`Policy`判断。

## 超管

users表中id为1的即为超管，其余为普通管理员。

`php artisan app:create.admin`将创建一个新管理员。
