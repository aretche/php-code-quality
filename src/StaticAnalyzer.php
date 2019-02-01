<?php

namespace Karriere\CodeQuality;

use Composer\Script\Event;
use Karriere\CodeQuality\Console\ScriptArgumentsTrait;
use Karriere\CodeQuality\Process\Process;

class StaticAnalyzer implements ComposerScriptInterface
{
    use ScriptArgumentsTrait;

    /**
     * Static analyzer commands.
     *
     * @var array
     */
    private static $commands = [
        'local'   => 'phpstan analyse -c ' . __DIR__ . '/../config/config.level7.neon src --level=7 --ansi',
        'jenkins' => 'phpstan analyse -c ' . __DIR__ . '/../config/config.level7.neon src --level=7 --ansi --no-progress -n'
    ];

    public static function run(Event $event)
    {
        $eventArguments = self::getComposerScriptArguments($event->getArguments());

        $command = self::getArrayValueByEventArguments(self::$commands, $eventArguments, 'env');

        $composerIO = $event->getIO();
        $composerIO->write('<info>Running </info><fg=green;options=bold>' .  $command . '</>');

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
