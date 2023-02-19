<?php

namespace app\System\Admin;

use app\System\Models\LogOperate;
use app\System\Models\SystemDepart;
use app\System\Models\SystemRole;
use app\System\Models\SystemUser;
use app\System\Models\LogLogin;
use Dux\App;
use Dux\Handlers\ExceptionBusiness;
use Dux\Manage\Manage;
use Dux\Validator\Validator;
use Illuminate\Database\Eloquent\Builder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class User extends Manage {

    protected string $model =  SystemUser::class;
    protected string $name = "用户";

    public function listWhere(Builder $query, array $args, ServerRequestInterface $request): Builder {
        $params = $request->getQueryParams();
        $role = $params["role"];
        $depart = $params["depart"];
        $search = $params["keyword"];
        $tab = $params["tab"];
        $query->with(["roles", "departs", "leaders"]);
        if ($search) {
            $query->where(function (Builder $query) use ($search) {
                $query->orWhere("nickname", "like", "%$search%");
                $query->orWhere("username", "like", "%$search%");
            });
        }
        if ($role) {
            $query->whereHas("roles", function (Builder $query) use ($role) {
                $query->where("id", $role);
            });
        }
        if ($depart) {
            $query->whereHas("departs", function (Builder $query) use ($depart) {
                $query->where("id", $depart);
            });
        }
        if ($tab) {
            $query->where("status", $tab == 1);
        }
        return $query;
    }

    public function listFormat(object $item): array {
        return [
            "id" => $item->id,
            "username" => $item->username,
            "nickname" => $item->nickname,
            "roles" => $item->roles->pluck("name"),
            "departs" => $item->departs->pluck("name"),
            "leaders" => $item->leaders->pluck("name"),
            "status" => $item->status,
        ];
    }

    public function infoWhere(object $query, array $args) {
        return $query->with(["roles", "departs", "leaders"])->where("id", $args["id"]);
    }

    public function infoFormat($info): array {
        return [
            "info" => [
                "id" => $info->id,
                "username" => $info->username,
                "nickname" => $info->nickname,
                "leader" => (bool)$info->leader,
                "roles" => $info->roles->pluck("id")->toArray(),
                "departs" => $info->departs->pluck("id")->toArray(),
                "leaders" => $info->leaders->pluck("id")->toArray(),
            ]
        ];
    }

    public function infoAssign($info): array {
        return [
            "roles" => SystemRole::query()->get(["name", "id"])->toArray(),
            "departs" => SystemDepart::childrenAll()->toArray(),
        ];
    }

    public function saveValidator(array $args): array {
        return [
            "nickname" => ["required", "请输入昵称"],
            "username" => ["required", "请输入用户名"],
            "password" => ["requiredWithout", "id", "请输入密码"],
            "roles" => ["required", "请选择角色"],
        ];
    }

    public function saveFormat(object $data, int $id): array {
        $model = SystemUser::query()->where('username', $data->username);
        if ($id) {
            $model->where("id", "<>", $data->id);
        }
        if ($model->exists()) {
            throw new ExceptionBusiness("该用户已存在");
        }
        $saveData = [
            "nickname" => $data->nickname,
            "username" => $data->username,
            "leader" => $data->leader,
        ];
        if(!$id || $data->password) {
            $saveData["password"] = password_hash($data->password, PASSWORD_BCRYPT);
        }
        return $saveData;
    }

    public function saveAfter($info, object $data) {
        $info->roles()->sync($data->roles ?: []);
        $info->departs()->sync($data->departs ?: []);
        $info->leaders()->sync($data->leaders ?: []);
    }


    /**
     * 个人资料
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function personage(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $auth = $request->getAttribute("auth");
        $userInfo = SystemUser::query()->find($auth["id"]);
        return send($response, "ok", [
            "avatar" => $userInfo->avatar,
            "username" => $userInfo->username,
            "nickname" => $userInfo->nickname,
        ]);
    }

    /**
     * 资料保存
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function personageSave(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = Validator::parser([...$request->getParsedBody(), ...$args], [
            "username" => ["required", "请输入用户名"],
            "nickname" => ["required", "请输入昵称"],
        ]);
        $auth = $request->getAttribute("auth");
        $info = SystemUser::query()->where("id", "<>", $auth["id"])->where('username', $data->username)->first();
        if ($info) {
            throw new ExceptionBusiness("该用户名无法使用");
        }
        $userInfo = SystemUser::query()->find($auth["id"]);
        $userInfo->avatar = $data->avatar;
        $userInfo->nickname = $data->nickname;
        $userInfo->username = $data->username;
        if($data->password) {
            $userInfo->password = password_hash($data->password, PASSWORD_BCRYPT);
        }
        $userInfo->save();
        return send($response, "ok");
    }

    /**
     * 登录日志
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function personageLogin(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $auth = $request->getAttribute("auth");
        $list = LogLogin::query()->where('user_type', SystemUser::class)->where('user_id', $auth['id'])->orderByDesc('id')->paginate(20);
        $assign = format_data($list, static function ($item): array {
            return [
                'id' => $item->id,
                'browser' => $item->browser,
                'ip' => $item->ip,
                'platform' => $item->platform,
                'status' => $item->status,
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
            ];
        });
        return send($response, 'ok', $assign);
    }

    /**
     * 操作日志
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function personageOperate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $params = $request->getQueryParams();
        $limit = $params['limit'] ?: 20;

        $auth = $request->getAttribute("auth");
        $list = LogOperate::query()->where('user_type', SystemUser::class)->where('user_id', $auth['id'])->orderByDesc('id')->paginate($limit);
        $assign = format_data($list, static function ($item): array {
            return [
                'id' => $item->id,
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
        });
        return send($response, 'ok', $assign);
    }

}