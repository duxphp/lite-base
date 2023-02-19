<?php
declare(strict_types=1);

namespace app\Tools\Config;
use app\System\Admin\Auth;
use app\System\Admin\Depart;
use app\System\Admin\User;
use Dux\App;
use Dux\Route\Route;

class Menu {

    static function Admin(\Dux\Menu\Menu $menu): void {
        $app = $menu->add("tools", [
            "name" => "工具",
            "icon" => "i-heroicons:cube",
            "order" => 90,
        ]);

        $group = $app->group("数据管理");
        $group->item("地区数据", "tools/area/list", 0);
    }

}