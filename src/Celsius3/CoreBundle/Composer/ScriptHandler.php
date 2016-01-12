<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Celsius3\CoreBundle\Composer;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;
use Composer\Script\CommandEvent;

class ScriptHandler
{

    /**
     * Dumps the assets
     *
     * @param $event CommandEvent A instance
     */
    public static function asseticDump(CommandEvent $event)
    {
        $options = self::getOptions($event);
        $appDir = $options['symfony-app-dir'];

        if (!is_dir($appDir)) {
            echo 'The symfony-app-dir (' . $appDir
            . ') specified in composer.json was not found in '
            . getcwd() . ', can not clear the cache.' . PHP_EOL;

            return;
        }

        static::executeCommand($event, $appDir, 'assetic:dump', $options['process-timeout']);
    }
    
    public static function redisFlushAll(CommandEvent $event)
    {
        $options = self::getOptions($event);
        $appDir = $options['symfony-app-dir'];

        if (!is_dir($appDir)) {
            echo 'The symfony-app-dir (' . $appDir
            . ') specified in composer.json was not found in '
            . getcwd() . ', can not clear the cache.' . PHP_EOL;

            return;
        }

        static::executeCommand($event, $appDir, 'redis:flushall -n --client cache --env dev', $options['process-timeout']);
    }

    protected static function executeCommand(CommandEvent $event, $appDir, $cmd, $timeout = 300)
    {
        $php = escapeshellarg(self::getPhp());
        $console = escapeshellarg($appDir . '/console');
        if ($event->getIO()->isDecorated()) {
            $console .= ' --ansi';
        }

        $process = new Process($php . ' ' . $console . ' ' . $cmd, null, null, null, $timeout);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
        if (!$process->isSuccessful()) {
            throw new \RuntimeException(
            sprintf(
                    'An error occurred when executing the "%s" command.', escapeshellarg($cmd)));
        }
    }

    protected static function getOptions(CommandEvent $event)
    {
        $options = array_merge(
                array('symfony-app-dir' => 'app', 'symfony-web-dir' => 'web',
            'symfony-assets-install' => 'hard'), $event->getComposer()->getPackage()->getExtra());

        $options['process-timeout'] = $event->getComposer()->getConfig()
                ->get('process-timeout');

        return $options;
    }

    protected static function getPhp()
    {
        $phpFinder = new PhpExecutableFinder;
        if (!$phpPath = $phpFinder->find()) {
            throw new \RuntimeException(
            'The php executable could not be found, add it to your PATH environment variable and try again');
        }

        return $phpPath;
    }
}
