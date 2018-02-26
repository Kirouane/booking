<?php
namespace Lib;

interface FactoryInterface
{
    public function createService(\Lib\Di $di);
}
