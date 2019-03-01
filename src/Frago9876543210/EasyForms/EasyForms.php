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

	private function sendSetting(Player $player, Form $form) : void{
		$reflection = new \ReflectionObject($player);

		$idProperty = $reflection->getProperty("formIdCounter");
		$idProperty->setAccessible(true);
		$idPropertyValue = $idProperty->getValue($player);

		/** @noinspection PhpUnusedLocalVariableInspection */
		$id = $idPropertyValue++;

		$idProperty->setValue($player, $id);

		$pk = new ServerSettingsResponsePacket();
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
			$ev = new ServerSettingsRequestEvent($player = $e->getPlayer());
			($server = $this->getServer())->getApiVersion(){0} === "3" ? $server->getPluginManager()->callEvent($ev) : $ev->call();
			if(($form = $ev->getForm()) !== null){
				$this->sendSetting($player, $form);
			}
		}
	}
}