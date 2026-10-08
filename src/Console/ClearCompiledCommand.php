<?php

namespace Jundayw\ProxyManager\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Jundayw\Proxy\Contracts\Configuration;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'proxy:clear-compiled', aliases: ['proxy:clear'])]
class ClearCompiledCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proxy:clear-compiled';

    /**
     * The console command name aliases.
     *
     * @var string[]
     */
    protected $aliases = ['proxy:clear'];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove the compiled class file';

    public function __construct(
        protected Configuration $configuration,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        foreach ([
                     $this->configuration->getNamespacePath(),
                     $this->configuration->getNamespacePath(false),
                 ] as $path) {
            if (!$this->deleteDirectory($path)) {
                $this->error("Failed to clear compiled class files in {$path}");
            }
        }

        $this->info('Compiled class files cleared successfully.');

        return Command::SUCCESS;
    }

    protected function deleteDirectory(string $directory): bool
    {
        if (!is_dir($directory)) {
            return false;
        }

        foreach (scandir($directory) ?: [] as $filename) {
            if ($filename === '.' || $filename === '..' || $filename === '.gitignore') {
                continue;
            }

            $fullPath = $directory.DIRECTORY_SEPARATOR.$filename;

            if (is_link($fullPath) || is_file($fullPath)) {
                unlink($fullPath);
            } else {
                $this->deleteDirectory($fullPath);
            }
        }

        return true;
    }
}
