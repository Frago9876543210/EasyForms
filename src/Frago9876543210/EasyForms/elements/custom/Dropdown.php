<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms\elements\custom;


use Frago9876543210\EasyForms\elements\Element;

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