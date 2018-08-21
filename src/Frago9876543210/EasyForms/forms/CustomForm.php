<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms\forms;


use Ds\Queue;
use Frago9876543210\EasyForms\elements\custom\Dropdown;
use Frago9876543210\EasyForms\elements\custom\Input;
use Frago9876543210\EasyForms\elements\custom\Label;
use Frago9876543210\EasyForms\elements\custom\Slider;
use Frago9876543210\EasyForms\elements\custom\Toggle;
use Frago9876543210\EasyForms\elements\Element;
use pocketmine\Player;

class CustomForm extends Form{
	/** @var Element[] */
	protected $elements;
	/** @var Queue */
	private $queue;

	/**
	 * CustomForm constructor.
	 * @param string    $title
	 * @param Element[] $elements
	 */
	public function __construct(string $title, array $elements){
		parent::__construct($title);
		$this->elements = $elements;
		$this->queue = new Queue;
	}

	/**
	 * @param Player $player
	 * @param mixed  $response
	 */
	public function onSubmit(Player $player, $response) : void{
		foreach($this->elements as $index => $element){
			$value = $response[$index];
			if(
				($element instanceof Dropdown && is_int($value)) ||
				($element instanceof Input && is_string($value)) ||
				($element instanceof Slider && (is_float($value) || is_int($value)) && ($value >= $element->getMin() || $value <= $element->getMax())) ||
				($element instanceof Toggle && is_bool($value))
			){
				$this->queue->push($element);
				$element->setValue($value);
			}
		}
	}

	/**
	 * @return Element
	 */
	public function popElement() : Element{
		return $this->queue->pop();
	}

	/**
	 * @return string
	 */
	final public function getType() : string{
		return self::TYPE_CUSTOM_FORM;
	}

	/**
	 * @return array
	 */
	protected function serializeFormData() : array{
		return ["content" => $this->elements];
	}
}