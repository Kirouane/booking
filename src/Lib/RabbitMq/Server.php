<?php
declare(strict_types=1);

namespace Lib\RabbitMq;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Class Server
 * @package Lib\RabbitMq
 */
class Server
{

    /**
     * @var AMQPStreamConnection
     */
    private $amq;

    /**
     * @var AMQPChannel
     */
    private $channel;
    /**
     * @var string
     */
    private $exchangeName;

    /**
     * Client constructor.
     * @param AMQPStreamConnection $amq
     * @param string $exchangeName
     */
    public function __construct(AMQPStreamConnection $amq, string $exchangeName)
    {
        $this->amq = $amq;
        $this->channel = $amq->channel();
        $this->exchangeName = $exchangeName;
        $this->channel->exchange_declare($this->exchangeName, 'topic', false, false, false);
    }


    public function listen($queueName, array $bindingKeys, callable $callback)
    {
        $this->channel->queue_declare($queueName, false, false, true, false);
        foreach ($bindingKeys as $bindingKey) {
            $this->channel->queue_bind($queueName, $this->exchangeName, $bindingKey);
        };

        $this->channel->basic_consume($queueName, '', false, true, false, false, $callback);
        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }
}
