<?php

namespace JoopSchilder\React\Stream\AMQP;

use JoopSchilder\React\Stream\AMQP\ValueObject\BasicConsume;
use JoopSchilder\React\Stream\AMQP\ValueObject\ConsumerTag;
use JoopSchilder\React\Stream\AMQP\ValueObject\Exchange;
use JoopSchilder\React\Stream\AMQP\ValueObject\Queue;
use PhpAmqpLib\Channel\AMQPChannel;

final class Channel
{

	private AMQPChannel $channel;


	public function __construct(AMQPChannel $channel)
	{
		$this->channel = $channel;
	}


	public function isConsuming(): bool
	{
		return $this->channel->is_consuming();
	}


	public function close(): void
	{
		$this->channel->close();
	}


	public function waitNonBlocking(): void
	{
		$this->channel->wait(null, true);
	}


	public function basicCancel(ConsumerTag $tag): void
	{
		$this->channel->basic_cancel($tag->getTag());
	}


	public function declareQueue(Queue $queue): void
	{
		$this->channel->queue_declare(
			$queue->getName(),
			$queue->isPassive(),
			$queue->isDurable(),
			$queue->isExclusive(),
			$queue->isAutoDelete(),
			$queue->isNoWait(),
			$queue->getArguments(),
			$queue->getTicket()
		);
	}


	public function declareExchange(Exchange $exchange): void
	{
		$this->channel->exchange_declare(
			$exchange->getName(),
			$exchange->getType(),
			$exchange->isPassive(),
			$exchange->isDurable(),
			$exchange->isAutoDelete(),
			$exchange->isInternal(),
			$exchange->isNoWait(),
			$exchange->getArguments(),
			$exchange->getTicket()
		);
	}


	public function bindQueue(Queue $queue, Exchange $exchange): void
	{
		$this->channel->queue_bind(
			$queue->getName(),
			$exchange->getName()
		);
	}


	public function basicConsume(BasicConsume $consume): void
	{
		$this->channel->basic_consume(
			$consume->getQueue()->getName(),
			$consume->getTag()->getTag(),
			$consume->isNoLocal(),
			$consume->isNoAck(),
			$consume->isExclusive(),
			$consume->isNowait(),
			$consume->getCallback(),
			$consume->getTicket(),
			$consume->getArguments()
		);
	}

}
