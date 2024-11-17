<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationBundle\Tests\Command;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Command\Command;
use Ubeliakou\OneTimeOperationBundle\Command\GenerateOperationCommand;
use Ubeliakou\OneTimeOperationSdk\Generator\OperationGenerator;
use Ubeliakou\OneTimeOperationSdk\Generator\Exception\GenerationException;
use Ubeliakou\OneTimeOperationSdk\Inventory\Dto\OperationFile;

class GenerateOperationCommandTest extends TestCase
{
    private MockObject $mockedOperationGenerator;
    private CommandTester $sut;

    protected function setUp(): void
    {
        $this->mockedOperationGenerator = $this->createMock(OperationGenerator::class);

        $command = new GenerateOperationCommand($this->mockedOperationGenerator);
        $this->sut = new CommandTester($command);
    }

    public function testExecuteSuccessfullyGeneratesOperation(): void
    {
        $operationFile = new OperationFile('20241010303030', '/test');
        $this->givenGeneratorReturnsOperationFile($operationFile);

        $this->sut->execute([]);

        $output = $this->sut->getDisplay();
        $this->assertStringContainsString('Created operation: /test/Operation20241010303030.php', $output);
        $this->assertSame(Command::SUCCESS, $this->sut->getStatusCode());
    }

    public function testExecuteHandlesGenerationException(): void
    {
        $this->givenGeneratorThrowsException('An error occurred');

        $this->sut->execute([]);

        $output = $this->sut->getDisplay();
        $this->assertStringContainsString('An error occurred', $output);
        $this->assertSame(Command::FAILURE, $this->sut->getStatusCode());
    }

    private function givenGeneratorReturnsOperationFile(OperationFile $operationFile): void
    {
        $this->mockedOperationGenerator
            ->expects($this->once())
            ->method('generate')
            ->willReturn($operationFile);
    }

    private function givenGeneratorThrowsException(string $errorMessage): void
    {
        $this->mockedOperationGenerator
            ->expects($this->once())
            ->method('generate')
            ->willThrowException(
                new GenerationException($errorMessage)
            );
    }
}