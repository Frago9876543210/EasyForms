<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms\elements;


class Label extends Element{

	/**
	 * @return null|string
	 */
	public function getType() : ?string{
		return "label";
	}

	/**
	 * @return array
	 */
	public function serializeElementData() : array{
		return [];
	}
}