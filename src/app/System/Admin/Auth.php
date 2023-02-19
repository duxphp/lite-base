<?php

namespace App\System\Admin;

use App\System\Models\SystemUser;
use App\System\Models\LogLogin;
use donatj\UserAgent\UserAgentParser;
use Dux\App;
use Dux\Handlers\ExceptionBusiness;
use Dux\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Auth
{

    public function login(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = Validator::parser([...$request->getParsedBody(), ...$args], [
            "username" => ["required", "请输入账号"],
            "password" => ["required", "请输入密码"],
        ]);
        $info = SystemUser::query()->where("username", $data->username)->first();
        if (!$info) {
            throw new ExceptionBusiness("账号或密码错误");
        }

        $this->loginCheck((int) $info->id);


        $useragent = $request->getHeader("user-agent")[0];
        $parser = new UserAgentParser();
        $ua = $parser->parse($useragent);
        $loginModel = LogLogin::query();
        $logData = [
            'user_type' => SystemUser::class,
            'user_id' => $info->id,
            'browser' => $ua->browser() . ' ' . $ua->browserVersion(),
            'ip' => get_ip(),
            'platform' => $ua->platform(),
        ];
        if (!password_verify($data->password, $info->password)) {
            $logData['status'] = false;
            $loginModel->create($logData);
            throw new ExceptionBusiness("账号或密码错误");
        }
        $logData['status'] = true;
        $loginModel->create($logData);

        return send($response, "ok", [
            "userInfo" => [
                "id" => $info->id,
                "avatar" => $info->avatar,
                "username" => $info->username,
                "nickname" => $info->nickname,
                "rolename" => $info->roles[0]->name,
            ],
            "token" => "Bearer " . \Dux\Auth\Auth::token("admin", [
                    'id' => $info->id,
                ]),
            "permission" => $info->permission
        ]);
    }

    public function menu(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $auth = $request->getAttribute("auth");
        $userInfo = SystemUser::query()->find($auth["id"]);
        return send($response, "ok", [
            "main" => App::menu('admin')->get($userInfo->permission),
        ]);
    }

    private function loginCheck(int $id): void
    {
        $loginList = LogLogin::query()->where('user_type', SystemUser::class)->where('user_id', $id)->orderByDesc('id')->where('status', false)->limit(3)->get();
        $loginLast = $loginList->first();
        $loginStatus = 0;
        foreach ($loginList as $vo) {
            if (!$vo->status) {
                $loginStatus++;
            }
        }
        $time = now();
        if ($loginStatus >= 3 && $loginLast->created_at->addSeconds(60)->gt($time)) {
            throw new ExceptionBusiness("三次登录密码错误，等待一分钟");
        }
    }


}