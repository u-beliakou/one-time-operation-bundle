<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationBundle\Tests\Command;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ubeliakou\OneTimeOperationBundle\Command\ExecuteOperationCommand;
use Ubeliakou\OneTimeOperationSdk\Executor\OperationExecutor;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Lock\Exception\LockConflictedException;
use Ubeliakou\OneTimeOperationBundle\Executor\ExecutionLocker;

class ExecuteOperationCommandTest extends TestCase
{
    private MockObject $mockedExecutor;
    private MockObject $mockedLocker;
    private CommandTester $sut;
    
    protected function setUp(): void
    {
        $this->mockedExecutor = $this->createMock(OperationExecutor::class);
        $this->mockedLocker = $this->createMock(ExecutionLocker::class);

        $command = new ExecuteOperationCommand($this->mockedExecutor, $this->mockedLocker);

        $this->sut = new CommandTester($command);
    }
    
    public function testExecuteOnNoOperations(): void
    {
        $this->mockedExecutor->method('execute')->willReturn(0);
        
        $this->mockedLocker->expects($this->once())->method('acquire');
        $this->mockedLocker->expects($this->once())->method('release');

        $this->sut->execute([]);

        $output = $this->sut->getDisplay();

        $this->assertStringContainsString('No operations to execute', $output);
        $this->assertEquals(Command::SUCCESS, $this->sut->getStatusCode());
    }

    public function testExecuteHappyPath(): void
    {
        $this->mockedExecutor->method('execute')->willReturn(3);

        $this->mockedLocker->expects($this->once())->method('acquire');
        $this->mockedLocker->expects($this->once())->method('release');

        $this->sut->execute([]);

        $output = $this->sut->getDisplay();

        $this->assertStringContainsString('3 operation(s) executed successfully', $output);
        $this->assertEquals(Command::SUCCESS, $this->sut->getStatusCode());
    }

    public function testExecuteOnLockerIsAlreadyAcquired(): void
    {
        $this->mockedLocker->method('acquire')->willThrowException(new LockConflictedException());
        $this->mockedLocker->expects($this->never())->method('release');

        $this->sut->execute([]);

        $output = $this->sut->getDisplay();

        $this->assertStringContainsString('Another instance of the command is already running.', $output);
        $this->assertEquals(Command::SUCCESS, $this->sut->getStatusCode());
    }

    public function testExecuteOnException(): void
    {
        $this->mockedExecutor->method('execute')->willThrowException(new \Exception('Test exception'));

        $this->mockedLocker->expects($this->once())->method('acquire');
        $this->mockedLocker->expects($this->once())->method('release');

        $this->sut->execute([]);

        $output = $this->sut->getDisplay();

        $this->assertStringContainsString('An error occurred during execution: Test exception', $output);
        $this->assertEquals(Command::FAILURE, $this->sut->getStatusCode());
    }
}