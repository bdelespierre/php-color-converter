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

class Yxy extends ColorSpace
{
	public static function getSpaceBoundaries()
	{
		return [[0,0,0],[XYZ::$ref_Y,1,1]];
	}

	public static function getLabels()
	{
		return ['Y','x','y'];
	}

	public static function validate(array $values)
	{
		return count($values) == 3
			&& $values[0] >= 0 && $values[0] <= XYZ::$ref_Y
			&& $values[1] >= 0 && $values[1] <= 1
			&& $values[2] >= 0 && $values[2] <= 1;
	}

	public static function toXYZ()
	{
		list($Y,$x,$y) = static::interpolateArgs(func_num_args(), func_get_args());

		//Y from 0 to 100
		//x from 0 to 1
		//y from 0 to 1

		$X = $x * ( $Y / $y );
		$Y = $Y;
		$Z = ( 1 - $x - $y ) * ( $Y / $y );

		return [$X,$Y,$Z];
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

	public static function toHunterLab()
	{
		$color = static::interpolateArgs(func_num_args(), func_get_args());
		$color = static::toXYZ($color);
		$color = XYZ::toHunterLab($color);
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