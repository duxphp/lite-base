<?php
declare(strict_types=1);

namespace App\System\Service;

use Dux\App;
use Enqueue\Consumption\ChainExtension;
use Enqueue\Consumption\Extension\LimitConsumedMessagesExtension;
use Enqueue\Consumption\Extension\LimitConsumptionTimeExtension;
use Enqueue\Consumption\QueueConsumer;

class Notify
{

    /**
     * 消费主题
     * @param string $label 消息标志
     * @return array
     */
    public static function consume(string $label): array
    {
        $context = App::queue()->context;
        $queueConsumer = new QueueConsumer($context, new ChainExtension([
            new LimitConsumptionTimeExtension(new \DateTime('now + 3 sec')),
            new LimitConsumedMessagesExtension(1)
        ]));

        $processor = new NotifyProcessor();
        $queueConsumer->bind("notify:$label", $processor);
        $queueConsumer->consume();
        return $processor->get();
    }

    /**
     * 发送主题
     * @param string $label 消息标志
     * @param string $type 消息类型
     * @param array $params 消息参数
     * @return void
     */
    public static function send(string $label, string $type, array $params = []): void
    {
        $context = App::queue()->context;
        $fooTopic = $context->createTopic("notify:$label");
        $data = [
            'type' => $label . '.' . $type,
            'params' => $params
        ];
        $message = $context->createMessage(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        $context->createProducer()->send($fooTopic, $message);
    }

}