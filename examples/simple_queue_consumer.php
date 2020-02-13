<?php

use JoopSchilder\React\Stream\AMQP\Message;
use JoopSchilder\React\Stream\AMQP\NonBlockingAMQPInputBuilder;
use JoopSchilder\React\Stream\AMQP\ValueObject\Queue;
use JoopSchilder\React\Stream\NonBlockingInput\ReadableNonBlockingInputStream;
use React\EventLoop\Factory;

require_once __DIR__ . '/../vendor/autoload.php';

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
