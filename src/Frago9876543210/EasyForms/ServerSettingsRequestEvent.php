<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms;


use pocketmine\event\plugin\PluginEvent;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class ServerSettingsRequestEvent extends PluginEvent{
	/** @var Player */
	private $player;

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
}