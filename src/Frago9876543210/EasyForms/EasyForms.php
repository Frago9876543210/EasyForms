<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms;


use Frago9876543210\EasyForms\forms\CustomForm;
use pocketmine\event\{Listener, server\DataPacketReceiveEvent};
use pocketmine\network\mcpe\protocol\{ServerSettingsRequestPacket, ServerSettingsResponsePacket};
use pocketmine\{Player, plugin\PluginBase};
use function json_encode;
use const pocketmine\IS_DEVELOPMENT_BUILD;

class EasyForms extends PluginBase implements Listener{
	public const SERVER_SETTINGS_ID = 4294967295;

	public function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	private function sendSetting(Player $player, CustomForm $form) : void{
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
		$server = $this->getServer();
		$release = !IS_DEVELOPMENT_BUILD;
		$pk = $e->getPacket();
		if($pk instanceof ServerSettingsRequestPacket){
			$ev = new ServerSettingsRequestEvent($player = $release ? $e->getPlayer() : $e->getOrigin()->getPlayer());
			$release ? $server->getPluginManager()->callEvent($ev) : $ev->call();
			if(($form = $ev->getForm()) !== null){
				$this->sendSetting($player, $form);
			}
		}
	}
}
