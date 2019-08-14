<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms\forms;

use Closure;
use Frago9876543210\EasyForms\elements\Image;

class ServerSettingsForm extends CustomForm{
	/** @var Image|null */
	private $image;

	/**
	 * @param string        $title
	 * @param               $elements
	 * @param Image|null    $image
	 * @param Closure      $onSubmit
	 * @param Closure|null $onClose
	 */
	public function __construct(string $title, $elements, ?Image $image, Closure $onSubmit, ?Closure $onClose = null){
		parent::__construct($title, $elements, $onSubmit, $onClose);
		$this->image = $image;
	}

	/**
	 * @return bool
	 */
	public function hasImage() : bool{
		return $this->image !== null;
	}

	/**
	 * @return array
	 */
	public function serializeFormData() : array{
		$data = parent::serializeFormData();
		if($this->hasImage()){
			$data["icon"] = $this->image;
		}
		return $data;
	}
}