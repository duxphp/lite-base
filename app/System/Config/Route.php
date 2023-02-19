<?php

declare(strict_types=1);

namespace app\System\Config;

use app\System\Admin\Auth;
use app\System\Admin\Depart;
use app\System\Admin\Operate;
use app\System\Admin\Role;
use app\System\Admin\User;
use App\System\Event\Test;
use Dux\App;
use Dux\Route\Route as DuxRoute;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Route
{
	static function Admin(DuxRoute $route): void
	{
		$route->post(pattern: "/login", callable: Auth::class . ":login", name: "system.login", title: "登录");
	}


	static function AuthAdmin(DuxRoute $route): void
	{
		$route->get(pattern: "/menu", callable: Auth::class.":menu", name: "system.menu", title: "菜单");
        $route->get("/notify", \app\System\Admin\Notify::class.":get", "system.notify.get", "通知事件");
        $route->get(pattern: "/personage", callable: User::class.":personage", name: "system.personage.info", title: "个人信息");
        $route->post(pattern: "/personage", callable: User::class.":personageSave", name: "system.personage.save", title: "个人保存");
        $route->get(pattern: "/personage/login", callable: User::class.":personageLogin", name: "system.personage.login", title: "登录日志");
        $route->get(pattern: "/personage/operate", callable: User::class.":personageOperate", name: "system.personage.operate", title: "操作日志");
		$route->manage(pattern: "/system/user", class: User::class, name: "system.user", title: "账号");
		$route->manage(pattern: "/system/role", class: Role::class, name: "system.role", title: "角色");
		$route->manage(pattern: "/system/depart", class: Depart::class, name: "system.depart", title: "部门");
		$route->manage(pattern: "/system/api", class: \app\System\Admin\Api::class, name: "system.api", title: "API授权");
        $route->get("/system/operate", Operate::class.":list", "system.operate", "操作记录");
        $route->get("/system/app/label", \app\System\Admin\App::class.":label", "system.app.label", "应用标签");
	}


    static function Api(DuxRoute $route): void
    {
        $route->get('/test', function (ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
            return send($response, 'dddd');
        }, 'test', '测试');
    }
}
