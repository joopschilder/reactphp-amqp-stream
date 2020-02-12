<?php

namespace JoopSchilder\React\Stream\AMQP;

use JoopSchilder\React\Stream\AMQP\ValueObject\BasicConsume;
use JoopSchilder\React\Stream\AMQP\ValueObject\ConsumerTag;
use JoopSchilder\React\Stream\AMQP\ValueObject\Exchange;
use JoopSchilder\React\Stream\AMQP\ValueObject\Queue;
use JoopSchilder\React\Stream\NonBlockingInput\NonBlockingInputInterface;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Message\AMQPMessage;

final class NonBlockingAMQPInput implements NonBlockingInputInterface
{
	protected AbstractConnection $connection;

	protected Channel $channel;

	protected Queue $queue;

	protected ?Exchange $exchange;

	protected ConsumerTag $tag;

	protected BasicConsume $consume;

	private bool $closed = true;

	/** @var Message[] */
	protected array $buffer = [];


	public function __construct(
		AbstractConnection $connection,
		Queue $queue,
		?Exchange $exchange = null,
		?ConsumerTag $tag = null
	)
	{
		$this->connection = $connection;
		$this->queue = $queue;
		$this->exchange = $exchange;
		$this->tag = $tag ?? new ConsumerTag();
		$this->channel = new Channel($this->connection->channel());

		$this->setupRouting();
		$this->setupConsume();
		$this->open();
	}


	public function select(): ?Message
	{
		if (!$this->channel->isConsuming()) {
			return null;
		}
		$this->channel->waitNonBlocking();

		return array_shift($this->buffer);
	}


	public function open(): void
	{
		if (!$this->closed) {
			return;
		}
		$this->channel->basicConsume($this->consume);
		$this->closed = false;
	}


	public function close(): void
	{
		if ($this->closed) {
			return;
		}
		$this->channel->basicCancel($this->tag);
		$this->closed = true;
	}


	private function setupConsume(): void
	{
		$this->consume = BasicConsume::create($this->queue)
			->setTag($this->tag)
			->setCallback(function (AMQPMessage $message) {
				$message = new Message($message);
				$this->tag = $message->getTag();
				$this->buffer[] = $message;
			});
	}


	private function setupRouting(): void
	{
		$this->channel->declareQueue($this->queue);
		if (!is_null($this->exchange)) {
			$this->channel->declareExchange($this->exchange);
			$this->channel->bindQueue($this->queue, $this->exchange);
		}
	}


	public function __destruct()
	{
		$this->channel->close();
		$this->connection->close();
	}

}
