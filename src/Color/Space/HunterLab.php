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

namespace Color\Space;

use \Color\Space as ColorSpace;

class HunterLab extends ColorSpace
{
	public static function getSpaceBoundaries()
	{
		return CIELab::getLabels();
	}

	public static function getLabels()
	{
		return CIELab::getLabels();
	}

	public static function validate(array $values)
	{
		return CIELab::validate($values);
	}

	public static function toXYZ()
	{
		list($HL,$Ha,$Hb) = static::interpolateArgs(func_num_args(), func_get_args());

		$var_Y = $HL / 10;
		$var_X = $Ha / 17.5 * $HL / 10;
		$var_Z = $Hb / 7 * $HL / 10;

		$Y = $var_Y ^ 2;
		$X =  ( $var_X + $Y ) / 1.02;
		$Z = -( $var_Z - $Y ) / 0.847;

		return [$Y,$X,$Z];
	}

	public static function toHEX()
	{
		$color = static::interpolateArgs(func_num_args(), func_get_args());
		$color = static::toXYZ($color);
		$color = XYZ::toRGB($color);
		$color = RGB::toHEX($color);
		return $color;
	}

	public static function toRGB()
	{
		$color = static::interpolateArgs(func_num_args(), func_get_args());
		$color = static::toXYZ($color);
		$color = XYZ::toRGB($color);
		return $color;
	}

	public static function toYxy()
	{
		$color = static::interpolateArgs(func_num_args(), func_get_args());
		$color = static::toXYZ($color);
		$color = XYZ::toYxy($color);
		return $color;
	}

	public static function toCIELab()
	{
		$color = static::interpolateArgs(func_num_args(), func_get_args());
		$color = static::toXYZ($color);
		$color = XYZ::toCIELab($color);
		return $color;
	}

	public static function toCIELch()
	{
		$color = static::interpolateArgs(func_num_args(), func_get_args());
		$color = static::toXYZ($color);
		$color = XYZ::toCIELab($color);
		$color = CIELab::toCIELch($color);
		return $color;
	}

	public static function toCIELuv()
	{
		$color = static::interpolateArgs(func_num_args(), func_get_args());
		$color = static::toXYZ($color);
		$color = XYZ::toCIELuv($color);
		return $color;
	}

	public static function toHSL()
	{
		$color = static::interpolateArgs(func_num_args(), func_get_args());
		$color = static::toXYZ($color);
		$color = XYZ::toRGB($color);
		$color = RGB::toHSL($color);
		return $color;
	}

	public static function toHSV()
	{
		$color = static::interpolateArgs(func_num_args(), func_get_args());
		$color = static::toXYZ($color);
		$color = XYZ::toRGB($color);
		$color = RGB::toHSV($color);
		return $color;
	}

	public static function toCMY()
	{
		$color = static::interpolateArgs(func_num_args(), func_get_args());
		$color = static::toXYZ($color);
		$color = XYZ::toRGB($color);
		$color = RGB::toCMY($color);
		return $color;
	}

	public static function toCMYK()
	{
		$color = static::interpolateArgs(func_num_args(), func_get_args());
		$color = static::toXYZ($color);
		$color = XYZ::toRGB($color);
		$color = RGB::toCMY($color);
		$color = CMY::toCMYK($color);
		return $color;
	}
}