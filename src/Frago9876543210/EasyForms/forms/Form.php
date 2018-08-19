<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms\forms;


use pocketmine\form\Form as PMForm;
use pocketmine\Player;

abstract class Form implements PMForm{
	public const TYPE_MODAL = "modal";
	public const TYPE_MENU = "form";
	public const TYPE_CUSTOM_FORM = "custom_form";

	/** @var string */
	protected $title;

	/**
	 * Form constructor.
	 * @param string $title
	 */
	public function __construct(string $title){
		$this->title = $title;
	}

	public function handleResponse(Player $player, $data) : ?PMForm{
		try{
			if($data === null){
				$this->onClose($player);
			}elseif(
				($this instanceof MenuForm && is_int($data)) ||
				($this instanceof ModalForm && is_bool($data)) ||
				($this instanceof CustomForm && is_array($data))
			){
				$this->onSubmit($player, $data);
			}
		}catch(\Throwable $e){
			$player->getServer()->getLogger()->logException($e);
		}
		return null; //TODO: next form
	}

	/**
	 * @param Player $player
	 */
	public function onClose(Player $player) : void{
	}

	/**
	 * @param Player $player
	 * @param mixed  $response
	 */
	public function onSubmit(Player $player, $response) : void{
	}

	/**
	 * @return array
	 */
	final public function jsonSerialize() : array{
		return array_merge([
			"title" => $this->getTitle(), "type" => $this->getType()
		], $this->serializeFormData());
	}

	/**
	 * @return string
	 */
	public function getTitle() : string{
		return $this->title;
	}

	/**
	 * @return string
	 */
	abstract public function getType() : string;

	/**
	 * @return array
	 */
	abstract protected function serializeFormData() : array;
}