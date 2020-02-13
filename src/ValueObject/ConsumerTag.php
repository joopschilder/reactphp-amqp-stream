<?php

namespace JoopSchilder\React\Stream\AMQP\ValueObject;

final class ConsumerTag
{
	private string $tag;


	public function __construct(string $tag = '')
	{
		$this->tag = $tag;
	}


	public static function format(string $format, ...$args): self
	{
		return new self(sprintf($format, ...$args));
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
