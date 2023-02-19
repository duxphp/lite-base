<?php

namespace App\System\Admin;

use App\System\Models\LogOperate;
use App\System\Models\SystemUser;
use Dux\App;
use Dux\Manage\Manage;
use Illuminate\Database\Eloquent\Builder;
use Psr\Http\Message\ServerRequestInterface;

class Operate extends Manage {

    protected string $model =  LogOperate::class;
    protected string $name = "操作";

    public function listWhere(Builder $query, array $args, ServerRequestInterface $request): Builder {
        SystemUser::resolveRelationUsing("user", function ($orderModel) {
            return $orderModel->hasOne(SystemUser::class, 'user_id');
        });
        $query->with("user");
        $query->where("user_type", SystemUser::class);
        $params = $request->getQueryParams();
        $search = $params["keyword"];
        if ($search) {
            $query->where(function (Builder $query) use ($search) {
                $query->orWhere("client_ip", "like", "%$search%");
                $query->orWhere("request_url", "like", "%$search%");
            });
        }
        $query->orderByDesc('id');
        return $query;
    }

    public function listFormat(object $item): array {
        return [
            "id" => $item->id,
            "username" => $item->user->username,
            "nickname" => $item->user->nickname,
            "request_method" => $item->request_method,
            "request_url" => $item->request_url,
            "request_time" => $item->request_time,
            "request_params" => $item->request_params,
            "route_name" => $item->route_name,
            "route_title" => $item->route_title,
            "client_ua" => $item->client_ua,
            "client_ip" => $item->client_ip,
            "client_browser" => $item->client_browser,
            "client_device" => $item->client_device,
            "time" => $item->created_at->format("Y-m-d H:i:s"),

        ];
    }


}