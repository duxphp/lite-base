<?php

declare(strict_types=1);

namespace App\Tools\Config;

use Dux\Permission\Permission as DuxPermission;

class Permission
{
    static function Admin(DuxPermission $permission): void
	{
		$group = $permission->manage("", "tools.area");
	}
}
