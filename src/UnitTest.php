<?php

namespace Karriere\CodeQuality;

use Composer\Script\Event;
use Karriere\CodeQuality\Console\ScriptArgumentsTrait;
use Karriere\CodeQuality\Process\Process;

class UnitTest implements ComposerScriptInterface
{
    use ScriptArgumentsTrait;

    /**
     * The code phpunit command.
     *
     * @var array
     */
    private static $commands = [
        'default' => 'phpunit ',
        'verbose' => 'phpunit -v',
        'v'       => 'phpunit -v'
    ];

    public static function run(Event $event)
    {
        $eventArguments = self::getComposerScriptArguments($event->getArguments());

        $command = self::getArrayValueByEventArguments(self::$commands, $eventArguments);

        $composerIO = $event->getIO();
        $composerIO->write('<info>Running </info><fg=green;options=bold>' . $command . '</>');

        $process = new Process($command);
        $process->setTtyByArguments($eventArguments);
        $process->setProcessTimeoutByArguments($eventArguments);
        $process->run();

        $composerIO->write($process->getOutput());

        $exitCode = $process->getExitCode();

        $fail = self::hasParameterOption(ComposerScriptInterface::FLAG_FAIL, $eventArguments);

        if ($exitCode !== ComposerScriptInterface::EXIT_CODE_OK && $fail) {
            throw new \Exception('Failed with exit code '.$exitCode.'.');
        }

        return $exitCode;
    }
}
