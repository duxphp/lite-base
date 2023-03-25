<?php

namespace App\System\Admin;

use App\System\Event\AppEvent;
use Dux\Validator\Data;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class App
{
    public function label(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $list = [];

        $event = new AppEvent();
        // NOTE system.app
        \Dux\App::event()->dispatch($event, 'system.app');

        return send($response, 'ok', [
            'list' => $event->getLabel()
        ]);
    }
}