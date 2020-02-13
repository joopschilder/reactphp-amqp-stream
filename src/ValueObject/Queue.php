<?php

namespace JoopSchilder\React\Stream\AMQP\ValueObject;

final class Queue
{
	private string $name;

	private bool $passive = false;

	private bool $durable = true;

	private bool $exclusive = false;

	private bool $autoDelete = false;

	private bool $noWait = false;

	private array $arguments = [];

	private ?int $ticket = null;


	public function __construct(string $name = '')
	{
		$this->name = $name;
	}


	public static function create(string $name): self
	{
		return new self($name);
	}


	public function getName(): string
	{
		return $this->name;
	}


	public function isPassive(): bool
	{
		return $this->passive;
	}


	public function setPassive(bool $passive): self
	{
		$this->passive = $passive;

		return $this;
	}


	public function isDurable(): bool
	{
		return $this->durable;
	}


	public function setDurable(bool $durable): self
	{
		$this->durable = $durable;

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


	public function isAutoDelete(): bool
	{
		return $this->autoDelete;
	}


	public function setAutoDelete(bool $autoDelete): self
	{
		$this->autoDelete = $autoDelete;

		return $this;
	}


	public function isNoWait(): bool
	{
		return $this->noWait;
	}


	public function setNoWait(bool $noWait): self
	{
		$this->noWait = $noWait;

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


	public function getTicket(): ?int
	{
		return $this->ticket;
	}


	public function setTicket(int $ticket): self
	{
		$this->ticket = $ticket;

		return $this;
	}

}
