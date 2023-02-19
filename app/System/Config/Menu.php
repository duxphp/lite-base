<?php
declare(strict_types=1);

namespace App\System\Config;
use App\System\Admin\Auth;
use App\System\Admin\Depart;
use App\System\Admin\User;
use Dux\App;
use Dux\Route\Route;
use PDO;

class Menu {
    static function Admin(\Dux\Menu\Menu $menu): void {

        $app = $menu->add("home", [
            "name" => "首页",
            "icon" => "i-heroicons:home",
            "order" => 0,
        ]);
        $group = $app->group("概况");
        $group->item("系统概况", "system/total/index", 0);


        $app = $menu->add("system", [
            "name" => "系统",
            "icon" => "i-heroicons:cog-6-tooth",
            "order" => 100,
        ]);

        $group = $app->group("接口");
        $group->item("接口授权", "system/api/list", 0)->auth("system.api.list");

        $group = $app->group("用户");
        $group->item("账号管理", "system/user/list", 0)->auth("system.user.list");
        $group->item("角色管理", "system/role/list", 1)->auth("system.role.list");
        $group->item("部门管理", "system/depart/list", 2)->auth("system.depart.list");


        $group = $app->group("日志");
        $group->item("操作记录", "system/operate/list", 0)->auth("system.operate.list");

        $menu->add("app", [
            "manage" => true,
            "name" => "应用",
            "icon" => "i-heroicons:squares-plus",
            "order" => 1000,
            "url" => "system/app/list"
        ]);
    }

}