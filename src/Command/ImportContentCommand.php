<?php

declare(strict_types=1);

namespace App\Command;

use App\Import\ProjectImportResult;
use App\Import\ProjectScreenshotResult;
use App\Message\ImportProject;
use App\Message\TakeProjectScreenshot;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class ImportContentCommand extends Command
{
    use HandleTrait;

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     * @var string
     */
    protected static $defaultName = 'app:import-content';

    public function __construct(MessageBusInterface $messageBus)
    {
        parent::__construct();

        $this->messageBus = $messageBus;
    }

    protected function configure(): void
    {
        $this->setDescription('Import content from the supplied directory')
            ->addArgument('path', InputArgument::REQUIRED, 'The path to import content from')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $path = $input->getArgument('path');

        if (!is_string($path) || $path === '' || !is_dir($path)) {
            $io->error('Incorrect path supplied');

            return self::FAILURE;
        }

        $di = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));

        foreach ($di as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $content = file_get_contents($file->getPathname());
            if ($content === false) {
                $io->error('Unable to read file ' . $file->getPathname() . '. Ignoring');

                continue;
            }

            /** @var ProjectImportResult $importResult */
            $importResult = $this->handle(new ImportProject($content));

            $io->writeln(sprintf(
                '<info>%s imported successfully</info> - %s',
                $file->getPathname(),
                ($importResult->isNew()) ? 'Created a new project' : 'Updated existing project'
            ));

            $project = $importResult->getProject();

            /** @var ProjectScreenshotResult $screenshotResult */
            $screenshotResult = $this->handle(new TakeProjectScreenshot($project));

            if ($screenshotResult->isSuccessful()) {
                $io->writeln(sprintf(
                    'Took screenshot of <info>%s</info> and saved to <info>%s</info>',
                    $project->getUrl(),
                    $screenshotResult->getScreenshotPath()
                ));
            }
        }

        return self::SUCCESS;
    }
}
