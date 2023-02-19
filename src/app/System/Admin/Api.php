<?php

declare(strict_types=1);

namespace App\System\Admin;

use App\System\Models\SystemApi;
use Dux\Manage\Manage;
use Dux\Validator\Data;

class Api extends Manage
{
	protected string $model = SystemApi::class;
	protected string $name = '业务名';


	protected function listFormat(object $item): array
	{
		return [
		    "id" => $item->id,
            "name" => $item->name,
            "secret_id" => $item->secret_id,
            "secret_key" => $item->secret_key,
            "status" => $item->status,
		];
	}


	protected function infoFormat($info): array
	{
		return [
		"info" => [
		    "id" => $info->id,
            "name" => $info->name,
		]];
	}


	protected function saveValidator(array $args): array
	{
		return [
		    "name" => ["required", "请输入名称"],
		];
	}


	protected function saveFormat(Data $data, int $id): array
	{
        $data = [
            "name" => $data->name,

        ];
        if (!$id) {
            $data = [...$data, ...[
                "secret_id" => random_int(10000000, 99999999),
                "secret_key" => bin2hex(random_bytes(16)),
            ]];
        }
		return $data;
	}
}
