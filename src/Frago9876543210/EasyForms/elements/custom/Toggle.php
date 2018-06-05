<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms\elements\custom;


use Frago9876543210\EasyForms\abstracts\Element;

class Toggle extends Element{
	/** @var bool */
	private $default;

	public function __construct(string $text, bool $default = false){
		parent::__construct($text);
		$this->default = $default;
	}

	public function getType() : ?string{
		return "toggle";
	}

	public function serializeElementData() : array{
		return [
			"default" => $this->default
		];
	}
}