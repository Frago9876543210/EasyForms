<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms\elements;


class Dropdown extends Element{
	/** @var string[] */
	protected $options;
	/** @var int */
	protected $default;

	/**
	 * Dropdown constructor.
	 * @param string   $text
	 * @param string[] $options
	 * @param int      $default
	 */
	public function __construct(string $text, array $options, int $default = 0){
		parent::__construct($text);
		$this->options = $options;
		$this->default = $default;
	}

	/**
	 * @return array
	 */
	public function getOptions() : array{
		return $this->options;
	}

	/**
	 * @return string
	 */
	public function getSelectedOption() : ?string{
		return $this->options[$this->value] ?? null;
	}

	/**
	 * @return int
	 */
	public function getDefault() : int{
		return $this->default;
	}

	/**
	 * @return null|string
	 */
	public function getType() : ?string{
		return "dropdown";
	}

	/**
	 * @return array
	 */
	public function serializeElementData() : array{
		return [
			"options" => $this->options,
			"default" => $this->default
		];
	}
}