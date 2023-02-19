<?php

namespace app\System\Enum;

enum PlatformEnum: string {
    // web
    case WEB = 'web';
    // wap
    case WAP = 'wap';
    // APP
    case APP = 'app';
    // 微信公众号
    case WECHAT = 'wechat';
    // 微信小程序
    case WEAPP = 'weapp';


}