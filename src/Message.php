<?php

namespace JoopSchilder\React\Stream\AMQP;

use JoopSchilder\React\Stream\AMQP\ValueObject\ConsumerTag;
use PhpAmqpLib\Message\AMQPMessage;

final class Message
{
	protected AMQPMessage $AMQPMessage;

	protected array $info;

	protected ConsumerTag $tag;

	protected bool $ackedOrNacked = false;


	public final function __construct(AMQPMessage $AMQPMessage, bool $isNoAck = false)
	{
		$this->AMQPMessage = $AMQPMessage;
		$this->ackedOrNacked = $isNoAck;

		$this->info = &$this->AMQPMessage->delivery_info;
		$this->tag = new ConsumerTag($this->info['consumer_tag']);
	}


	public final function getAMQPMessage(): AMQPMessage
	{
		return $this->AMQPMessage;
	}


	public final function getTag(): ConsumerTag
	{
		return $this->tag;
	}


	public final function ack(): void
	{
		if ($this->ackedOrNacked) {
			return;
		}
		$this->info['channel']->basic_ack($this->info['delivery_tag']);
		$this->ackedOrNacked = true;
	}


	public function nack(): void
	{
		if ($this->ackedOrNacked) {
			return;
		}
		$this->info['channel']->basic_nack($this->info['delivery_tag']);
		$this->ackedOrNacked = true;
	}

}
