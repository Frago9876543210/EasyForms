<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms\forms;


class ModalForm extends Form{
	/** @var string */
	protected $text;
	/** @var string */
	protected $yesButton;
	/** @var string */
	protected $noButton;

	/**
	 * ModalForm constructor.
	 * @param string $title
	 * @param string $text
	 * @param string $yesButton
	 * @param string $noButton
	 */
	public function __construct(string $title, string $text, string $yesButton = "gui.yes", string $noButton = "gui.no"){
		parent::__construct($title);
		$this->text = $text;
		$this->yesButton = $yesButton;
		$this->noButton = $noButton;
	}

	/**
	 * @return string
	 */
	final public function getType() : string{
		return self::TYPE_MODAL;
	}

	/**
	 * @return string
	 */
	public function getYesButtonText() : string{
		return $this->yesButton;
	}

	/**
	 * @return string
	 */
	public function getNoButtonText() : string{
		return $this->noButton;
	}

	/**
	 * @return array
	 */
	protected function serializeFormData() : array{
		return [
			"content" => $this->text,
			"button1" => $this->yesButton,
			"button2" => $this->noButton
		];
	}
}