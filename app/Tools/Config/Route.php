<?php

declare(strict_types=1);

namespace App\Tools\Config;

use Dux\Route\Route as DuxRoute;

class Route
{
    static function AuthAdmin(DuxRoute $route): void
	{
        $route->get("/tools/area",  \App\Tools\Admin\Area::class . ":list", "tools.area.list", "地区列表");
        $route->post("/tools/area",  \App\Tools\Admin\Area::class . ":import", "tools.area.import", "数据导入");
        $route->get("/tools/area/cascade",  \App\Tools\Admin\Area::class . ":cascade", "tools.area.cascade", "地区选择");
        $route->post("/tools/upload",  \App\Tools\Admin\Upload::class . ":handler", "tools.upload", "文件上传");
        $route->get("/tools/fileManage",  \App\Tools\Admin\Upload::class . ":manage", "tools.fileManage", "文件管理器");
	}
}
