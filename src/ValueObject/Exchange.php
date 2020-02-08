<?php

namespace JoopSchilder\React\Stream\AMQP\ValueObject;

use InvalidArgumentException;
use PhpAmqpLib\Exchange\AMQPExchangeType;

final class Exchange
{
	private const SUPPORTED_TYPES = [
		AMQPExchangeType::DIRECT,
		AMQPExchangeType::FANOUT,
		AMQPExchangeType::HEADERS,
		AMQPExchangeType::TOPIC,
	];

	private string $name;

	private string $type = AMQPExchangeType::DIRECT;

	private bool $isPassive = false;

	private bool $isDurable = false;

	private bool $isAutoDelete = true;

	private bool $isInternal = false;

	private bool $noWait = false;

	private array $arguments = [];

	private ?int $ticket = null;


	public function __construct(string $name, string $type = AMQPExchangeType::DIRECT)
	{
		$this->name = $name;
		$this->type = $type;
	}


	public static function create(string $name, string $type = AMQPExchangeType::DIRECT): self
	{
		return new self($name, $type);
	}


	public function getName(): string
	{
		return $this->name;
	}


	public function isInternal(): bool
	{
		return $this->isInternal;
	}


	public function getType(): string
	{
		return $this->type;
	}


	public function setType(string $type): self
	{
		if (!in_array($type, self::SUPPORTED_TYPES)) {
			throw new InvalidArgumentException(sprintf('Unsupported type: \'%s\'', $type));
		}
		$this->type = $type;

		return $this;
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
