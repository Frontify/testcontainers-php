<?php

declare(strict_types=1);

namespace Testcontainers\Tests\Integration;

use Testcontainers\Modules\MariaDBContainer;

class MariaDBContainerTest extends ContainerTestCase
{
    public function setUp(): void
    {
        $this->container = (new MariaDBContainer())
            ->withMariaDBDatabase('foo')
            ->withMariaDBUser('bar', 'baz')
            ->start();
    }

    public function testMariaDBContainer(): void
    {
        $pdo = new \PDO(
            sprintf(
                'mysql:host=%s;port=%d',
                $this->container->getHost(),
                $this->container->getFirstMappedPort()
            ),
            'bar',
            'baz',
        );

        $query = $pdo->query('SHOW databases');

        $this->assertInstanceOf(\PDOStatement::class, $query);

        $databases = $query->fetchAll(\PDO::FETCH_COLUMN);

        $this->assertContains('foo', $databases);
    }
}
