<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms\elements;


class Toggle extends Element{
	/** @var bool */
	protected $default;

	public function __construct(string $text, bool $default = false){
		parent::__construct($text);
		$this->default = $default;
	}

	/**
	 * @return bool
	 */
	public function hasChanged() : bool{
		return $this->default !== $this->value;
	}

	/**
	 * @return bool
	 */
	public function getDefault() : bool{
		return $this->default;
	}

	/**
	 * @return null|string
	 */
	public function getType() : ?string{
		return "toggle";
	}

	/**
	 * @return array
	 */
	public function serializeElementData() : array{
		return [
			"default" => $this->default
		];
	}
}