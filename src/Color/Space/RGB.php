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

class RGB extends ColorSpace
{
	public static function getSpaceBoundaries()
	{
		return [[0,0,0],[255,255,255]];
	}

	public static function getLabels()
	{
		return ['R','G','B'];
	}

	public static function validate(array $values)
	{
		return count($values) == 3
			&& $values[0] >= 0 && $values[0] <= 255
			&& $values[1] >= 0 && $values[1] <= 255
			&& $values[2] >= 0 && $values[2] <= 255;
	}

	public static function toHEX()
	{
		list($R,$G,$B) = static::interpolateArgs(func_num_args(), func_get_args());

		$HEX = sprintf('#%02x%02x%02x', $R, $G, $B);

		return [$HEX];
	}

	public static function toXYZ()
	{
		list($R,$G,$B) = static::interpolateArgs(func_num_args(), func_get_args());

		$var_R = ( $R / 255 );        //R from 0 to 255
		$var_G = ( $G / 255 );        //G from 0 to 255
		$var_B = ( $B / 255 );        //B from 0 to 255

		if ( $var_R > 0.04045 ) $var_R = pow( ( $var_R + 0.055 ) / 1.055 , 2.4);
		else                    $var_R = $var_R / 12.92;
		if ( $var_G > 0.04045 ) $var_G = pow( ( $var_G + 0.055 ) / 1.055 , 2.4);
		else                    $var_G = $var_G / 12.92;
		if ( $var_B > 0.04045 ) $var_B = pow( ( $var_B + 0.055 ) / 1.055 , 2.4);
		else                    $var_B = $var_B / 12.92;

		$var_R = $var_R * 100;
		$var_G = $var_G * 100;
		$var_B = $var_B * 100;

		//Observer. = 2°, Illuminant = D65
		$X = $var_R * 0.4124 + $var_G * 0.3576 + $var_B * 0.1805;
		$Y = $var_R * 0.2126 + $var_G * 0.7152 + $var_B * 0.0722;
		$Z = $var_R * 0.0193 + $var_G * 0.1192 + $var_B * 0.9505;

		return [$X,$Y,$Z];
	}

	public static function toHSL()
	{
		list($R,$G,$B) = static::interpolateArgs(func_num_args(), func_get_args());

		$var_R = ( $R / 255 );                       //RGB from 0 to 255
		$var_G = ( $G / 255 );
		$var_B = ( $B / 255 );

		$var_Min = min( $var_R, $var_G, $var_B );    //Min. value of RGB
		$var_Max = max( $var_R, $var_G, $var_B );    //Max. value of RGB
		$del_Max = $var_Max - $var_Min;              //Delta RGB value

		$L = ( $var_Max + $var_Min ) / 2;

		if ( $del_Max == 0 ) {                       //This is a gray, no chroma...
			$H = 0;                                  //HSL results from 0 to 1
			$S = 0;
		} else {                                     //Chromatic data...
			if ( $L < 0.5 ) $S = $del_Max / ( $var_Max + $var_Min );
			else            $S = $del_Max / ( 2 - $var_Max - $var_Min );

			$del_R = ( ( ( $var_Max - $var_R ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
			$del_G = ( ( ( $var_Max - $var_G ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
			$del_B = ( ( ( $var_Max - $var_B ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;

			if      ( $var_R == $var_Max ) $H = $del_B - $del_G;
			else if ( $var_G == $var_Max ) $H = ( 1 / 3 ) + $del_R - $del_B;
			else if ( $var_B == $var_Max ) $H = ( 2 / 3 ) + $del_G - $del_R;

			if ( $H < 0 ) $H += 1;
			if ( $H > 1 ) $H -= 1;
		}

		return [$H,$S,$L];
	}

	public static function toHSV()
	{
		list($R,$G,$B) = static::interpolateArgs(func_num_args(), func_get_args());

		$var_R = ( $R / 255 );                       //RGB from 0 to 255
		$var_G = ( $G / 255 );
		$var_B = ( $B / 255 );

		$var_Min = min( $var_R, $var_G, $var_B );    //Min. value of RGB
		$var_Max = max( $var_R, $var_G, $var_B );    //Max. value of RGB
		$del_Max = $var_Max - $var_Min;              //Delta RGB value

		$V = $var_Max;

		if ( $del_Max == 0 ) {                       //This is a gray, no chroma...
			$H = 0;                                  //HSV results from 0 to 1
			$S = 0;
		} else {                                     //Chromatic data...
			$S = $del_Max / $var_Max;

			$del_R = ( ( ( $var_Max - $var_R ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
			$del_G = ( ( ( $var_Max - $var_G ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
			$del_B = ( ( ( $var_Max - $var_B ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;

			if      ( $var_R == $var_Max ) $H = $del_B - $del_G;
			else if ( $var_G == $var_Max ) $H = ( 1 / 3 ) + $del_R - $del_B;
			else if ( $var_B == $var_Max ) $H = ( 2 / 3 ) + $del_G - $del_R;

			if ( $H < 0 ) $H += 1;
			if ( $H > 1 ) $H -= 1;
		}

		return [$H,$S,$V];
	}

	public static function toCMY()
	{
		list($R,$G,$B) = static::interpolateArgs(func_num_args(), func_get_args());

		//RGB values from 0 to 255
		//CMY results from 0 to 1

		$C = 1 - ( $R / 255 );
		$M = 1 - ( $G / 255 );
		$Y = 1 - ( $B / 255 );

		return [$C,$M,$Y];
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

	public static function toCMYK()
	{
		$color = static::interpolateArgs(func_num_args(), func_get_args());
		$color = static::toCMY($color);
		$color = CMY::toCMYK($color);
		return $color;
	}
}