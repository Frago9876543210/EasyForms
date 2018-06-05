<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms;

use Ds\Map;
use Frago9876543210\EasyForms\abstracts\Form;
use Frago9876543210\EasyForms\forms\CustomForm;
use Frago9876543210\EasyForms\forms\MenuForm;
use Frago9876543210\EasyForms\forms\ModalForm;
use Frago9876543210\EasyForms\forms\ServerSettingsForm;
use Frago9876543210\EasyForms\utils\PlayerFormData;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\network\mcpe\protocol\ServerSettingsRequestPacket;
use pocketmine\network\mcpe\protocol\ServerSettingsResponsePacket;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class EasyForms extends PluginBase implements Listener{
	public const SERVER_SETTINGS_ID = 1698473874;

	/** @var Map */
	private static $forms;
	/** @var null|ServerSettingsForm */
	public static $settings;

	public function onEnable() : void{
		self::$forms = new Map;
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onJoin(PlayerJoinEvent $e) : void{
		self::$forms->put($e->getPlayer(), new PlayerFormData);
	}

	public function onQuit(PlayerQuitEvent $e) : void{
		self::$forms->remove($e->getPlayer(), null);
	}

	private static function getFormData(Player $player) : ?PlayerFormData{
		return self::$forms->get($player, null);
	}

	public static function sendForm(Player $player, Form $form) : void{
		$formData = self::getFormData($player);
		$pk = new ModalFormRequestPacket;
		$pk->formId = mt_rand(0, PHP_INT_MAX);
		$pk->formData = json_encode($form);
		$player->dataPacket($pk);
		$formData->windows[$pk->formId] = $form;
	}

	public function onModalFormResponse(DataPacketReceiveEvent $e) : void{
		$pk = $e->getPacket();
		if($pk instanceof ModalFormResponsePacket){
			$isSettings = self::$settings !== null && $pk->formId === self::SERVER_SETTINGS_ID;
			try{
				$formData = self::getFormData($player = $e->getPlayer());
				if(isset($formData->windows[$pk->formId]) || $isSettings){
					$form = $isSettings ? self::$settings : $formData->windows[$pk->formId];
					if(($response = json_decode($pk->formData, true)) === null){
						$form->onClose($player);
					}else{
						if($form instanceof MenuForm && is_int($response)){
							$form->onSubmit($player, $response);
						}elseif($form instanceof ModalForm && is_bool($response)){
							$form->onSubmit($player, $response);
						}elseif(($form instanceof CustomForm || $form instanceof ServerSettingsForm) && is_array($response)){
							$form->onSubmit($player, $response);
						}else{
							$this->getLogger()->debug("Received wrong form data from {$player->getName()}");
						}
					}
					if(!$isSettings){
						unset($formData->windows[$pk->formId]);
					}
				}
			}catch(\Throwable $e){
				$this->getLogger()->logException($e);
			}
		}elseif($pk instanceof ServerSettingsRequestPacket){
			$pk = new ServerSettingsResponsePacket;
			$pk->formId = self::SERVER_SETTINGS_ID;
			$pk->formData = json_encode(self::$settings);
			$e->getPlayer()->dataPacket($pk);
		}
	}
}