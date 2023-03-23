<?php

namespace App\Console\Commands;

use App\Attributes\MethodTitle;
use App\Attributes\MethodType;
use App\Models\SysRoute;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SaveAdminRoute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:admin.routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'save admin routes to db';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $savedMap = [];
        $routes = \Route::getRoutes();
        foreach ($routes as $route) {

            if (!empty($route->action["as"])) {
                if(!Str::is('admin.*', $route->action['as']) || (!isset($route->action['middleware']) || !in_array('rbac', $route->action['middleware']))) {
                    continue;
                }
                $classMethod = explode('@', $route->action['controller']);
                if($classMethod[0][0] != '\\') {
                    $classMethod[0] = '\\'.$classMethod[0];
                }
                $classRef = new \ReflectionClass($classMethod[0]);
                $classAttrs = $classRef->getAttributes(MethodTitle::class);
                $classTitle = '';
                if($classAttrs) {
                    $classTitle = $classAttrs[0]->newInstance()->title;
                }

                $ref = new \ReflectionMethod($classMethod[0], $classMethod[1]);
                $attrs = $ref->getAttributes(MethodTitle::class);
                if($attrs) {
                    $methodTitle = $attrs[0]->newInstance()->title;
                } else {
                    $methodTitle = '';
                }

                $typeAttrs = $ref->getAttributes(MethodType::class);
                $methodType = '';
                if($typeAttrs) {
                    $methodType = $typeAttrs[0]->newInstance()->type;
                }

                $item =  [
                    'class' => $classMethod[0],
                    'classTitle' => $classTitle?:(str_replace("\App\Http\Controllers\\", "", $classMethod[0])),
                    'methodTitle' => $methodTitle?:$classMethod[1],
                    'controller' => $classMethod,
                    "prefix" => !empty($route->action["prefix"]) ?   $route->action["prefix"] : '',
                    "uri" => $route->uri,
                    "method" => $route->methods[0],
                    "type" => $methodType,
                ];

                $parentRoute = SysRoute::query()->where('handler', $item['class'])->where('parent_id', 0)->firstOrNew();
                $parentRoute->handler = $item['class'];
                $parentRoute->title = $item['classTitle'];
                $parentRoute->save();
                if(!in_array($parentRoute->id, $savedMap)) {
                    $savedMap[] = $parentRoute->id;
                }

                $targetRoute = SysRoute::query()->where('route', $item['uri'])->where('method', $item['method'])->firstOrNew();
                $targetRoute->parent_id = $parentRoute->id;
                $targetRoute->handler = join('@', $item['controller']);
                $targetRoute->title = $item['methodTitle'];
                $targetRoute->route = $item['uri'];
                $targetRoute->method = $item['method'];
                $targetRoute->type = $item['type'];
                $targetRoute->save();
                if(!in_array($targetRoute->id, $savedMap)) {
                    $savedMap[] = $targetRoute->id;
                }
            }
        }

        SysRoute::whereNotIn('id', $savedMap)->delete();

        return Command::SUCCESS;
    }
}
