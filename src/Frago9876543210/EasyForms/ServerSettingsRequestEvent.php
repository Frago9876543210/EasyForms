<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms;


use Frago9876543210\EasyForms\forms\ServerSettingsForm;
use pocketmine\event\player\PlayerEvent;
use pocketmine\Player;

class ServerSettingsRequestEvent extends PlayerEvent{
	/** @var ServerSettingsForm|null */
	private $form;

	/**
	 * @param Player $player
	 */
	public function __construct(Player $player){
		$this->player = $player;
	}

	/**
	 * @return ServerSettingsForm|null
	 */
	public function getForm() : ?ServerSettingsForm{
		return $this->form;
	}

	/**
	 * @param ServerSettingsForm|null $form
	 */
	public function setForm(?ServerSettingsForm $form) : void{
		$this->form = $form;
	}
}