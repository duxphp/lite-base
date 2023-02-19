<?php

namespace app\System\Admin;

use app\System\Event\AppEvent;
use Dux\Validator\Data;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class App
{
    public function label(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $list = [];

        $event = new AppEvent();
        \Dux\App::event()->dispatch($event, 'system.app');

        return send($response, 'ok', [
            'list' => $event->getLabel()
        ]);
    }
}