<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms\forms;


use pocketmine\Player;

abstract class Element implements \JsonSerializable{
	/** @var string */
	protected $text;

	/**
	 * Element constructor.
	 * @param string $text
	 */
	public function __construct(string $text){
		$this->text = $text;
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
	final public function jsonSerialize() : array{
		$array = ["text" => $this->getText()];
		if($this->getType() !== null){
			$array["type"] = $this->getType();
		}
		return $array + $this->serializeElementData();
	}

	/**
	 * @return array
	 */
	abstract public function serializeElementData() : array;

	/**
	 * @param Player $player
	 * @param mixed  $value
	 */
	public function handle(Player $player, $value) : void{
	}
}