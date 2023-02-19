<?php

declare(strict_types=1);

namespace App\System\Config;

class Permission
{
	static function Admin(\Dux\Permission\Permission $permission): void
	{
		$group = $permission->group("系统概况", "home.total");
		$group->add("index", "系统概况");

		$permission->manage("用户管理", "system.user");
		$permission->manage("角色管理", "system.role");
		$permission->manage("部门管理", "system.depart");

		$group = $permission->group("操作日志", "system.operate");
		$group->add("list", "列表");

		$group = $permission->manage("接口授权", "system.api");
	}
}
