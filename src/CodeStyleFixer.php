<?php

namespace Karriere\CodeQuality;

use Composer\Script\Event;
use Karriere\CodeQuality\Console\ScriptArgumentsTrait;
use Karriere\CodeQuality\Process\Process;

class CodeStyleFixer implements ComposerScriptInterface
{
    use ScriptArgumentsTrait;

    /**
     * The code style fixer command.
     *
     * @var array
     */
    private static $commands = [
        'default' => 'php-cs-fixer fix',
        'dry-run' => 'php-cs-fixer fix --dry-run',
        'dry-run-diff' => 'php-cs-fixer fix --dry-run --diff',
    ];
    //private static $command = 'php-cs-fixer fix src --rules=@Symfony --using-cache=false';

    public static function run(Event $event)
    {
        $eventArguments = self::getComposerScriptArguments($event->getArguments());

        $command = self::getArrayValueByEventArguments(self::$commands, $eventArguments);

        $composerIO = $event->getIO();

        $composerIO->write('<info>Running </info><fg=green;options=bold>'.$command.'</>');

        $process = new Process($command);
        $process->setTtyByArguments($eventArguments);
        $process->setProcessTimeoutByArguments($eventArguments);
        $process->run();

        $composerIO->write($process->getOutput());

        $exitCode = $process->getExitCode();

        if ($exitCode === ComposerScriptInterface::EXIT_CODE_OK) {
            $composerIO->write('<fg=black;bg=green>Finished without errors!</>');
        } elseif (self::hasParameterOption(ComposerScriptInterface::FLAG_FAIL, $eventArguments)) {
            $composerIO->write('<fg=black;bg=red>Finished with errors!</>');
        }

        return $exitCode;
    }
}
