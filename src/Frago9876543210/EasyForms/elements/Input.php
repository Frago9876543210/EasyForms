<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms\elements;


class Input extends Element{
	/** @var string */
	protected $placeholder;
	/** @var string */
	protected $default;

	/**
	 * Input constructor.
	 * @param string $text
	 * @param string $placeholder
	 * @param string $default
	 */
	public function __construct(string $text, string $placeholder, string $default = ""){
		parent::__construct($text);
		$this->placeholder = $placeholder;
		$this->default = $default;
	}

	/**
	 * @return string
	 */
	public function getPlaceholder() : string{
		return $this->placeholder;
	}

	/**
	 * @return string
	 */
	public function getDefault() : string{
		return $this->default;
	}

	/**
	 * @return null|string
	 */
	public function getType() : ?string{
		return "input";
	}

	/**
	 * @return array
	 */
	public function serializeElementData() : array{
		return [
			"placeholder" => $this->placeholder,
			"default" => $this->default
		];
	}
}