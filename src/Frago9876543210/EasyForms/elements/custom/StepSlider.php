<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms\elements\custom;


class StepSlider extends Dropdown{

	public function getType() : string{
		return "step_slider";
	}

	public function serializeElementData() : array{
		return [
			"steps" => $this->options,
			"default" => $this->default
		];
	}
}