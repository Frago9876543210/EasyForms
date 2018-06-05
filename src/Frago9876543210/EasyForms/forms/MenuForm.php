<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms\forms;


use Frago9876543210\EasyForms\abstracts\Form;
use Frago9876543210\EasyForms\elements\menu\Button;
use pocketmine\Player;

class MenuForm extends Form{
	/** @var Button[] */
	protected $buttons;
	/** @var string */
	protected $text;

	/**
	 * Menu constructor.
	 * @param string   $title
	 * @param string   $text
	 * @param Button[] $buttons
	 */
	public function __construct(string $title, string $text, array $buttons){
		parent::__construct($title);
		$this->text = $text;
		$this->buttons = $buttons;
	}

	public function getType() : string{
		return self::TYPE_MENU;
	}

	public function onSubmit(Player $player, $response) : void{
		if(isset($this->buttons[$response])){
			$this->buttons[$response]->handle($player, $response);
		}
	}

	protected function serializeFormData() : array{
		return [
			"content" => $this->text,
			"buttons" => $this->buttons
		];
	}
}