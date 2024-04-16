<?php

declare(strict_types=1);

namespace Ramsey\Test\Dev\Repl;

use Composer\Factory;
use Composer\IO\ConsoleIO;
use PHPUnit\Framework\TestCase as PhpUnitTestCase;
use Psy\Configuration;
use Psy\Shell;
use Ramsey\Dev\Repl\Process\ProcessFactory;
use Ramsey\Dev\Repl\Repl;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

use function dirname;
use function getenv;
use function implode;
use function phpversion;
use function realpath;
use function str_replace;

use const DIRECTORY_SEPARATOR;
use const PHP_OS_FAMILY;

class ReplTest extends TestCase
{
    private Repl $repl;

    protected function setUp(): void
    {
        $input = new StringInput('');
        $output = Factory::createOutput();
        $helperSet = new HelperSet();
        $io = new ConsoleIO($input, $output, $helperSet);

        $composerFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'composer.json';
        $composer = Factory::create($io, $composerFile);

        $repositoryRoot = (string) realpath(dirname($composerFile));
        $processFactory = new ProcessFactory();

        $this->repl = new Repl($repositoryRoot, $processFactory, $composer, false);
    }

    public function testReplCommand(): void
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $this->markTestSkipped(
                'Skipping on Windows due to a problem recognizing the command.',
            );
        }

        if (
            getenv('GITHUB_ACTIONS') === 'true'
            || getenv('COMPOSER_REPL') === '1'
            || getenv('CAPTAIN_HOOK_PRE_PUSH') === '1'
        ) {
            $this->markTestSkipped(
                'Skipping when running in this context, due to a problem returning control to the calling script.',
            );
        }

        $processFactory = new ProcessFactory();
        $process = $processFactory->factory(['bin/repl'], dirname(__DIR__));
        $process->setTimeout(3);
        $process->setPty(true);

        try {
            $process->mustRun();
        } catch (ProcessTimedOutException $exception) { // @phpstan-ignore-line
        }

        $shellVersion = Shell::VERSION;
        $phpVersion = phpversion();

        $lines = [
            "\e[90mPsy Shell $shellVersion (PHP $phpVersion — cli) by Justin Hileman\e[39m",
            '------------------------------------------------------------------------',
            "\e[32mWelcome to the development console (REPL) for ramsey/composer-repl-lib.\e[39m",
            "\e[36mTo learn more about what you can do in PsySH, type `help`.\e[39m",
            '------------------------------------------------------------------------',
        ];

        $expected = implode("\r\n", $lines);

        // Remove the prompt string, so we don't have to worry about comparing
        // with different versions of PsySH (some include the paste-bracketing
        // escape characters).
        $output = str_replace(
            ["\r\n\e[?2004h\e[?2004h> ", "\r\n> "],
            '',
            $process->getOutput(),
        );

        $this->assertSame($expected, $output);
    }

    public function testReplRun(): void
    {
        if (
            getenv('GITHUB_ACTIONS') === 'true'
            || getenv('COMPOSER_REPL') === '1'
            || getenv('CAPTAIN_HOOK_PRE_PUSH') === '1'
        ) {
            $this->markTestSkipped(
                'Skipping when running in this context, due to a problem returning control to the calling script.',
            );
        }

        $shellVersion = Shell::VERSION;
        $phpVersion = phpversion();

        $lines = [
            "<whisper>Psy Shell $shellVersion (PHP $phpVersion — cli) by Justin Hileman</whisper>",
            '------------------------------------------------------------------------',
            "\e[32mWelcome to the development console (REPL) for ramsey/composer-repl-lib.\e[39m",
            "\e[36mTo learn more about what you can do in PsySH, type `help`.\e[39m",
            '------------------------------------------------------------------------',
            '',
        ];

        $expected = implode("\n", $lines);

        $input = new StringInput('');
        $bufferedOutput = new BufferedOutput();

        $this->repl->run($input, $bufferedOutput);

        $this->assertSame($expected, $bufferedOutput->fetch());
    }

    /**
     * This test is primarily for coverage purposes when running on
     * GitHub Actions. Since {@see testReplRun()} will not pass when
     * running on GitHub Actions, we use this test to execute the same
     * code using {@see NullOutput} so that the coverage reports see
     * {@see Repl} as fully covered.
     */
    public function testRunExecutesWithoutErrors(): void
    {
        $input = new StringInput('');
        $nullOutput = new NullOutput();

        $this->repl->run($input, $nullOutput);

        $this->assertInstanceOf(Configuration::class, $this->repl->getConfig());
    }

    public function testStartUpMessageIsSetOnTheConfig(): void
    {
        $expected = <<<'EOD'
            ------------------------------------------------------------------------
            <fg=green>Welcome to the development console (REPL) for ramsey/composer-repl-lib.</>
            <fg=cyan>To learn more about what you can do in PsySH, type `help`.</>
            ------------------------------------------------------------------------
            EOD;

        $this->assertSame($expected, $this->repl->getConfig()->getStartupMessage());
    }

    public function testDefaultIncludesPropertyIsSetOnTheConfig(): void
    {
        $expected = ['repl.php'];

        $this->assertSame($expected, $this->repl->getConfig()->getDefaultIncludes());
    }

    public function testGetScopeVariables(): void
    {
        $scopeVariables = $this->repl->getScopeVariables();

        $this->assertArrayHasKey('env', $scopeVariables);
        $this->assertIsArray($scopeVariables['env']);
        $this->assertArrayHasKey('COMPOSER_REPL', $scopeVariables['env']);
        $this->assertSame('1', $scopeVariables['env']['COMPOSER_REPL']);
        $this->assertArrayHasKey('phpunit', $scopeVariables);
        $this->assertInstanceOf(PhpUnitTestCase::class, $scopeVariables['phpunit']);
    }
}
