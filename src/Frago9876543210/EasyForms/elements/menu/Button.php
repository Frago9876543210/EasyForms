<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms\elements\menu;


use Frago9876543210\EasyForms\elements\Element;

class Button extends Element{
	/** @var null|string */
	protected $image;

	/**
	 * Button constructor.
	 * @param string      $text
	 * @param null|string $image
	 */
	public function __construct(string $text, ?string $image = null){
		parent::__construct($text);
		$this->image = $image;
	}

	/**
	 * @return null|string
	 */
	public function getType() : ?string{
		return null;
	}

	/**
	 * @return array
	 */
	public function serializeElementData() : array{
		$data = ["text" => $this->text];
		if($this->hasImage()){
			$data["image"] = [
				"type" => "url",
				"data" => $this->image
			];
		}
		return $data;
	}

	/**
	 * @return bool
	 */
	public function hasImage() : bool{
		return $this->image !== null;
	}
}