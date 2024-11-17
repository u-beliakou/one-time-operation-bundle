<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationBundle\Command;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Lock\Exception\LockConflictedException;
use Ubeliakou\OneTimeOperationBundle\Executor\ExecutionContextProvider;
use Ubeliakou\OneTimeOperationSdk\Executor\OperationExecutor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ubeliakou\OneTimeOperationBundle\Executor\ExecutionLocker;

class ExecuteOperationCommand extends Command
{
    protected static $defaultName = 'one-time-operation:execute';

    private OperationExecutor $executor;
    private ExecutionLocker $locker;
    private ExecutionContextProvider $contextProvider;

    public function __construct(
        OperationExecutor $executor,
        ExecutionLocker $locker,
        ExecutionContextProvider $contextProvider
    ) {
        parent::__construct();

        $this->executor = $executor;
        $this->locker = $locker;
        $this->contextProvider = $contextProvider;
    }

    protected function configure(): void
    {
        $this->setDescription('Executes all pending one-time operations');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->locker->acquire();

            $executedOperations = $this->executor->execute(
                $this->contextProvider->getContext()
            );

            if ($executedOperations === 0) {
                $io->info('No operations to execute');
            } else {
                $io->success(
                    sprintf(
                        '%s operation(s) executed successfully',
                        $executedOperations,
                    )
                );
            }

        } catch (LockConflictedException $e) {
            $io->warning('Another instance of the command is already running.');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('An error occurred during execution: ' . $e->getMessage());
            $this->locker->release();

            return Command::FAILURE;
        }

        $this->locker->release();

        return Command::SUCCESS;
    }
}