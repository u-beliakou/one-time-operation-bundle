# One-time operation bundle for Symfony

## Introduction
The repository provides an implementation of one-time operation concept for data migrations.

## Configuration

Add the following parameters ( `/config/packages/oto.yaml` ):
```yaml
oto:
  project: test
```

## Usage

Run the command `php bin/console one-time-operation:execute` to execute all available operations.

Run the command `php bin/console one-time-operation:generate` to generate an operation.

## Advanced

If an operation is to be run only on a specific project or for a specific environment, the operation should implement
the following interfaces:

* \Ubeliakou\OneTimeOperationSdk\Operation\EnvironmentAwareOperationInterface
* \Ubeliakou\OneTimeOperationSdk\Operation\ProjectAwareOperationInterface

The project is specified in the config mentioned above.

## License
This project is licensed under the MIT License.