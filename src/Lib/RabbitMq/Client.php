<?php
declare(strict_types=1);

namespace Lib\RabbitMq;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class Client
 * @package Lib\Rabbitmq
 */
class Client
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

    /**
     * @param string $routingKey
     * @param string $data
     */
    public function send(string $routingKey, string $data)
    {
        $message = new AMQPMessage($data);
        $this->channel->basic_publish($message, $this->exchangeName, $routingKey);
    }

    public function __destruct()
    {
        $this->channel->close();
    }
}
