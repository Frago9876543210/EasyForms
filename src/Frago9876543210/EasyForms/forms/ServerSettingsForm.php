<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms\forms;


class ServerSettingsForm extends CustomForm{
	/** @var null|string */
	public $icon;

	/**
	 * ServerSettingsForm constructor.
	 * @param string      $title
	 * @param array       $elements
	 * @param null|string $icon
	 */
	public function __construct(string $title, array $elements, ?string $icon = null){
		parent::__construct($title, $elements);
		$this->icon = $icon;
	}

	/**
	 * @return array
	 */
	public function serializeFormData() : array{
		$data = parent::serializeFormData();
		if($this->hasIcon()){
			$data["icon"] = [
				"type" => "url",
				"data" => $this->icon
			];
		}
		return $data;
	}

	/**
	 * @return bool
	 */
	public function hasIcon() : bool{
		return $this->icon !== null;
	}
}