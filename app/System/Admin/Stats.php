<?php

declare(strict_types=1);

namespace App\System\Admin;

use App\System\Event\StatsCardEvent;
use Dux\App;
use Dux\Route\Attribute\Route;
use Dux\Route\Attribute\RouteGroup;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

#[RouteGroup(app: 'adminAuth', title: '统计', pattern: '/system/stats')]
class Stats
{
    #[Route(methods: 'GET', title: '卡片', pattern: '/card')]
    public function card(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $event = new StatsCardEvent();
        App::event()->dispatch($event, "system.stats.card");
        $cards = $event->getCards();
        return send($response, 'ok', [
            'list' => $cards
        ]);
    }


    #[Route(methods: 'GET', title: '测试', pattern: '/test')]
    public function stats(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $data = [
            [
                'name' => '测试',
                'data' => [
                    [
                        'label' => '2023-03',
                        'value' => 280
                    ],
                ]
            ]
        ];
        return send($response, 'ok', [
            'list' => $data
        ]);
    }

    #[Route(methods: 'GET', title: '测试', pattern: '/test2')]
    public function stats2(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $data = [

            [
                'name' => '测试3',
                'data' => [
                    [
                        'label' => '2023-03-04',
                        'value' => 120
                    ],
                    [
                        'label' => '2023-03-06',
                        'value' => 100
                    ],
                ]
            ],
            [
                'name' => '测试2',
                'data' => [
                    [
                        'label' => '2023-03-05',
                        'value' => 100
                    ],
                    [
                        'label' => '2023-03-06',
                        'value' => 120
                    ],
                ]
            ],
        ];
        return send($response, 'ok', [
            'list' => $data
        ]);
    }


}
