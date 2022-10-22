<?php

declare(strict_types=1);

namespace Testcontainer\Container;

use Testcontainer\Wait\WaitForLog;

class RedisContainer extends Container
{
    public function __construct(string $version = 'latest')
    {
        parent::__construct('redis:' . $version);
        $this->withWait(new WaitForLog('Ready to accept connections'));
    }

    public static function make(string $version = 'latest'): self
    {
        return new self($version);
    }
}
