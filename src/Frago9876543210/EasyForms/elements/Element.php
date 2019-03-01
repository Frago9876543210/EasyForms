<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms\elements;


use pocketmine\form\FormValidationException;

abstract class Element implements \JsonSerializable{
	/** @var string */
	protected $text;
	/** @var null|mixed */
	protected $value;

	/**
	 * Element constructor.
	 * @param string $text
	 */
	public function __construct(string $text){
		$this->text = $text;
	}

	/**
	 * @return int|null
	 */
	public function getValue(){
		return $this->value;
	}

	/**
	 * @param null|int $value
	 */
	public function setValue($value){
		$this->value = $value;
	}

	/**
	 * @return array
	 */
	final public function jsonSerialize() : array{
		$array = ["text" => $this->getText()];
		if($this->getType() !== null){
			$array["type"] = $this->getType();
		}
		return $array + $this->serializeElementData();
	}

	/**
	 * @return string
	 */
	public function getText() : string{
		return $this->text;
	}

	/**
	 * @return null|string
	 */
	abstract public function getType() : ?string;

	/**
	 * @return array
	 */
	abstract public function serializeElementData() : array;

	/**
	 * @param $value
	 */
	public function validate($value) : void{
		if(!is_int($value)){
			throw new FormValidationException("Expected int, got " . gettype($value));
		}
	}
}