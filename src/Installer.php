<?php

namespace Ob\Skeleton;

use Composer\Script\Event;
use Composer\Util\Filesystem;

class Installer
{
    private static $metadataToRemoveBefore = [
        'README.md',
        'CHANGELOG.md',
        'LICENSE',
        'CONTRIBUTING.md',
        'composer.json',
        'composer.lock',
    ];

    private static $templates = [
        'a README'              => 'README.md',
        'a CHANGELOG'           => 'CHANGELOG.md',
        'an MIT LICENSE'        => 'LICENSE',
        'a CONTRIBUTING file'   => 'CONTRIBUTING.md',
        'a Travis CI config'    => '.travis.yml',
        'a Scrutinizer config'  => '.scrutinizer.yml',
        'a Gulp config'         => 'gulpfile.js',
        'a Humbug config'       => 'humbug.json.dist',
        'a PHPUnit config'      => 'phpunit.xml.dist',
    ];

    private static $metadataToRemoveAfter = [
        'templates/',
        'src/Installer.php',
    ];

    public static function postCreateProject(Event $event)
    {
        $io = $event->getIO();

        $projectName = $io->ask('<info>What is the name of you project?</info> ');

        self::removeMetadata(self::$metadataToRemoveBefore);

        foreach (self::$templates as $name => $file) {
            if ($io->askConfirmation("<info>Do you want {$name}</info> <comment>[Y,n]</comment>? ", true)) {
                self::copyFile($file, $projectName);
            }
        }

        self::copyFile('.gitignore', $projectName);
        self::copyFile('composer.json', $projectName);

        self::removeMetadata(self::$metadataToRemoveAfter);
    }

    private static function copyFile($fileName, $projectName)
    {
        $projectRoot = dirname(__DIR__);

        $text = file_get_contents($projectRoot . '/templates/' . $fileName);

        if ($projectName) {
            $text = str_replace('[[project_name]]', $projectName, $text);
            $text = str_replace('[[year]]', date('Y'), $text);
            $text = str_replace('[[date]]', date('Y-m-d'), $text);
        }

        file_put_contents($projectRoot . '/' . $fileName, $text);
    }

    private static function removeMetadata($files)
    {
        $filesystem  = new Filesystem();
        $projectRoot = dirname(__DIR__);

        foreach ($files as $file) {
            $path = $projectRoot . DIRECTORY_SEPARATOR . $file;

            if (is_dir($path)) {
                $filesystem->removeDirectory($path);
            } else {
                $filesystem->remove($path);
            }
        }
    }
}
