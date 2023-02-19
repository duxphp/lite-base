<?php

namespace App\System\Admin;

use Dux\App;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Notify {

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $data = \App\System\Service\Notify::consume('admin');
        return send($response, 'ok', $data);
    }

}