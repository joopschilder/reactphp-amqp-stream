# `reactphp-amqp-stream`
This package provides a way to work with AMQP messages as if it were a stream in the ReactPHP EventLoop component.  
It relies on one of my other packages, [`reactphp-input-stream`](https://packagist.org/packages/joopschilder/reactphp-input-stream). 

## Usage

### Basic
The code below can be found in `examples/simple_queue_consumer.php`.

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

// The builder allows you to define an exchange or a custom connection.
// By default, an AMQPStreamConnection is used (guest:guest@localhost:5672).
$input = NonBlockingAMQPInputBuilder::create($queue)->build();

// Create the EventLoop and add the AMQP consumer input to it:
$loop = Factory::create();
$stream = new ReadableNonBlockingInputStream($input, $loop);

// By default, a message needs to be acknowledged.
$stream->on('data', fn(Message $message) => print('m'));
$stream->on('data', fn(Message $message) => $message->ack());

// Add a timer for demonstration purposes...
$loop->addPeriodicTimer(0.2, fn() => print('.'));

// Run it.
$loop->run();
```

### Advanced
The code below can be found in `examples/advanced_queue_consumer.php`.

```php
<?php

use JoopSchilder\React\Stream\AMQP\Message;
use JoopSchilder\React\Stream\AMQP\NonBlockingAMQPInputBuilder;
use JoopSchilder\React\Stream\AMQP\ValueObject\ConsumeArguments;
use JoopSchilder\React\Stream\AMQP\ValueObject\ConsumerTag;
use JoopSchilder\React\Stream\AMQP\ValueObject\Exchange;
use JoopSchilder\React\Stream\AMQP\ValueObject\Queue;
use JoopSchilder\React\Stream\NonBlockingInput\ReadableNonBlockingInputStream;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use React\EventLoop\Factory;

require_once __DIR__ . '/path/to/autoload.php';

$queue = Queue::create('my_queue')
	->setDurable(true)
	->setAutoDelete(false)
	->setPassive(false)
	->setNoWait(false)
	->setArguments([]);

$consumerTag = ConsumerTag::format('worker[pid=%d]', getmypid());

$exchange = Exchange::create('my_exchange', AMQPExchangeType::FANOUT)
	->setDurable(true)
	->setAutoDelete(false)
	->setPassive(false)
	->setNoWait(false);

$arguments = ConsumeArguments::create()
	->setArguments([])
	->setNoAck(true)
	->setExclusive(false)
	->setNowait(false)
	->setNoLocal(false);

$input = NonBlockingAMQPInputBuilder::create($queue)
	->setConsumerTag($consumerTag)
	->setExchange($exchange)
	->setConsumeArguments($arguments)
	->build();

$loop = Factory::create();
$stream = new ReadableNonBlockingInputStream($input, $loop);
$stream->on('data', fn(Message $message) => print('m'));
$loop->addPeriodicTimer(0.2, fn() => print('.'));
$loop->run();
```
