<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms;


use Frago9876543210\EasyForms\forms\ServerSettingsForm;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class ServerSettingsRequestEvent extends PluginEvent{
	/** @var Player */
	private $player;
	/** @var ServerSettingsForm|null */
	private $form;

	/**
	 * ServerSettingsRequestPacket constructor.
	 * @param Plugin $plugin
	 * @param Player $player
	 */
	public function __construct(Plugin $plugin, Player $player){
		parent::__construct($plugin);
		$this->player = $player;
	}

	/**
	 * @return Player
	 */
	public function getPlayer() : Player{
		return $this->player;
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