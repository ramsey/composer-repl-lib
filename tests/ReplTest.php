<?php

declare(strict_types=1);

namespace Ramsey\Test\Dev\Repl;

use Composer\Factory;
use Composer\IO\ConsoleIO;
use Psy\Shell;
use Ramsey\Dev\Repl\Process\ProcessFactory;
use Ramsey\Dev\Repl\Repl;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

use function dirname;
use function implode;
use function phpversion;
use function realpath;

use const DIRECTORY_SEPARATOR;
use const PHP_MAJOR_VERSION;
use const PHP_OS_FAMILY;

class ReplTest extends TestCase
{
    public function testReplCommand(): void
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $this->markTestSkipped(
                'Skipping on Windows due to a problem recognizing the command.',
            );
        }

        if (PHP_MAJOR_VERSION < 8) {
            $this->markTestSkipped(
                'Skipping on PHP 7.4 due to a problem getting the command output.',
            );
        }

        $processFactory = new ProcessFactory();
        $process = $processFactory->factory(['bin/repl'], dirname(__DIR__));
        $process->setTimeout(2);
        $process->setPty(true);

        try {
            $process->mustRun();
        } catch (ProcessTimedOutException $exception) { // @phpstan-ignore-line
        }

        $shellVersion = Shell::VERSION;
        $phpVersion = phpversion();

        $lines = [
            "\e[34mPsy Shell $shellVersion (PHP $phpVersion — cli) by Justin Hileman\e[39m",
            '------------------------------------------------------------------------',
            "\e[32mWelcome to the development console (REPL) for ramsey/composer-repl-lib.\e[39m",
            "\e[36mTo learn more about what you can do in PsySH, type `help`.\e[39m",
            '------------------------------------------------------------------------',
            '>>> ',
        ];

        $expected = implode("\r\n", $lines);

        $this->assertSame($expected, $process->getOutput());
    }

    public function testReplRun(): void
    {
        $shellVersion = Shell::VERSION;
        $phpVersion = phpversion();

        $lines = [
            "<aside>Psy Shell $shellVersion (PHP $phpVersion — cli) by Justin Hileman</aside>",
            '------------------------------------------------------------------------',
            "\e[32mWelcome to the development console (REPL) for ramsey/composer-repl-lib.\e[39m",
            "\e[36mTo learn more about what you can do in PsySH, type `help`.\e[39m",
            '------------------------------------------------------------------------',
            '',
        ];

        $expected = implode("\n", $lines);

        $input = new StringInput('');
        $output = Factory::createOutput();
        $helperSet = new HelperSet();
        $io = new ConsoleIO($input, $output, $helperSet);

        $composerFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'composer.json';
        $composer = Factory::create($io, $composerFile);

        $repositoryRoot = (string) realpath(dirname($composerFile));
        $processFactory = new ProcessFactory();
        $bufferedOutput = new BufferedOutput();

        $repl = new Repl($repositoryRoot, $processFactory, $composer, false);
        $repl->run($input, $bufferedOutput);

        $this->assertSame($expected, $bufferedOutput->fetch());
    }
}
