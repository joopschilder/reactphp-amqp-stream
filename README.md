# `reactphp-amqp-stream`
This package provides a way to work with AMQP messages as if it were a stream in the ReactPHP EventLoop component.

## Usage

```php
use JoopSchilder\React\Stream\AMQP\Message;
use JoopSchilder\React\Stream\AMQP\NonBlockingAMQPInput;
use JoopSchilder\React\Stream\AMQP\ValueObject\Queue;
use JoopSchilder\React\Stream\NonBlockingInput\ReadableNonBlockingInputStream;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use React\EventLoop\Factory;

$connection = new AMQPStreamConnection('localhost', '5672', 'guest', 'guest');
$consumer = new NonBlockingAMQPInput($connection, new Queue('my_command_queue'));

$loop = Factory::create();
$stream = new ReadableNonBlockingInputStream($consumer, $loop);
$stream->on('data', fn() => print('m'));
$stream->on('data', fn(Message $message) => $message->acknowledge());
$loop->addPeriodicTimer(0.2, fn() => print('.'));
$loop->run();
```
