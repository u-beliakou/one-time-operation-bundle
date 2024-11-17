<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Ubeliakou\OneTimeOperationSdk\Generator\OperationGenerator;
use Ubeliakou\OneTimeOperationSdk\Generator\Exception\GenerationException;

class GenerateOperationCommand extends Command
{
    protected static $defaultName = 'one-time-operation:generate';

    private OperationGenerator $generator;

    public function __construct(OperationGenerator $generator)
    {
        parent::__construct();
        $this->generator = $generator;
    }

    protected function configure(): void
    {
        $this->setDescription('Generates a new one-time operation template');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $operation = $this->generator->generate();
            $io->success('Created operation: ' . $operation->filePath);
        } catch (GenerationException $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}