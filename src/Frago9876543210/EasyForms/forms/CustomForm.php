<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms\forms;


use Frago9876543210\EasyForms\elements\Element;
use pocketmine\Player;

class CustomForm extends Form{
	/** @var Element[] */
	public $elements;

	/**
	 * CustomForm constructor.
	 * @param string    $title
	 * @param Element[] $elements
	 */
	public function __construct(string $title, array $elements){
		parent::__construct($title);
		$this->elements = $elements;
	}

	public function onSubmit(Player $player, $response) : void{
		foreach($response as $index => $value){
			if(isset($this->elements[$index])){
				$this->elements[$index]->handle($player, $value);
			}
		}
	}

	/**
	 * @return string
	 */
	public function getType() : string{
		return self::TYPE_CUSTOM_FORM;
	}

	/**
	 * @return array
	 */
	protected function serializeFormData() : array{
		return ["content" => $this->elements];
	}
}