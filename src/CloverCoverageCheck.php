<?php

namespace Karriere\CodeQuality;

use Composer\Script\Event;
use Karriere\CodeQuality\Console\ScriptArgumentsTrait;
use Karriere\CodeQuality\Process\Process;

class CloverCoverageCheck implements ComposerScriptInterface
{
    use ScriptArgumentsTrait;

    /**
     * The code coverage check command.
     *
     * @var array
     */
    private static $commands = [
        'default' => 'coverage-checker clover.xml 100',
        '0'  => 'coverage-checker clover.xml 0',
        '25' => 'coverage-checker clover.xml 25',
        '50' => 'coverage-checker clover.xml 50',
        '75' => 'coverage-checker clover.xml 75',
        '100' => 'coverage-checker clover.xml',

    ];

    public static function run(Event $event)
    {
        $eventArguments = self::getComposerScriptArguments($event->getArguments());

        $command = self::getArrayValueByEventArguments(self::$commands, $eventArguments, 'min-coverage');

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
