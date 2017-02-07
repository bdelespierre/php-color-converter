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

class HSL extends AbstractSpace
{
    public static function getSpaceBoundaries()
    {
        return [[0,0,0],[360,1,1]];
    }

    public static function getLabels()
    {
        return ['H','S','L'];
    }

    public static function toRGB($values)
    {
        list($H,$S,$L) = $values;

        if ( $S == 0 )                        //HSL from 0 to 1
        {
            $R = $L * 255;                    //RGB results from 0 to 255
            $G = $L * 255;
            $B = $L * 255;
        }
        else
        {
            if ( $L < 0.5 ) $var_2 = $L * ( 1 + $S );
            else            $var_2 = ( $L + $S ) - ( $S * $L );

            $var_1 = 2 * $L - $var_2;

            $R = 255 * static::hueToRGB( $var_1, $var_2, $H + ( 1 / 3 ) ) ;
            $G = 255 * static::hueToRGB( $var_1, $var_2, $H );
            $B = 255 * static::hueToRGB( $var_1, $var_2, $H - ( 1 / 3 ) );
        }

        return [$R,$G,$B];
    }

    protected static function hueToRGB($v1, $v2, $vH)
    {
        if ( $vH < 0 ) $vH += 1;
        if ( $vH > 1 ) $vH -= 1;
        if ( ( 6 * $vH ) < 1 ) return ( $v1 + ( $v2 - $v1 ) * 6 * $vH );
        if ( ( 2 * $vH ) < 1 ) return ( $v2 );
        if ( ( 3 * $vH ) < 2 ) return ( $v1 + ( $v2 - $v1 ) * ( ( 2 / 3 ) - $vH ) * 6 );
        return ( $v1 );
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

    public static function toHSV($values)
    {
        $color = $values;
        $color = static::toRGB($color);
        $color = RGB::toHSV($color);
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
