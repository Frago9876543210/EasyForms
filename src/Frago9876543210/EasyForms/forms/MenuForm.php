<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms\forms;


use Frago9876543210\EasyForms\elements\menu\Button;
use pocketmine\Player;

class MenuForm extends Form{
	/** @var Button[] */
	protected $buttons;
	/** @var string */
	protected $text;

	/**
	 * MenuForm constructor.
	 * @param string      $title
	 * @param string $text
	 * @param Button[]    $buttons
	 */
	public function __construct(string $title, string $text, array $buttons = []){
		parent::__construct($title);
		$this->text = $text;
		$this->buttons = $buttons;
	}

	/**
	 * @return string
	 */
	public function getType() : string{
		return self::TYPE_MENU;
	}

	/**
	 * @param Player $player
	 * @param mixed  $response
	 */
	public function onSubmit(Player $player, $response) : void{
		if(!(is_int($response) || isset($this->buttons[$response]))){
			return;
		}
	}

	/**
	 * @return array
	 */
	protected function serializeFormData() : array{
		return [
			"buttons" => $this->buttons,
			"content" => $this->text
		];
	}
}