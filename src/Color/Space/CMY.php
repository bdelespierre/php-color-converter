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

class CMY extends ColorSpace
{
	public static function getSpaceBoundaries()
	{
		return [[0,0,0],[1,1,1]];
	}

	public static function getLabels()
	{
		return ['C','M','Y'];
	}

	public static function validate(array $values)
	{
		return count($values) == 3
			&& $values[0] >= 0 && $values[0] <= 1
			&& $values[1] >= 0 && $values[1] <= 1
			&& $values[2] >= 0 && $values[2] <= 1;
	}

	public static function toRGB()
	{
		list($C,$M,$Y) = static::interpolateArgs(func_num_args(), func_get_args());

		//CMY values from 0 to 1
		//RGB results from 0 to 255

		$R = ( 1 - $C ) * 255;
		$G = ( 1 - $M ) * 255;
		$B = ( 1 - $Y ) * 255;

		return [$R,$G,$B];
	}

	public static function toCMYK()
	{
		list($C,$M,$Y) = static::interpolateArgs(func_num_args(), func_get_args());

		//CMYK and CMY values from 0 to 1

		$var_K = 1;

		if ( $C < $var_K )   $var_K = $C;
		if ( $M < $var_K )   $var_K = $M;
		if ( $Y < $var_K )   $var_K = $Y;
		if ( $var_K == 1 ) { //Black
			$C = 0;
			$M = 0;
			$Y = 0;
		} else {
			$C = ( $C - $var_K ) / ( 1 - $var_K );
			$M = ( $M - $var_K ) / ( 1 - $var_K );
			$Y = ( $Y - $var_K ) / ( 1 - $var_K );
		}
		$K = $var_K;

		return [$C,$M,$Y,$K];
	}

	public static function toHEX()
	{
		$color = static::interpolateArgs(func_num_args(), func_get_args());
		$color = static::toRGB($color);
		$color = RGB::toHEX($color);
		return $color;
	}

	public static function toXYZ()
	{
		$color = static::interpolateArgs(func_num_args(), func_get_args());
		$color = static::toRGB($color);
		$color = RGB::toXYZ($color);
		return $color;
	}

	public static function toYxy()
	{
		$color = static::interpolateArgs(func_num_args(), func_get_args());
		$color = static::toRGB($color);
		$color = RGB::toXYZ($color);
		$color = XYZ::toYxy($color);
		return $color;
	}

	public static function toHunterLab()
	{
		$color = static::interpolateArgs(func_num_args(), func_get_args());
		$color = static::toRGB($color);
		$color = RGB::toXYZ($color);
		$color = XYZ::toHunterLab($color);
		return $color;
	}

	public static function toCIELab()
	{
		$color = static::interpolateArgs(func_num_args(), func_get_args());
		$color = static::toRGB($color);
		$color = RGB::toXYZ($color);
		$color = XYZ::toCIELab($color);
		return $color;
	}

	public static function toCIELch()
	{
		$color = static::interpolateArgs(func_num_args(), func_get_args());
		$color = static::toRGB($color);
		$color = RGB::toXYZ($color);
		$color = XYZ::toCIELab($color);
		$color = CIELab::toCIELch($color);
		return $color;
	}

	public static function toCIELuv()
	{
		$color = static::interpolateArgs(func_num_args(), func_get_args());
		$color = static::toRGB($color);
		$color = RGB::toXYZ($color);
		$color = XYZ::toCIELuv($color);
		return $color;
	}

	public static function toHSL()
	{
		$color = static::interpolateArgs(func_num_args(), func_get_args());
		$color = static::toRGB($color);
		$color = RGB::toHSL($color);
		return $color;
	}

	public static function toHSV()
	{
		$color = static::interpolateArgs(func_num_args(), func_get_args());
		$color = static::toRGB($color);
		$color = RGB::toHSV($color);
		return $color;
	}
}