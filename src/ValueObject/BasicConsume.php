<?php

namespace JoopSchilder\React\Stream\AMQP\ValueObject;

final class BasicConsume
{
	private Queue $queue;

	private ConsumerTag $tag;

	private ConsumeArguments $arguments;

	/** @var callable|null */
	private $callback = null;


	public function __construct(Queue $queue, ?ConsumerTag $tag = null)
	{
		$this->queue = $queue;
		$this->tag = $tag ?? new ConsumerTag();
	}


	public static function create(Queue $queue): self
	{
		return new self($queue);
	}


	public function setArguments(ConsumeArguments $arguments): void
	{
		$this->arguments = $arguments;
	}


	public function getQueue(): ?Queue
	{
		return $this->queue;
	}


	public function setTag(?ConsumerTag $tag): self
	{
		$this->tag = $tag;

		return $this;
	}


	public function getTag(): ?ConsumerTag
	{
		return $this->tag;
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


	public function getArguments(): ConsumeArguments
	{
		$this->arguments ??= new ConsumeArguments();

		return $this->arguments;
	}

}
