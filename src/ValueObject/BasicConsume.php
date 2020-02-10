<?php

namespace JoopSchilder\React\Stream\AMQP\ValueObject;

final class BasicConsume
{
	private Queue $queue;

	private ConsumerTag $tag;

	private bool $noLocal = false;

	private bool $noAck = false;

	private bool $exclusive = false;

	private bool $nowait = false;

	/** @var callable|null */
	private $callback = null;

	private ?int $ticket = null;

	private array $arguments = [];


	public function __construct(?Queue $queue = null, ?ConsumerTag $tag = null)
	{
		$this->queue = $queue ?? new Queue();
		$this->tag = $tag ?? new ConsumerTag();
	}


	public function getQueue(): ?Queue
	{
		return $this->queue;
	}


	public function getTag(): ?ConsumerTag
	{
		return $this->tag;
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


	public function getCallback(): ?callable
	{
		return $this->callback;
	}


	public function setCallback(callable $callback): self
	{
		$this->callback = $callback;

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
