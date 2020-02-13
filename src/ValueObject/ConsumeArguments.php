<?php

namespace JoopSchilder\React\Stream\AMQP\ValueObject;

final class ConsumeArguments
{
	private bool $noLocal = false;

	private bool $noAck = false;

	private bool $exclusive = false;

	private bool $nowait = false;

	private ?int $ticket = null;

	private array $arguments = [];


	public function __construct()
	{
	}


	public static function create(): self
	{
		return new self();
	}


	public function isNoLocal(): bool
	{
		return $this->noLocal;
	}


	public function setNoLocal(bool $noLocal): self
	{
		$this->noLocal = $noLocal;

		return $this;
	}


	public function isNoAck(): bool
	{
		return $this->noAck;
	}


	public function setNoAck(bool $noAck): self
	{
		$this->noAck = $noAck;

		return $this;
	}


	public function isExclusive(): bool
	{
		return $this->exclusive;
	}


	public function setExclusive(bool $exclusive): self
	{
		$this->exclusive = $exclusive;

		return $this;
	}


	public function isNowait(): bool
	{
		return $this->nowait;
	}


	public function setNowait(bool $nowait): self
	{
		$this->nowait = $nowait;

		return $this;
	}


	public function getTicket(): ?int
	{
		return $this->ticket;
	}


	public function setTicket(int $ticket): self
	{
		$this->ticket = $ticket;

		return $this;
	}


	public function getArguments(): array
	{
		return $this->arguments;
	}


	public function setArguments(array $arguments): self
	{
		$this->arguments = $arguments;

		return $this;
	}

}
