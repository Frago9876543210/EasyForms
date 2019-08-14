<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms\forms;

use Closure;
use pocketmine\form\FormValidationException;
use pocketmine\player\Player;
use pocketmine\utils\Utils;
use function is_bool;

class ModalForm extends Form{
	/** @var string */
	private $content;
	/** @var string */
	private $yesButton;
	/** @var string */
	private $noButton;

	public function __construct(string $title, string $content, Closure $onSubmit, string $yesButton = "gui.yes", string $noButton = "gui.no"){
		parent::__construct($title);
		$this->content = $content;
		$this->onSubmit($onSubmit);
		$this->yesButton = $yesButton;
		$this->noButton = $noButton;
	}

	public static function confirm(string $title, string $text, Closure $onConfirm) : self{
		Utils::validateCallableSignature(function(Player $player) : void{}, $onConfirm);
		return new self($title, $text, function(Player $player, bool $response) use ($onConfirm): void{
			if($response){
				$onConfirm($player);
			}
		});
	}

	/**
	 * @return string
	 */
	protected function getType() : string{
		return self::TYPE_MODAL;
	}

	/**
	 * @return callable
	 */
	protected function getOnSubmitCallableSignature() : callable{
		return function(Player $player, bool $response) : void{};
	}

	/**
	 * @return array
	 */
	protected function serializeFormData() : array{
		return [
			"content" => $this->content,
			"button1" => $this->yesButton,
			"button2" => $this->noButton
		];
	}

	final public function handleResponse(Player $player, $data) : void{
		if($data === null){
			if($this->onClose !== null){
				($this->onClose)($player);
			}
		}elseif(is_bool($data)){
			($this->onSubmit)($player, $data);
		}else{
			throw new FormValidationException("Expected bool or null, got " . gettype($data));
		}
	}
}