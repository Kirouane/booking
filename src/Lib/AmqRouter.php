<?php
declare(strict_types=1);

namespace Lib;

use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class AmqRouter
 * @package Lib
 */
class AmqRouter
{

    /**
     * @var Di
     */
    private $di;

    /**
     * @var array
     */
    private $routes;

    /**
     * RpcRouter constructor.
     * @param Di $di
     * @param array $routes
     */
    public function __construct(Di $di, array $routes)
    {
        $this->di = $di;
        $this->routes = $routes;
    }

    /**
     * @param AMQPMessage $message
     * @return array
     */
    public function __invoke(AMQPMessage $message)
    {
        return $this->getResponse($message);
    }

    /**
     * @param $method
     * @return callable|null
     * @throws \DomainException
     */
    private function getController($method): ?callable
    {
        if (!isset($this->routes[$method])) {
            return null;
        }

        $controller = $this->routes[$method];
        return $this->di->get($controller);
    }

    /**
     * @param AMQPMessage $message
     * @return array
     */
    private function getResponse(AMQPMessage $message): array
    {
        $routingKey = $message->get('routing_key');
        $controller = $this->getController($routingKey);
        return $controller(json_decode($message->getBody(), true));
    }
}
