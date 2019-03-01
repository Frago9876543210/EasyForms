<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms\forms;


use Frago9876543210\EasyForms\elements\Button;
use pocketmine\form\FormValidationException;
use pocketmine\Player;
use pocketmine\utils\Utils;

class MenuForm extends Form{
	/** @var Button[] */
	protected $buttons;
	/** @var string */
	protected $text;
	/** @var \Closure */
	private $onSubmit;
	/** @var \Closure|null */
	private $onClose;

	/**
	 * MenuForm constructor.
	 * @param string        $title
	 * @param string        $text
	 * @param Button[]      $buttons
	 * @param \Closure      $onSubmit
	 * @param \Closure|null $onClose
	 */
	public function __construct(string $title, string $text, array $buttons, \Closure $onSubmit, ?\Closure $onClose = null){
		parent::__construct($title);
		$this->text = $text;
		$this->buttons = $buttons;
		Utils::validateCallableSignature(function(Player $player, Button $selected) : void{
		}, $onSubmit);
		$this->onSubmit = $onSubmit;
		if($onClose !== null){
			Utils::validateCallableSignature(function(Player $player) : void{
			}, $onClose);
			$this->onClose = $onClose;
		}
	}

	/**
	 * @return string
	 */
	final public function getType() : string{
		return self::TYPE_MENU;
	}

	/**
	 * @return array
	 */
	protected function serializeFormData() : array{
		return [
			"buttons" => $this->buttons,
			"content" => $this->text
		];
	}

	final public function handleResponse(Player $player, $data) : void{
		if($data === null){
			if($this->onClose !== null){
				($this->onClose)($player, $data);
			}
		}elseif(is_int($data)){
			if(!isset($this->buttons[$data])){
				throw new FormValidationException("Button with index $data does not exist");
			}
			$button = $this->buttons[$data];
			$button->setValue($data);
			($this->onSubmit)($player, $button);
		}else{
			throw new FormValidationException("Expected int or null, got " . gettype($data));
		}
	}
}