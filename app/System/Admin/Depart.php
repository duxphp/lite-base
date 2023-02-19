<?php

namespace app\System\Admin;

use app\System\Models\SystemDepart;
use Dux\App;
use Dux\Manage\Manage;

class Depart extends Manage {

    protected string $model =  SystemDepart::class;
    protected string $name = "部门";
    protected bool $listPage = false;
    protected bool $tree = true;


    public function infoFormat($info): array {

        return [
            "info" => [
                "id" => $info->id,
                "parent" => $this->coverLevel($info->parent_id),
                "name" => $info->name,
            ]
        ];
    }

    public function saveValidator(array $args): array {
        return [
            "name" => ["required", "请输入名称"],
        ];
    }

    public function saveFormat(object $data, int $id): array {
        return [
            "name" => $data->name,
            "parent_id" => $data->parent ? last($data->parent) : 0,
        ];
    }
}