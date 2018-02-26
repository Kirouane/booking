<?php
declare(strict_types=1);

namespace Lib;

/**
 * Class Di
 * @package Lib
 */
class Di
{
    public const FACTORY = 0x1;

    /**
     * @var array
     */
    private $config;

    /**
     * @var array
     */
    private $services = [];

    /**
     * Di constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * @param string $key
     * @return mixed
     * @throws \DomainException
     */
    public function get(string $key)
    {
        if (isset($this->services[$key])) {
            return $this->services[$key];
        }

        $configuration = $this->config[$key] ?? null;
        if (!isset($this->config[$key])) {
            $this->services[$key] = $this->getServiceFromClassName($key);
        } elseif (\is_string($configuration)) {
            $this->services[$key] = $this->getServiceFromString($configuration);
        } elseif (\is_array($configuration)) {
            $this->services[$key] = $this->getServiceFromArray($configuration);
        }

        if (!isset($this->services[$key])) {
            throw new \DomainException("$key service not found.");
        }

        return $this->services[$key];
    }


    /**
     * @param string $serviceConfig
     * @return mixed
     */
    private function getServiceFromString(string $serviceConfig)
    {
        return new $serviceConfig();
    }

    /**
     * @param array $serviceConfig
     * @return mixed
     * @throws \DomainException
     */
    private function getServiceFromArray(array $serviceConfig)
    {
        /** @var string $class */
        $class = $serviceConfig[0] ?? null;
        $argument = $serviceConfig[1] ?? null;
        $factory = ($serviceConfig[2] ?? null) === self::FACTORY;
        if ($factory) {
            return $this->getServiceFromFactory($class, $argument);
        }

        return $this->getServiceFromDi($class, $argument);
    }

    /**
     * @param string $className
     * @param array $argumentServices
     * @return mixed
     */
    public function getServiceFromFactory(string $className, array $argumentServices)
    {
        /** @var FactoryInterface $factory */
        $factory = new $className(...$argumentServices);
        return $factory->createService($this);
    }

    /**
     * @param string $className
     * @param array $argumentServices
     * @return mixed
     * @throws \DomainException
     */
    private function getServiceFromDi(string $className, array $argumentServices)
    {
        $arguments = [];
        foreach ($argumentServices as $argument) {
            if (\is_array($argument)) {
                $arguments[] = reset($argument);
            } else {
                $arguments[] = $this->get($argument);
            }
        }

        return new $className(...$arguments);
    }

    /**
     * @param string $key
     * @return null|mixed
     */
    private function getServiceFromClassName(string $key)
    {
        if (!class_exists($key)) {
            return null;
        }

        return new $key();
    }
}
