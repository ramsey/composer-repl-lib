<?php

/**
 * This file is part of ramsey/composer-repl-lib
 *
 * ramsey/composer-repl-lib is open source software: you can distribute
 * it and/or modify it under the terms of the MIT License
 * (the "License"). You may not use this file except in
 * compliance with the License.
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or
 * implied. See the License for the specific language governing
 * permissions and limitations under the License.
 *
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license https://opensource.org/licenses/MIT MIT License
 */

declare(strict_types=1);

namespace Ramsey\Dev\Repl;

use Composer\Composer;
use PHPUnit\Framework\TestCase;
use Psy\Configuration;
use Psy\Shell;
use Ramsey\Dev\Repl\Process\ProcessFactory;
use Ramsey\Dev\Repl\Psy\ElephpantCommand;
use Ramsey\Dev\Repl\Psy\PhpunitRunCommand;
use Ramsey\Dev\Repl\Psy\PhpunitTestCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function getenv;
use function sprintf;

/**
 * Customizes and controls an instance of PsySH Shell
 *
 * @psalm-type ComposerReplIncludesType = array{includes?: array<string>}
 * @psalm-type ComposerExtrasType = array{"ramsey/composer-repl"?: ComposerReplIncludesType}
 */
class Repl
{
    private string $repositoryRoot;
    private ProcessFactory $processFactory;
    private Composer $composer;
    private Configuration $configuration;

    /**
     * @var array{env: array<string, string>, phpunit: TestCase}
     */
    private array $scopeVariables;

    public function __construct(
        string $repositoryRoot,
        ProcessFactory $processFactory,
        Composer $composer,
        bool $isInteractive = true
    ) {
        $this->repositoryRoot = $repositoryRoot;
        $this->processFactory = $processFactory;
        $this->composer = $composer;
        $this->scopeVariables = $this->buildScopeVariables();
        $this->configuration = $this->buildConfig($composer, $isInteractive);
    }

    public function run(?InputInterface $input = null, ?OutputInterface $output = null): int
    {
        $shell = new Shell($this->getConfig());
        $shell->setScopeVariables($this->getScopeVariables());
        $shell->add(new PhpunitTestCommand());
        $shell->add(new PhpunitRunCommand(
            $this->repositoryRoot,
            $this->processFactory,
            $this->composer,
        ));
        $shell->add(new ElephpantCommand());

        return $shell->run($input, $output);
    }

    /**
     * @psalm-mutation-free
     */
    public function getConfig(): Configuration
    {
        return $this->configuration;
    }

    /**
     * @return array<string, mixed>
     *
     * @psalm-mutation-free
     */
    public function getScopeVariables(): array
    {
        return $this->scopeVariables;
    }

    private function buildConfig(Composer $composer, bool $isInteractive): Configuration
    {
        $config = new Configuration([
            'startupMessage' => $this->buildStartupMessage($composer),
            'colorMode' => Configuration::COLOR_MODE_FORCED,
            'updateCheck' => 'never',
            'useBracketedPaste' => true,
            'defaultIncludes' => $this->buildDefaultIncludes($composer),
        ]);

        if ($isInteractive === false) {
            $config->setInteractiveMode(Configuration::INTERACTIVE_MODE_DISABLED);
        }

        return $config;
    }

    private function buildStartupMessage(Composer $composer): string
    {
        $startupMessage = <<<'EOD'
            ------------------------------------------------------------------------
            <info>Welcome to the development console (REPL)%s.</info>
            <fg=cyan>To learn more about what you can do in PsySH, type `help`.</>
            ------------------------------------------------------------------------
            EOD;

        $packageName = $composer->getPackage()->getPrettyName();
        $forPackage = '';

        if ($packageName !== '__root__') {
            $forPackage = " for $packageName";
        }

        return sprintf($startupMessage, $forPackage);
    }

    /**
     * @return array{env: array<string, string>, phpunit: TestCase}
     */
    private function buildScopeVariables(): array
    {
        return [
            'env' => getenv(),
            'phpunit' => $this->getPhpUnitTestCase(),
        ];
    }

    /**
     * @return string[]
     */
    private function buildDefaultIncludes(Composer $composer): array
    {
        /** @var ComposerExtrasType $extra */
        $extra = $composer->getPackage()->getExtra();

        return $extra['ramsey/composer-repl']['includes'] ?? [];
    }

    /**
     * @psalm-suppress PropertyNotSetInConstructor
     * @psalm-suppress InternalMethod
     */
    private function getPhpUnitTestCase(): TestCase
    {
        return new class extends TestCase {
        };
    }
}
