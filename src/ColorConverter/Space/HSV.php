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

namespace ColorConverter\Space;

class HSV extends AbstractSpace
{
    public static function getSpaceBoundaries()
    {
        return [[0,0,0],[360,1,1]];
    }

    public static function getLabels()
    {
        return ['H','S','V'];
    }

    public static function toRGB($values)
    {
        list($H,$S,$V) = $values;

        if ( $S == 0 ) {                        //HSV from 0 to 1
            $R = $V * 255;
            $G = $V * 255;
            $B = $V * 255;
        } else {
            $var_h = $H * 6;
            if ( $var_h == 6 ) $var_h = 0;      //H must be < 1
            $var_i = int( $var_h );             //Or ... $var_i = floor( $var_h )
            $var_1 = $V * ( 1 - $S );
            $var_2 = $V * ( 1 - $S * ( $var_h - $var_i ) );
            $var_3 = $V * ( 1 - $S * ( 1 - ( $var_h - $var_i ) ) );

            if      ( $var_i == 0 ) { $var_r = $V     ; $var_g = $var_3 ; $var_b = $var_1 ; }
            else if ( $var_i == 1 ) { $var_r = $var_2 ; $var_g = $V     ; $var_b = $var_1 ; }
            else if ( $var_i == 2 ) { $var_r = $var_1 ; $var_g = $V     ; $var_b = $var_3 ; }
            else if ( $var_i == 3 ) { $var_r = $var_1 ; $var_g = $var_2 ; $var_b = $V     ; }
            else if ( $var_i == 4 ) { $var_r = $var_3 ; $var_g = $var_1 ; $var_b = $V     ; }
            else                    { $var_r = $V     ; $var_g = $var_1 ; $var_b = $var_2 ; }

            $R = $var_r * 255;                  //RGB results from 0 to 255
            $G = $var_g * 255;
            $B = $var_b * 255;
        }

        return [$R,$G,$B];
    }

    public static function toHEX($values)
    {
        $color = $values;
        $color = static::toRGB($color);
        $color = RGB::toHEX($color);
        return $color;
    }

    public static function toXYZ($values)
    {
        $color = $values;
        $color = static::toRGB($color);
        $color = RGB::toXYZ($color);
        return $color;
    }

    public static function toYxy($values)
    {
        $color = $values;
        $color = static::toRGB($color);
        $color = RGB::toXYZ($color);
        $color = XYZ::toYxy($color);
        return $color;
    }

    public static function toHunterLab($values)
    {
        $color = $values;
        $color = static::toRGB($color);
        $color = RGB::toXYZ($color);
        $color = XYZ::toHunterLab($color);
        return $color;
    }

    public static function toCIELab($values)
    {
        $color = $values;
        $color = static::toRGB($color);
        $color = RGB::toXYZ($color);
        $color = XYZ::toCIELab($color);
        return $color;
    }

    public static function toCIELch($values)
    {
        $color = $values;
        $color = static::toRGB($color);
        $color = RGB::toXYZ($color);
        $color = XYZ::toCIELab($color);
        $color = CIELab::toCIELch($color);
        return $color;
    }

    public static function toCIELuv($values)
    {
        $color = $values;
        $color = static::toRGB($color);
        $color = RGB::toXYZ($color);
        $color = XYZ::toCIELuv($color);
        return $color;
    }

    public static function toHSL($values)
    {
        $color = $values;
        $color = static::toRGB($color);
        $color = RGB::toHSL($color);
        return $color;
    }

    public static function toCMY($values)
    {
        $color = $values;
        $color = static::toRGB($color);
        $color = RGB::toCMY($color);
        return $color;
    }

    public static function toCMYK($values)
    {
        $color = $values;
        $color = static::toRGB($color);
        $color = RGB::toCMY($color);
        $color = CMY::toCMYK($color);
        return $color;
    }
}
