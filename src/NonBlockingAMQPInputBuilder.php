<?php

namespace JoopSchilder\React\Stream\AMQP;

use JoopSchilder\React\Stream\AMQP\ValueObject\ConsumeArguments;
use JoopSchilder\React\Stream\AMQP\ValueObject\ConsumerTag;
use JoopSchilder\React\Stream\AMQP\ValueObject\Exchange;
use JoopSchilder\React\Stream\AMQP\ValueObject\Queue;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;

final class NonBlockingAMQPInputBuilder
{

	private Queue $queue;

	private ?AbstractConnection $connection = null;

	private ?Exchange $exchange = null;

	private ?ConsumerTag $consumerTag = null;

	private ?ConsumeArguments $consumeArguments = null;


	private function __construct(Queue $queue)
	{
		$this->queue = $queue;
	}


	public static function create(Queue $queue): self
	{
		return new self($queue);
	}


	public function setConnection(AbstractConnection $connection): self
	{
		$this->connection = $connection;

		return $this;
	}


	public function setExchange(Exchange $exchange): self
	{
		$this->exchange = $exchange;

		return $this;
	}


	public function setConsumerTag(ConsumerTag $consumerTag): self
	{
		$this->consumerTag = $consumerTag;

		return $this;
	}


	public function setConsumeArguments(?ConsumeArguments $consumeArguments): self
	{
		$this->consumeArguments = $consumeArguments;

		return $this;
	}


	public function build(): NonBlockingAMQPInput
	{
		return new NonBlockingAMQPInput(
			$this->connection ?? new AMQPStreamConnection('localhost', '5672', 'guest', 'guest'),
			$this->queue,
			$this->exchange,
			$this->consumerTag,
			$this->consumeArguments
		);
	}

}
