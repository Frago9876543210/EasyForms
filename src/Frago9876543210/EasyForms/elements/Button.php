<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms\elements;

class Button extends Element{
	/** @var Image|null */
	private $image;
	/** @var string */
	private $type;

	/**
	 * @param string     $text
	 * @param Image|null $image
	 */
	public function __construct(string $text, ?Image $image = null){
		parent::__construct($text);
		$this->image = $image;
	}

	/**
	 * @return string|null
	 */
	public function getType() : ?string{
		return null;
	}

	/**
	 * @return bool
	 */
	public function hasImage() : bool{
		return $this->image !== null;
	}

	/**
	 * @return array
	 */
	public function serializeElementData() : array{
		$data = ["text" => $this->text];
		if($this->hasImage()){
			$data["image"] = $this->image;
		}
		return $data;
	}
}