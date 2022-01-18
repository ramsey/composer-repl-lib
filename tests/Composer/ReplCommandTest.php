<?php

declare(strict_types=1);

namespace Ramsey\Test\Dev\Repl\Composer;

use Composer\Composer;
use Composer\Config;
use Composer\EventDispatcher\EventDispatcher;
use Composer\Package\RootPackageInterface;
use Ramsey\Dev\Repl\Composer\ReplCommand;
use Ramsey\Dev\Repl\Process\ProcessFactory;
use Ramsey\Dev\Tools\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function dirname;
use function realpath;

class ReplCommandTest extends TestCase
{
    public function testRun(): void
    {
        $dispatcher = $this->mockery(EventDispatcher::class);
        $dispatcher->shouldIgnoreMissing();

        $package = $this->mockery(RootPackageInterface::class, [
            'getPrettyName' => 'foo/bar',
            'getFullPrettyVersion' => 'dev-test',
            'getExtra' => [
                'ramsey/composer-repl' => [
                    'includes' => [
                        __DIR__ . '/../../repl.php',
                    ],
                ],
            ],
        ]);

        $config = new Config();
        $config->merge([
            'config' => [
                'vendor-dir' => realpath(dirname(__FILE__) . '/../../vendor'),
            ],
        ]);

        $composer = new Composer();
        $composer->setEventDispatcher($dispatcher);
        $composer->setConfig($config);
        $composer->setPackage($package);

        $input = $this->mockery(InputInterface::class);
        $input->shouldIgnoreMissing();

        $output = $this->mockery(OutputInterface::class);

        $command = new ReplCommand(
            (string) realpath(dirname(__FILE__) . '/../../'),
            new ProcessFactory(),
            $composer,
            false,
        );

        $command->run($input, $output);
    }
}
