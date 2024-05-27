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

namespace Ramsey\Dev\Repl\Psy;

use Psy\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ElephpantCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('🐘')
            ->setAliases(['elephpant'])
            ->setDescription('¯\_(ツ)_/¯')
            ->setHelp('¯\_(ツ)_/¯');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(
            <<<'EOF'
            <fg=blue>
                              ╓φÆÆφ╤ ┌╤φφφ╗
                           ┌▓╨  ,▄Φ▀╙      └▀╗
                      ,╖╤φ▓▌▀▓▓▀╙            ╙▓╙╙╙╙╙╙╙└└└└└└└╙╙╙╨▀▀Φφ╕
                 ,é▀▀╙└                       ╙▌             ...,┌┌:;┘╙▀▀╕          ,╤φφ╕
               ╒▌╙                             ▓µ          '..,,┌┌¡¡¡░░░░┤╢▌╤╤╤╤╤╤╤▓▓ΦΦΦ▓φ╖
              ╣▀    ▄ΦΦ▀Γ²¬                    ╫▌         ...,┌┌┐┐¡¡░░░░░░░░╣▓╣╣╣▓▓▌φ▄▄▄▄▄▀
             ⌠▌    ╙                           ╞▌       ]φφ ┌┌:┐¡¡░░░░░░░░░░░╩▓    `╩╣▄▄╣╛
             ▓Γ        ,.,                     ╞▌   '   ▓▓▌ ''`ⁿ≥░░`""""""ⁿ╚▒▒╩▓
             ▓─       Γ j▒╣                    ╫▀▀▓▓▌ .╒▓▓▌▀▀▓▓▓▌` ╫▓▓▀▀▀▓▓▓╦╙▒╫▌
             ▓        \ -╜     ▄  ╓Γ          ▐▌   ▓▓▌ ╣▓▓ ░░ ▓▓▓, ▓▓▌╚░╠[╢▓▓ ▒╩▓
             ▓                  └▓          ╥▓▀ ' Æ▓▓¬ ▓▓Γ≥¡░┌▓▓Γ[@▓▓ ░▒╚ ▓▓▌.▒▒▓
             ▓                  ┌▌     ▄Φ▀╫▓▓▓Φ▓▓▓▓╩. ║▓▓ ░░ ╫▓▓  ▓▓▓▓▓▓▓▓▌Γ╔▒╠╠▓
             ▓                  ╣     ▓Γ  ▓▓▌ ... «░┌≥»»»≥░░≥«==╚]▓▓⌐≡≡≡≥≥▒▒╠╠╠╣▌
             ▓               ,▄╝          ▀▀ ░.,,┌┌┐┐¡¡░░░░░░░░░ ╩▀▀.▒▒▒▒▒▒╠╠╠╫▓
             ▓          ▄▀╙└└▓▀▀Φφ▄▌       ...,┌┌:;¡¡░░░░░░░░░░░░▒▒▒▒▒▒▒▒╠╠╠╠╠╠▓
             ▓         ▓     ▓⌐   ╞▓     ...,,┌┌┐¡¡¡░░░░░░░░░░░▒▒▒▒▒▒▒▒╠╠╠╠╠╠╠╠▓
             ▓        ║▌     ▓⌐   ╞▓   '..,┌┌:┐¡¡¡░░│░░░░░░░░▒▒▒▒▒▒▒▒▒╠╠╠╠╠╠╠╠╠▓
             ▓        ▓Γ     ▓⌐   ╞▓  ...,┌┌┐¡¡¡░░░░▓▒▄▄▒▄▄▄▄▓▌▒▒▒▒▒╠╠╠╠╠╠╠╠╠╠╠▓
             ▓        ▓      ▓⌐   ╞▓..~,┌┌┌┐¡¡░░░░░░▓   ▓╠╩╩▒╫▌▒▒▒╠╠╠╠╠╠╠╠╠╠╠╠╠▓
             ▓       ]▓      ▓⌐   ╞▓.,,┌┌┐¡¡░░░░░░░░▓   ▓▒▒▒▒╫▌▒▒╠╠╠╠╠╠╠╠╠╠╠╠╠╠▓
             ▓       ▐▓      ▓⌐  .╞▓,┌┌┐¡¡¡░░░░░░░░░▓   ▓▒▒▒▒╫▌╠╠╠╠╠╠╠╠╠╠╠╠╠╠╠╠▓
             ▓       ║▌      ╫▒..~╞▓┌┌┐¡¡░░░░░░░░░░░▓   ▓▒▒▒▒╫▌╠╠╠╠╠╠╠╠╠╠╠╠╠╠╠╠▓
             ▓       ╫Γ       ▀▌Q┌▐▓┐\¡¡░░░░░░░░░░░▒▓   '▓╬▒╠╫▓╠╠╠╠╠╠╠╠╠╠╠╠╠╠╠╢▓
             └▀▄▄╓╓▄Φ╨           ╙╜▀▌p░░░░░░░░░░░▒▒╣╝      └╜╝▀▓╬╠╠╠╠╠╠╠╠╠╠╠╬╣▌
                                     └╝▀╣╣▄▄╣╣╣╣╣╝└              ╙╨╝▀╣╣╣╣╝╝╙^


                    This implementation of PsySH has Super ElePHPant Powers!
                              https://afieldguidetoelephpants.net
                                      https://elephpant.me
            </>
            EOF,
        );

        return 0;
    }
}
