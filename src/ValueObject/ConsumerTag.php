<?php

namespace JoopSchilder\React\Stream\AMQP\ValueObject;

final class ConsumerTag
{
	private string $tag;


	public function __construct(string $tag = '')
	{
		$this->tag = $tag;
	}


	public function isDefault(): bool
	{
		return strlen($this->tag) === 0;
	}


	public function getTag(): string
	{
		return $this->tag;
	}

}
