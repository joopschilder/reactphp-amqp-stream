<?php

use JoopSchilder\React\Stream\AMQP\Message;
use JoopSchilder\React\Stream\AMQP\NonBlockingAMQPConsumer;
use JoopSchilder\React\Stream\AMQP\ValueObject\Queue;
use JoopSchilder\React\Stream\NonBlockingInput\ReadableNonBlockingInputStream;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use React\EventLoop\Factory;

require_once __DIR__ . '/../vendor/autoload.php';

$connection = new AMQPStreamConnection('localhost', '5672', 'guest', 'guest');
$consumer = new NonBlockingAMQPConsumer($connection, new Queue('my_command_queue'));

$loop = Factory::create();
$stream = new ReadableNonBlockingInputStream($consumer, $loop);
$stream->on('data', fn() => print('m'));
$stream->on('data', fn(Message $message) => $message->acknowledge());
$loop->addPeriodicTimer(0.2, fn() => print('.'));
$loop->run();
