<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms\elements\menu;


use Frago9876543210\EasyForms\elements\Element;

class Button extends Element{
	public const TYPE_URL = "url";
	public const TYPE_PATH = "path";

	/** @var null|string */
	protected $image;
	/** @var string */
	protected $type;

	/**
	 * Button constructor.
	 * @param string      $text
	 * @param null|string $image
	 * @param string      $type
	 */
	public function __construct(string $text, ?string $image = null, string $type = self::TYPE_URL){
		parent::__construct($text);
		$this->image = $image;
		$this->type = $type;
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
				"type" => $this->type,
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