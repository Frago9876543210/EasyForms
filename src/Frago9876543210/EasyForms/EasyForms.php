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

	/** @var Map */
	private static $forms;

	public function onEnable() : void{
		self::$forms = new Map;
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	/* Interaction with forms */

	public static function sendForm(Player $player, Form $form) : void{
		$settings = $form instanceof ServerSettingsForm;
		$pk = $settings ? new ServerSettingsResponsePacket : new ModalFormRequestPacket;
		$pk->formId = $settings ? self::SERVER_SETTINGS_ID : mt_rand(0, INT32_MAX);
		$pk->formData = json_encode($form);
		$player->dataPacket($pk);
		self::addForm($player, $pk->formId, $form);
	}

	private static function addForm(Player $player, int $formId, Form $form) : void{
		$list = self::$forms->get($player, []);
		$list[$formId] = $form;
		self::$forms->put($player, $list);
	}

	private static function hasForm(Player $player, int $formId) : bool{
		return isset(self::$forms->get($player, [])[$formId]);
	}

	private static function getForm(Player $player, int $formId) : ?Form{
		return self::hasForm($player, $formId) ? self::$forms->get($player)[$formId] : null;
	}

	private static function removeForm(Player $player, int $formId) : void{
		$list = self::$forms->get($player, []);
		if(self::hasForm($player, $formId)){
			unset($list[$formId]);
		}
		self::$forms->put($player, $list);
	}

	/* Event handlers */

	public function onJoin(PlayerJoinEvent $e) : void{
		self::$forms->put($e->getPlayer(), []);
	}

	public function onQuit(PlayerQuitEvent $e) : void{
		self::$forms->remove($e->getPlayer(), null);
	}

	public function onDataPacketReceive(DataPacketReceiveEvent $e) : void{
		$pk = $e->getPacket();
		if($pk instanceof ModalFormResponsePacket){
			if(($form = self::getForm($player = $e->getPlayer(), $pk->formId)) !== null){
				$this->handleModalFormResponse($player, $pk->formId, $form, json_decode($pk->formData, true));
			}
		}elseif($pk instanceof ServerSettingsRequestPacket){
			$this->getServer()->getPluginManager()->callEvent(new ServerSettingsRequestEvent($this, $e->getPlayer()));
		}
	}

	public function handleModalFormResponse(Player $player, int $formId, Form $form, $response) : void{
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
			self::removeForm($player, $formId);
		}catch(\Exception $e){
			$this->getServer()->getLogger()->logException($e);
		}
	}
}