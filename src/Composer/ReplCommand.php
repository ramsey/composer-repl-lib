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

namespace Ramsey\Dev\Repl\Composer;

use Composer\Command\BaseCommand;
use Composer\Composer;
use Composer\Config;
use Ramsey\Dev\Repl\Process\ProcessFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function method_exists;

use const DIRECTORY_SEPARATOR;

/**
 * Composer command to launch a PsySH REPL
 */
class ReplCommand extends BaseCommand
{
    private string $repositoryRoot;
    private ProcessFactory $processFactory;
    private bool $isInteractive;

    public function __construct(
        string $repositoryRoot,
        ProcessFactory $processFactory,
        Composer $composer,
        bool $isInteractive = true
    ) {
        parent::__construct();

        $this->repositoryRoot = $repositoryRoot;
        $this->processFactory = $processFactory;
        $this->setComposer($composer);
        $this->isInteractive = $isInteractive;
    }

    protected function configure(): void
    {
        $this
            ->setName('repl')
            ->setDescription('Launches a development console (REPL) for PHP.')
            ->setAliases(['shell']);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        Config::disableProcessTimeout();
        $composer = $this->requireComposerLocal();

        /** @var string $binDir */
        $binDir = $composer->getConfig()->get('bin-dir');
        $replCommand = $binDir . DIRECTORY_SEPARATOR . 'repl';

        $process = $this->processFactory->factory([$replCommand], $this->repositoryRoot);
        $process->setTimeout(null);
        $process->setIdleTimeout(null);

        if (DIRECTORY_SEPARATOR !== '\\' && $this->isInteractive) {
            $process->setTty(true); // @codeCoverageIgnore
        }

        return $process->run();
    }

    /**
     * @codeCoverageIgnore
     */
    private function requireComposerLocal(): Composer
    {
        if (method_exists($this, 'requireComposer')) {
            /** @var Composer */
            return $this->requireComposer();
        }

        /**
         * @var Composer
         * @psalm-suppress DeprecatedMethod
         */
        return $this->getComposer(true);
    }
}
