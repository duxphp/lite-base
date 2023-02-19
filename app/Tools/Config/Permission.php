<?php

declare(strict_types=1);

namespace app\Tools\Config;

use Dux\Permission\Permission as DuxPermission;

class Permission
{
    static function Admin(DuxPermission $permission): void
	{
		$group = $permission->manage("", "tools.area");
	}
}
