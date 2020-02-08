<?php

namespace JoopSchilder\React\Stream\AMQP\ValueObject;

final class Queue
{
	private string $name;

	private bool $isPassive = false;

	private bool $isDurable = true;

	private bool $isExclusive = false;

	private bool $isAutoDelete = false;

	private bool $noWait = false;

	private array $arguments = [];

	private ?int $ticket = null;


	public function __construct(string $name)
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
		return $this->isPassive;
	}


	public function setIsPassive(bool $isPassive): self
	{
		$this->isPassive = $isPassive;

		return $this;
	}


	public function isDurable(): bool
	{
		return $this->isDurable;
	}


	public function setIsDurable(bool $isDurable): self
	{
		$this->isDurable = $isDurable;

		return $this;
	}


	public function isExclusive(): bool
	{
		return $this->isExclusive;
	}


	public function setIsExclusive(bool $isExclusive): self
	{
		$this->isExclusive = $isExclusive;

		return $this;
	}


	public function isAutoDelete(): bool
	{
		return $this->isAutoDelete;
	}


	public function setIsAutoDelete(bool $isAutoDelete): self
	{
		$this->isAutoDelete = $isAutoDelete;

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
