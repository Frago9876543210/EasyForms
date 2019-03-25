<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms\forms;


use function array_merge;

abstract class Form implements \pocketmine\form\Form{
	protected const TYPE_MODAL = "modal";
	protected const TYPE_MENU = "form";
	protected const TYPE_CUSTOM_FORM = "custom_form";

	/** @var string */
	protected $title;

	/**
	 * @param string $title
	 */
	public function __construct(string $title){
		$this->title = $title;
	}

	/**
	 * @return array
	 */
	final public function jsonSerialize() : array{
		return array_merge([
			"title" => $this->getTitle(), "type" => $this->getType()
		], $this->serializeFormData());
	}

	/**
	 * @return string
	 */
	public function getTitle() : string{
		return $this->title;
	}

	/**
	 * @return string
	 */
	abstract public function getType() : string;

	/**
	 * @return array
	 */
	abstract protected function serializeFormData() : array;
}