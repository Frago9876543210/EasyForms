<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms;


use Frago9876543210\EasyForms\forms\Form;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ServerSettingsRequestPacket;
use pocketmine\network\mcpe\protocol\ServerSettingsResponsePacket;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class EasyForms extends PluginBase implements Listener{
	public const SERVER_SETTINGS_ID = 4294967295;

	public function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	/**
	 * @deprecated
	 * @param Player    $player
	 * @param Form      $form
	 * @param bool|null $settings
	 */
	public static function sendForm(Player $player, Form $form, ?bool $settings = null) : void{
		throw new \RuntimeException(__METHOD__ . " is deprecated and will be removed in 1.0.6");
	}

	private function sendSetting(Player $player, Form $form) : void{
		$reflection = new \ReflectionObject($player);

		$idProperty = $reflection->getProperty("formIdCounter");
		$idProperty->setAccessible(true);
		$idPropertyValue = $idProperty->getValue($player);

		/** @noinspection PhpUnusedLocalVariableInspection */
		$id = $idPropertyValue++;

		$idProperty->setValue($player, $id);

		$pk = new ServerSettingsResponsePacket;
		$pk->formId = $id;
		$pk->formData = json_encode($form);
		if($player->sendDataPacket($pk)){
			$formsProperty = $reflection->getProperty("forms");
			$formsProperty->setAccessible(true);

			$formsValue = $formsProperty->getValue($player);
			$formsValue[$id] = $form;

			$formsProperty->setValue($player, $formsValue);
		}
	}

	public function onDataPacketReceive(DataPacketReceiveEvent $e) : void{
		$pk = $e->getPacket();
		if($pk instanceof ServerSettingsRequestPacket){
			$this->getServer()->getPluginManager()->callEvent($ev = new ServerSettingsRequestEvent($this, $player = $e->getPlayer()));
			if(($form = $ev->getForm()) !== null){
				$this->sendSetting($player, $form);
			}
		}
	}
}
