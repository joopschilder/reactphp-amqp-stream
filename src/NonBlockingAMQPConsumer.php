<?php

namespace JoopSchilder\React\Stream\AMQP;

use JoopSchilder\React\Stream\AMQP\ValueObject\ConsumerTag;
use JoopSchilder\React\Stream\AMQP\ValueObject\Exchange;
use JoopSchilder\React\Stream\AMQP\ValueObject\Queue;
use JoopSchilder\React\Stream\NonBlockingInput\NonBlockingInputInterface;
use PhpAmqpLib\Channel\AbstractChannel;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Message\AMQPMessage;

final class NonBlockingAMQPConsumer implements NonBlockingInputInterface
{
	protected AbstractConnection $connection;

	protected AbstractChannel $channel;

	protected Queue $queue;

	protected ConsumerTag $tag;

	protected ?Exchange $exchange;

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

		$this->channel = $this->connection->channel();
		$this->setupQueue();
		if (!is_null($this->exchange)) {
			$this->setupAndBindExchange();
		}
		$this->open();
	}


	public function select(): ?Message
	{
		if (!$this->channel->is_consuming()) {
			return null;
		}
		$this->channel->wait(null, true);

		return array_shift($this->buffer);
	}


	public function open(): void
	{
		if (!$this->closed) {
			return;
		}
		$this->channel->basic_consume(
			$this->queue->getName(),
			$this->tag->getTag(),
			false,
			false,
			false,
			false,
			fn(AMQPMessage $message) => $this->buffer[] = new Message($message)
		);
		$this->closed = false;
	}


	public function close(): void
	{
		if ($this->closed) {
			return;
		}
		$this->channel->basic_cancel($this->tag->getTag());
		$this->closed = true;
	}


	private function setupQueue(): void
	{
		$this->channel->queue_declare(
			$this->queue->getName(),
			$this->queue->isPassive(),
			$this->queue->isDurable(),
			$this->queue->isExclusive(),
			$this->queue->isAutoDelete(),
			$this->queue->isNoWait(),
			$this->queue->getArguments(),
			$this->queue->getTicket()
		);
	}


	private function setupAndBindExchange(): void
	{
		$this->channel->exchange_declare(
			$this->exchange->getName(),
			$this->exchange->getType(),
			$this->exchange->isPassive(),
			$this->exchange->isDurable(),
			$this->exchange->isAutoDelete(),
			$this->exchange->isInternal(),
			$this->exchange->isNoWait(),
			$this->exchange->getArguments(),
			$this->exchange->getTicket()
		);
		$this->channel->queue_bind(
			$this->queue->getName(),
			$this->exchange->getName()
		);
	}


	public function __destruct()
	{
		$this->channel->close();
		$this->connection->close();
	}

}
