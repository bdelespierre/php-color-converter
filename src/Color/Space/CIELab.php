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

class CIELab extends ColorSpace
{
	public static function getSpaceBoundaries()
	{
		return [[0,-128,-128],[100,127,127]];
	}

	public static function getLabels()
	{
		return ['L','a','b'];
	}

	public static function validate(array $values)
	{
		return count($values) == 3
			&& $values[0] >= 0 && $values[0] <= 100
			&& $values[1] >= -128 && $values[1] <= 127
			&& $values[2] >= -128 && $values[2] <= 127;
	}

	public static function toXYZ()
	{
		list($CIEL,$CIEa,$CIEb) = static::interpolateArgs(func_num_args(), func_get_args());

		$var_Y = ( $CIEL + 16 ) / 116;
		$var_X = $CIEa / 500 + $var_Y;
		$var_Z = $var_Y - $CIEb / 200;

		if ( pow($var_Y,3) > 0.008856 ) $var_Y = pow($var_Y,3);
		else                            $var_Y = ( $var_Y - 16 / 116 ) / 7.787;
		if ( pow($var_X,3) > 0.008856 ) $var_X = pow($var_X,3);
		else                            $var_X = ( $var_X - 16 / 116 ) / 7.787;
		if ( pow($var_Z,3) > 0.008856 ) $var_Z = pow($var_Z,3);
		else                            $var_Z = ( $var_Z - 16 / 116 ) / 7.787;

		$X = XYZ::$ref_X * $var_X;
		$Y = XYZ::$ref_Y * $var_Y;
		$Z = XYZ::$ref_Z * $var_Z;

		return [$X,$Y,$Z];
	}

	public static function toCIELch()
	{
		list($CIEL,$CIEa,$CIEb) = static::interpolateArgs(func_num_args(), func_get_args());

		$var_H = atan( $CIEb, $CIEa );  //Quadrant by signs

		if ( $var_H > 0 ) $var_H = ( $var_H / pi() ) * 180;
		else              $var_H = 360 - ( abs( $var_H ) / pi() ) * 180;

		$CIEL = $CIEL;
		$CIEC = sqrt( pow($CIEa , 2) + pow($CIEb, 2 ) );
		$CIEH = $var_H;

		return [$CIEL,$CIEC,$CIEH];
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

	public static function toHunterLab()
	{
		$color = static::interpolateArgs(func_num_args(), func_get_args());
		$color = static::toXYZ($color);
		$color = XYZ::toHunterLab($color);
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