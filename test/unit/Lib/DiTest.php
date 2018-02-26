<?php
namespace Lib;

use PHPUnit\Framework\TestCase;

class DiTest extends TestCase
{
    /**
     * @test
     */
    public function getOnTheFly()
    {
        $di = new Di();
        self::assertInstanceOf(OnTheFly::class, $di->get(OnTheFly::class));
    }

    /**
     * @test
     */
    public function getFromString()
    {
        $di = new Di([
            'key' => FromString::class
        ]);
        self::assertInstanceOf(FromString::class, $di->get('key'));
    }

    /**
     * @test
     */
    public function getFromConfigWithScalarInTheConstructor()
    {
        $di = new Di([
            'key' => [
                FromConfigWithScalarInTheConstructor::class,
                [['string'], [1], [[1]]]
            ]
        ]);

        $service = $di->get('key');

        self::assertInstanceOf(FromConfigWithScalarInTheConstructor::class, $service);
        self::assertSame('string', $service->arg1);
        self::assertSame(1, $service->arg2);
        self::assertSame([1], $service->arg3);
    }

    /**
     * @test
     */
    public function getFromConfigWithServiceInTheConstructor()
    {
        $di = new Di([
            FromString::class => FromString::class,
            'key' => [
                FromConfigWithServiceInTheConstructor::class,
                [FromString::class]
            ]
        ]);

        $service = $di->get('key');

        self::assertInstanceOf(FromConfigWithServiceInTheConstructor::class, $service);
        self::assertInstanceOf(FromString::class, $service->arg1);
    }

    /**
     * @test
     */
    public function getFromFactory()
    {
        $di = new Di([
            FromString::class => FromString::class,
            'key' => [
                FromFactory::class,
                ['string', 1],
                Di::FACTORY
            ]
        ]);

        $service = $di->get('key');

        self::assertNotNull($service);
        self::assertSame('string1', $service->arg1);
    }
}


class OnTheFly
{
}

class FromString
{
}

class FromConfigWithScalarInTheConstructor
{
    /**
     * @var string
     */
    public $arg1;
    /**
     * @var int
     */
    public $arg2;
    /**
     * @var array
     */
    public $arg3;

    public function __construct(string $arg1, int $arg2, array $arg3)
    {
        $this->arg1 = $arg1;
        $this->arg2 = $arg2;
        $this->arg3 = $arg3;
    }
}

class FromConfigWithServiceInTheConstructor
{

    /**
     * @var array
     */
    public $arg1;

    public function __construct(FromString $arg1)
    {
        $this->arg1 = $arg1;
    }
}

class FromFactory implements FactoryInterface
{

    /**
     * @var string
     */
    public $arg1;
    /**
     * @var int
     */
    public $arg2;

    public function __construct(string $arg1, int $arg2)
    {
        $this->arg1 = $arg1;
        $this->arg2 = $arg2;
    }

    public function createService(\Lib\Di $di)
    {
        return new class($this->arg1, $this->arg2) {
            public $arg1;

            public function __construct($arg1, $arg2)
            {
                $this->arg1 = $arg1 . $arg2;
            }
        };
    }
}
