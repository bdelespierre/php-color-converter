<?php
/**
 * This file is part of ColorConverter.php
 *
 * Copyright (c) 2014 Benjamin Delespierre
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Color;

class Color implements \ArrayAccess
{
	// supported color spaces
	const CMY       = "CMY";       const CMYK      = "CMYK";
	const HEX       = "HEX";       const CIELab    = "CIELab";
	const CIELCH    = "CIELCH";    const CIELuv    = "CIELuv";
	const HSL       = "HSL";       const HSV       = "HSV";
	const HunterLab = "HunterLab"; const RGB       = "RGB";
	const XYZ       = "XYZ";       const Yxy       = "Yxy";

	protected $space;
	protected $values;

	public function __construct($space, $values)
	{
		if (static::isSupported($space, $space))
			throw new \DomainException("unsupported color space: $space");

		$this->space     = $space;
		$this->values    = (array)$values;
	}

	/**
	 * Allows use of transformation
	 *
	 * @example $color->toHEX();
	 *
	 * @param  string $method
	 * @param  array  $args
	 * @throws BadMethodCallException If method names doesn't begin with 'to'
	 * @return Color
	 */
	public function __call($method, $args)
	{
		if (!preg_match('/^to(\w+)$/', $method, $matches))
			throw new \BadMethodCallException("unsupported method: $method");

		list(,$newSpace) = $matches;
		return $this->to($newSpace);
	}

	public function __toString()
	{
		return implode(',', $this->values);
	}

	public function to($newSpace)
	{
		if ($newSpace == $this->space)
			return $this;

		if (!static::isSupported($newSpace, $newSpace))
			throw new \DomainException("unsupported color space: $newSpace");

		$class  = "\\Color\\Space\\{$this->space}";
		$method = "to{$newSpace}";

		return new static($newSpace, $class::$method($this->values));
	}

	public function delatE(self $other)
	{
		list($L1,$a1,$b1) =  $this->to(self::CIELab)->values;
		list($L2,$a2,$b2) = $other->to(self::CIELab)->values;

		// euclidian distance between Lab1 & Lab2 colors
		return sqrt(
			($L2 - $L1) * ($L2 - $L1) +
			($a2 - $a1) * ($a2 - $a1) +
			($b2 - $b1) * ($b2 - $b1)
		);
	}

	public function toArray()
	{
		return $this->values;
	}

	public function getSpace()
	{
		return $this->space;
	}

	public function offsetExists($offset)
	{
		return isset($this->values[$offset]);
	}

	public function offsetGet($offset)
	{
		if (!isset($this->values[$offset]))
			throw new \OutOfRangeException("illegal offset: $offset");

		return $this->values[$offset];
	}

	public function offsetSet($offset, $value)
	{
		if (!isset($this->values[$offset]))
			throw new \OutOfRangeException("illegal offset: $offset");

		$this->values[$offset] = $value;
	}

	public function offsetUnset($offset)
	{
		throw new \LogicException("cannot unset a color offset");
	}

	public static function isSupported($colorSpace, & $validName = null)
	{
		static $memo = [];

		if (isset($memo[$colorSpace]))
			return $validName = $memo[$colorSpace];

		if (class_exists($colorSpace, true))
			return $memo[$colorSpace] = $validName = $colorSpace;

		// aliasing
		switch ($colorSpace) {
			case "Hunter-Lab":
			case "HLab":
				$memo[$colorSpace] = $validName = "HunterLab";
				return true;

			case "CIE-L*ab":
			case "Lab":
			case "L*a*b*":
			case "CIELAB":
				$memo[$colorSpace] = $validName = "CIELab";
				return true;

			case "CIE-L*CH°":
			case "LCH":
			case "L*c*h*":
				$memo[$colorSpace] = $validName = "CIELCH";
				return true;

			case "CIE-L*uv":
			case "CIELUV":
			case "L*u*v*":
				$memo[$colorSpace] = $validName = "CIELuv";
				return true;

			case 'Hex':
				$memo[$colorSpace] = $validName = "HEX";
				return true;
		}

		return false;
	}
}