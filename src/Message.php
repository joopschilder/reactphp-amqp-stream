<?php

namespace JoopSchilder\React\Stream\AMQP;

use JoopSchilder\React\Stream\AMQP\ValueObject\ConsumerTag;
use PhpAmqpLib\Message\AMQPMessage;

final class Message
{
	protected AMQPMessage $AMQPMessage;

	protected ConsumerTag $tag;

	protected bool $isAcknowledged = false;


	public final function __construct(AMQPMessage $AMQPMessage)
	{
		$this->AMQPMessage = $AMQPMessage;
		$this->tag = new ConsumerTag($this->AMQPMessage->delivery_info['consumer_tag']);
	}


	public final function getAMQPMessage(): AMQPMessage
	{
		return $this->AMQPMessage;
	}


	public final function getTag(): ConsumerTag
	{
		return $this->tag;
	}


	public final function acknowledge(): void
	{
		if ($this->isAcknowledged) {
			return;
		}

		$info = &$this->AMQPMessage->delivery_info;
		$info['channel']->basic_ack($info['delivery_tag']);
		$this->isAcknowledged = true;
	}

}
