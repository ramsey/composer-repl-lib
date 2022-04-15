<?php

declare(strict_types=1);

namespace Ramsey\Test\Dev\Repl\Process;

use Ramsey\Dev\Repl\Process\ProcessFactory;
use Ramsey\Dev\Tools\TestCase;
use Symfony\Component\Process\Process as SymfonyProcess;

class ProcessFactoryTest extends TestCase
{
    public function testFactory(): void
    {
        $factory = new ProcessFactory();

        $this->assertInstanceOf(SymfonyProcess::class, $factory->factory(['ls']));
    }
}
