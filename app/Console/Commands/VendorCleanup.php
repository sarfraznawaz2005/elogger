<?php

namespace App\Console\Commands;

use FilesystemIterator;
use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/* @noinspection ALL */

final class VendorCleanup extends Command
{
    protected $signature = 'vendor:cleanup {--o : Verbose Output} {--dry : Runs in dry mode without deleting files.}';
    protected $description = 'Cleans up useless files from vendor folder.';

    // default patterns for common files
    private const PATTERNS = [
        '.git',
        '.github',
        'test',
        'tests',
        'docs',
        'travis',
        'demo',
        'demos',
        'example',
        'examples',
        'todo',
        'license',
        'changelog*',
        'contributing*',
        'upgrading*',
        'upgrade*',
        '.idea',
        '.vagrant',
        'readme*',
        '_ide_helper.php',
        '{,.}*.yml',
        '*.dist',
        '*.md',
        '*.log',
        '*.txt',
        '*.pdf',
        '*.xls',
        '*.doc',
        '*.docx',
        '*.png',
        '*.gif',
        '*.bmp',
        '*.ico',
        '*.jpg',
        '*.jpeg',
        '*.htm',
        '*.html',
        '.php_cs*',
        '.scrutinizer',
        '.gitignore',
        '.gitattributes',
        '.editorconfig',
        'dockerfile',
        'phpcs.xml',
        'phpunit.xml',
        'build.xml',
        'package.xml',
        'package.json',
        'Makefile',
        'Doxyfile',
        'gulpfile.js',
        'bower.json',
        'karma.conf.js',
        'yarn.lock',
        '.babelrc',
        'package.js',
        '.htaccess',
        'CNAME',
        'LICENSE*',
        '.gitmodules',
        'composer.json',
        'composer.lock',
    ];

    // These paths/patterns will NOT be deleted
    private const EXCLUDED = [
        '*.css',
        '*.js',
        'laravel-mail-preview/tests',
        'folklore/image/tests',
        'laravel/dusk/stubs',
        '*/mpdf/mpdf/*',
        '*/codecept/*',
        '*/codeception/*',
    ];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $patterns = array_diff(self::PATTERNS, self::EXCLUDED);

        $directories = $this->expandTree(base_path('vendor'));

        $isDry = $this->option('dry');

        foreach ($directories as $directory) {
            foreach ($patterns as $pattern) {

                $casePattern = preg_replace_callback('/([a-z])/i', fn(array $matches): string => $this->prepareWord($matches), $pattern);

                $files = glob($directory . '/' . $casePattern, GLOB_BRACE);

                if (!$files) {
                    continue;
                }

                // filter out based on ignored patterns
                $files = $this->filterIgnoredFiles($files);

                foreach ($files as $file) {
                    if (is_dir($file)) {
                        $this->out('DELETING DIR: ' . $file);

                        if (!$isDry) {
                            $this->delTree($file);
                        }
                    } else {
                        $this->out('DELETING FILE: ' . $file);

                        if (!$isDry) {
                            @unlink($file);
                        }
                    }
                }
            }
        }

        $this->out('Vendor Cleanup Done!');
    }

    /**
     * Recursively traverses the directory tree
     *
     * @param string $dir
     * @return array
     */
    private function expandTree(string $dir): array
    {
        $directories = [];

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        foreach ($iterator as $file) {
            if ($file->isDir() && !in_array($file->getPath(), $directories, true)) {
                $directories[] = $file->getPath();
            }
        }

        unset($directories[0]);

        return $directories;
    }

    /**
     * Recursively deletes the directory
     *
     * @param string $dir
     * @return void
     */
    private function delTree(string $dir): void
    {
        if (!file_exists($dir) || !is_dir($dir)) {
            return;
        }

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($iterator as $filename => $fileInfo) {
            if ($fileInfo->isDir()) {
                @rmdir($filename);
            } else {
                @unlink($filename);
            }
        }

        @rmdir($dir);
    }

    /**
     * Prepare word
     *
     * @param array $matches
     * @return string
     */
    private function prepareWord(array $matches): string
    {
        return '[' . strtolower($matches[1]) . strtoupper($matches[1]) . ']';
    }

    private function filterIgnoredFiles(array $files): array
    {
        $files = array_map(static fn($file) => str_replace('\\', '/', $file), $files);

        foreach ($files as $i => $file) {
            foreach (self::EXCLUDED as $pattern) {
                if ($this->patternMatch($pattern, $file)) {
                    $this->out('SKIPPED: ' . $file);
                    unset($files[$i]);
                    break;
                }
            }
        }

        return array_values($files);
    }

    private function patternMatch(string $pattern, string $string): bool|int
    {
        return preg_match('#^' . strtr(preg_quote($pattern, '#'), ['\*' => '.*', '\?' => '.']) . '$#i', $string);
    }

    private function out($message): void
    {
        if ($this->option('o') || $this->option('dry')) {
            echo $message . PHP_EOL;
        }
    }
}
