<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms;

use Ds\Map;
use Frago9876543210\EasyForms\forms\CustomForm;
use Frago9876543210\EasyForms\forms\Form;
use Frago9876543210\EasyForms\forms\MenuForm;
use Frago9876543210\EasyForms\forms\ModalForm;
use Frago9876543210\EasyForms\forms\ServerSettingsForm;
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
	/** @var null|ServerSettingsForm */
	public static $settings;
	/** @var Map */
	private static $forms;

	public static function sendForm(Player $player, Form $form) : void{
		$pk = new ModalFormRequestPacket;
		$pk->formId = mt_rand(0, INT32_MAX);
		$pk->formData = json_encode($form);
		$player->dataPacket($pk);
		self::addForm($player, $pk->formId, $form);
	}

	private static function addForm(Player $player, int $formId, Form $form) : void{
		$list = self::$forms->get($player, []);
		$list[$formId] = $form;
		self::$forms->put($player, $list);
	}

	public function onEnable() : void{
		self::$forms = new Map;
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onJoin(PlayerJoinEvent $e) : void{
		self::$forms->put($e->getPlayer(), []);
	}

	public function onQuit(PlayerQuitEvent $e) : void{
		self::$forms->remove($e->getPlayer(), null);
	}

	public function onDataPacketReceive(DataPacketReceiveEvent $e) : void{
		$pk = $e->getPacket();
		if($pk instanceof ModalFormResponsePacket){
			$isSettings = self::$settings !== null && $pk->formId === self::SERVER_SETTINGS_ID;
			if(self::hasForm($player = $e->getPlayer(), $pk->formId) || $isSettings){
				$form = $isSettings ? self::$settings : self::getForm($player, $pk->formId);
				$this->handleModalFormResponse($player, $form, json_decode($pk->formData, true));
			}
		}elseif($pk instanceof ServerSettingsRequestPacket){
			$pk = new ServerSettingsResponsePacket;
			$pk->formId = self::SERVER_SETTINGS_ID;
			$pk->formData = json_encode(self::$settings);
			$e->getPlayer()->dataPacket($pk);
		}
	}

	private static function hasForm(Player $player, int $formId) : bool{
		return isset(self::$forms->get($player, [])[$formId]);
	}

	private static function getForm(Player $player, int $formId) : ?Form{
		return self::hasForm($player, $formId) ? self::$forms->get($player)[$formId] : null;
	}

	public function handleModalFormResponse(Player $player, Form $form, $response) : void{
		try{
			if($response === null){
				$form->onClose($player);
			}else{
				if(
					($form instanceof MenuForm && is_int($response)) ||
					($form instanceof ModalForm && is_bool($response)) ||
					($form instanceof CustomForm && is_array($response))
				){
					$form->onSubmit($player, $response);
				}else{
					$this->getLogger()->debug("Received wrong form data from {$player->getName()}");
				}
			}
			self::$forms->remove($player, null);
		}catch(\Exception $e){
			$this->getServer()->getLogger()->logException($e);
		}
	}
}