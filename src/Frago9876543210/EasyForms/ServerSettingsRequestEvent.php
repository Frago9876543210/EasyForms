<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms;

use Frago9876543210\EasyForms\forms\CustomForm;
use pocketmine\{event\Event, player\Player};

class ServerSettingsRequestEvent extends Event{
	/** @var Player */
	private $player;
	/** @var CustomForm|null */
	private $form;

	/**
	 * @param Player $player
	 */
	public function __construct(Player $player){
		$this->player = $player;
	}

	/**
	 * @return Player
	 */
	public function getPlayer() : Player{
		return $this->player;
	}

	/**
	 * @return CustomForm|null
	 */
	public function getForm() : ?CustomForm{
		return $this->form;
	}

	/**
	 * @param CustomForm|null $form
	 */
	public function setForm(?CustomForm $form) : void{
		$this->form = $form;
	}
}