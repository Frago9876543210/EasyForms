<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms;

use Frago9876543210\EasyForms\forms\CustomForm;
use pocketmine\{player\Player, plugin\PluginBase};
use pocketmine\event\{Listener, server\DataPacketReceiveEvent};
use pocketmine\network\mcpe\protocol\{ServerSettingsRequestPacket, ServerSettingsResponsePacket};
use ReflectionObject;
use function json_encode;

class EasyForms extends PluginBase implements Listener{

	public function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	private function sendSetting(Player $player, CustomForm $form) : void{
		$reflection = new ReflectionObject($player);

		$idProperty = $reflection->getProperty("formIdCounter"); //TODO: sync it with SOF3 PR
		$idProperty->setAccessible(true);
		$id = $idProperty->getValue($player);

		$idProperty->setValue($player, ++$id);
		$id--;

		$pk = new ServerSettingsResponsePacket();
		$pk->formId = $id;
		$pk->formData = json_encode($form);
		if($player->getNetworkSession()->sendDataPacket($pk)){
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
			($ev = new ServerSettingsRequestEvent($player = $e->getOrigin()->getPlayer()))->call();
			if(($form = $ev->getForm()) !== null){
				$this->sendSetting($player, $form);
			}
		}
	}
}
