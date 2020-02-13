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

require_once __DIR__ . '/../vendor/autoload.php';

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
