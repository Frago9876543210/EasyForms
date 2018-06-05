<?php

declare(strict_types=1);

namespace Frago9876543210\EasyForms\elements\custom;


use Frago9876543210\EasyForms\elements\Element;

class Slider extends Element{
	/** @var float */
	protected $min;
	/** @var float */
	protected $max;
	/** @var float */
	protected $step = 1.0;
	/** @var float */
	protected $default;

	public function __construct(string $text, float $min, float $max, float $step = 1.0, ?float $default = null){
		parent::__construct($text);
		if($this->min > $this->max){
			throw new \InvalidArgumentException("Slider min value should be less than max value");
		}
		$this->min = $min;
		$this->max = $max;
		if($default !== null){
			if($default > $this->max or $default < $this->min){
				throw new \InvalidArgumentException("Default must be in range $this->min ... $this->max");
			}
			$this->default = $default;
		}else{
			$this->default = $this->min;
		}
		if($step <= 0){
			throw new \InvalidArgumentException("Step must be greater than zero");
		}
		$this->step = $step;
	}

	/**
	 * @return null|string
	 */
	public function getType() : ?string{
		return "slider";
	}

	/**
	 * @return array
	 */
	public function serializeElementData() : array{
		return [
			"min" => $this->min,
			"max" => $this->max,
			"default" => $this->default,
			"step" => $this->step
		];
	}
}