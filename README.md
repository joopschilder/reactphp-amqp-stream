# `reactphp-amqp-stream`
This package provides a way to work with AMQP messages as if it were a stream in the ReactPHP EventLoop component.

## Usage
The code below can also be found in `examples/simple_queue_consumer.php`.

```php
<?php

use JoopSchilder\React\Stream\AMQP\Message;
use JoopSchilder\React\Stream\AMQP\NonBlockingAMQPInputBuilder;
use JoopSchilder\React\Stream\AMQP\ValueObject\Queue;
use JoopSchilder\React\Stream\NonBlockingInput\ReadableNonBlockingInputStream;
use React\EventLoop\Factory;

require_once __DIR__ . '/path/to/autoload.php';

// Define a queue:
$queue = new Queue('my_queue');

// Static factory method is available too:
$queue = Queue::create('my_queue')->setIsDurable(true);

// The builder allows you to define an exchange or a custom connection.
// By default, an AMQPStreamConnection is used (guest:guest@localhost:5672).
$input = NonBlockingAMQPInputBuilder::create($queue)->build();

// Create the EventLoop and add the AMQP consumer input to it:
$loop = Factory::create();
$stream = new ReadableNonBlockingInputStream($input, $loop);

// By default, a message needs to be acknowledged.
// An option will be added to enable auto acknowledgement and some more
// advanced behaviors (acknowledge on receive, acknowledge after handlers).
$stream->on('data', fn(Message $message) => print('m'));
$stream->on('data', fn(Message $message) => $message->acknowledge());

// Add a timer for demonstration purposes...
$loop->addPeriodicTimer(0.2, fn() => print('.'));

// Run it.
$loop->run();
```
