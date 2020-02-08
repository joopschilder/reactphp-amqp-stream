<?php

namespace JoopSchilder\React\Stream\AMQP;

use PhpAmqpLib\Message\AMQPMessage;

final class Message
{
	protected AMQPMessage $AMQPMessage;

	protected bool $isAcknowledged = false;


	public final function __construct(AMQPMessage $AMQPMessage)
	{
		$this->AMQPMessage = $AMQPMessage;
	}


	public final function getAMQPMessage(): AMQPMessage
	{
		return $this->AMQPMessage;
	}


	public final function acknowledge(): void
	{
		if ($this->isAcknowledged) {
			return;
		}

		$tag = $this->AMQPMessage->delivery_info['delivery_tag'];
		$channel = $this->AMQPMessage->delivery_info['channel'];
		$channel->basic_ack($tag);
		$this->isAcknowledged = true;
	}

}
