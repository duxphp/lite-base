<?php

declare(strict_types=1);

namespace App\Tools;

use App\Tools\Config\Menu;
use App\Tools\Config\Route;

/**
 * Application Registration
 */
class App extends \Dux\App\AppExtend
{
    public string $name = '工具模块';
    public string $description = '系统数据工具功能';


    public function register(\Dux\Bootstrap $app): void {
        // 注册菜单
        Menu::Admin($app->getMenu()->get("admin"));

        // 注册路由
        Route::AuthAdmin($app->getRoute()->get("adminAuth"));
    }
}
