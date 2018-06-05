<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms;

use Ds\Map;
use Frago9876543210\EasyForms\abstracts\Form;
use Frago9876543210\EasyForms\forms\CustomForm;
use Frago9876543210\EasyForms\forms\MenuForm;
use Frago9876543210\EasyForms\forms\ModalForm;
use Frago9876543210\EasyForms\utils\PlayerFormData;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class EasyForms extends PluginBase implements Listener{
	/** @var Map */
	private static $forms;

	public function onEnable() : void{
		self::$forms = new Map;
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onJoin(PlayerJoinEvent $e) : void{
		self::$forms->put($e->getPlayer(), new PlayerFormData);
	}

	private static function getFormData(Player $player) : ?PlayerFormData{
		return self::$forms->get($player, null);
	}

	public static function sendForm(Player $player, Form $form) : void{
		$formData = self::getFormData($player);
		$pk = new ModalFormRequestPacket;
		$pk->formId = $formData->formId++;
		$pk->formData = json_encode($form);
		$player->dataPacket($pk);
		$formData->windows[$pk->formId] = $form;
	}

	public function onModalFormResponse(DataPacketReceiveEvent $e) : void{
		$pk = $e->getPacket();
		if($pk instanceof ModalFormResponsePacket){
			try{
				$formData = self::getFormData($player = $e->getPlayer());
				if(isset($formData->windows[$pk->formId])){
					$form = $formData->windows[$pk->formId];
					if(($response = json_decode($pk->formData, true)) === null){
						$form->onClose($player);
					}else{
						if($form instanceof MenuForm && is_int($response)){
							$form->onSubmit($player, $response);
						}elseif($form instanceof ModalForm && is_bool($response)){
							$form->onSubmit($player, $response);
						}elseif($form instanceof CustomForm && is_array($response)){
							$form->onSubmit($player, $response);
						}else{
							$this->getLogger()->debug("Received wrong form data from {$player->getName()}");
						}
					}
					unset($formData->windows[$pk->formId]);
				}
			}catch(\Throwable $e){
				$this->getLogger()->logException($e);
			}
		}
	}
}