<?php
declare(strict_types=1);

namespace App\System;

use App\System\Config\Menu;
use App\System\Config\Permission;
use App\System\Config\Route;
use App\System\Event\AppEvent;
use App\System\Middleware\ApiMiddleware;
use App\System\Middleware\OperateMiddleware;
use App\System\Models\SystemUser;
use Dux\App\AppExtend;
use Dux\Auth\AuthMiddleware;
use Dux\Bootstrap;
use Dux\Menu\Menu as DuxMenu;
use Dux\Permission\Permission as DuxPermission;
use Dux\Permission\PermissionMiddleware;
use Dux\Route\Route as DuxRoute;
use Dux\UI\UI;

class App extends AppExtend {

    public string $name = "系统模块";

    public string $description = '管理系统基础功能';

    public function init(Bootstrap $app): void {
        // 初始化路由
        $app->getRoute()->set("web", new DuxRoute("", "web端"));
        $app->getRoute()->set("admin", new DuxRoute("/admin", "管理端"));
        $app->getRoute()->set("adminAuth",
            new DuxRoute("/admin", "管理端授权",
                new OperateMiddleware(SystemUser::class),
                new PermissionMiddleware("admin", SystemUser::class),
                new AuthMiddleware("admin")
            )
        );
        $app->getRoute()->set("api",
            new DuxRoute("/api", "接口端",
                new ApiMiddleware()
            ),
        );
        // 初始化权限
        $app->getPermission()->set("admin", new DuxPermission());
        // 初始化菜单
        $app->getMenu()->set("admin", new DuxMenu());
    }

    public function register(Bootstrap $app): void {
        // 注册路由
        Route::Admin($app->getRoute()->get("admin"));
        Route::AuthAdmin($app->getRoute()->get("adminAuth"));
        Route::Api($app->getRoute()->get('api'));
        // 注册权限
        Permission::Admin($app->getPermission()->get("admin"));
        // 注册菜单
        Menu::Admin($app->getMenu()->get("admin"));
        // 注册UI
        UI::register(__DIR__ . "/Client/admin", "system");

        // 注册事件
        $app->getEvent()->addListener("system.app", function (AppEvent $event) {
            $event->label([
                'label' => 'system',
                'name' => '系统工具'
            ]);
            $event->label([
                'label' => 'show',
                'name' => '展示模块'
            ]);
        });
    }


}