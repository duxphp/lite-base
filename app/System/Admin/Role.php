<?php

namespace app\System\Admin;

use app\System\Models\SystemRole;
use Dux\App;
use Dux\Manage\Manage;

class Role extends Manage {

    protected string $model =  SystemRole::class;

    protected string $name = "角色";


    public function listFormat(object $item): array {
        return [
            "id" => $item->id,
            "name" => $item->name,
        ];
    }

    public function infoFormat($info): array {
        return [
            "info" => [
                "id" => $info->id,
                "name" => $info->name,
                "permission" => $info->permission,
            ],
        ];
    }

    public function infoAssign($info): array {
        return [
            "permission" => App::permission("admin")->get()
        ];
    }

    public function saveValidator(array $args): array {
        return [
            "name" => ["required", "请输入名称"],
        ];
    }

    public function saveFormat(object $data, int $id): array {
        $permission = [];
        foreach ($data->permission as $vo) {
            if (!str_contains($vo, "group:")) {
                $permission[] = $vo;
            }
        }
        return [
            "name" => $data->name,
            "permission" => $permission,
        ];
    }
}