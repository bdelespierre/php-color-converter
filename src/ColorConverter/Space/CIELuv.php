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

class CIELuv extends AbstractSpace
{
    public static function getSpaceBoundaries()
    {
        return [[0,-100,-100],[100,100,100]];
    }

    public static function getLabels()
    {
        return ['L','u','v'];
    }

    public static function toXYZ($values)
    {
        list($CIEL,$CIEu,$CIEv) = $values;

        $var_Y = ( $CIEL + 16 ) / 116;
        if ( pow($var_Y , 3) > 0.008856 ) $var_Y = pow($var_Y , 3);
        else                              $var_Y = ( $var_Y - 16 / 116 ) / 7.787;

        $ref_X = XYZ::$ref_X;
        $ref_Y = XYZ::$ref_Y;
        $ref_Z = XYZ::$ref_Z;

        $ref_U = ( 4 * $ref_X ) / ( $ref_X + ( 15 * $ref_Y ) + ( 3 * $ref_Z ) );
        $ref_V = ( 9 * $ref_Y ) / ( $ref_X + ( 15 * $ref_Y ) + ( 3 * $ref_Z ) );

        $var_U = $CIEu / ( 13 * $CIEL ) + $ref_U;
        $var_V = $CIEv / ( 13 * $CIEL ) + $ref_V;

        $Y = $var_Y * 100;
        $X =  - ( 9 * $Y * $var_U ) / ( ( $var_U - 4 ) * $var_V  - $var_U * $var_V );
        $Z = ( 9 * $Y - ( 15 * $var_V * $Y ) - ( $var_V * $X ) ) / ( 3 * $var_V );

        return [$X,$Y,$Z];
    }

    public static function toHEX($values)
    {
        $color = $values;
        $color = static::toXYZ($color);
        $color = XYZ::toRGB($color);
        $color = RGB::toHEX($color);
        return $color;
    }

    public static function toRGB($values)
    {
        $color = $values;
        $color = static::toXYZ($color);
        $color = XYZ::toRGB($color);
        return $color;
    }

    public static function toYxy($values)
    {
        $color = $values;
        $color = static::toXYZ($color);
        $color = XYZ::toYxy($color);
        return $color;
    }

    public static function toHunterLab($values)
    {
        $color = $values;
        $color = static::toXYZ($color);
        $color = XYZ::toHunterLab($color);
        return $color;
    }

    public static function toCIELab($values)
    {
        $color = $values;
        $color = static::toXYZ($color);
        $color = XYZ::toCIELab($color);
        return $color;
    }

    public static function toCIELch($values)
    {
        $color = $values;
        $color = static::toXYZ($color);
        $color = XYZ::toCIELab($color);
        $color = CIELab::toCIELch($color);
        return $color;
    }

    public static function toHSL($values)
    {
        $color = $values;
        $color = static::toXYZ($color);
        $color = XYZ::toRGB($color);
        $color = RGB::toHSL($color);
        return $color;
    }

    public static function toHSV($values)
    {
        $color = $values;
        $color = static::toXYZ($color);
        $color = XYZ::toRGB($color);
        $color = RGB::toHSV($color);
        return $color;
    }

    public static function toCMY($values)
    {
        $color = $values;
        $color = static::toXYZ($color);
        $color = XYZ::toRGB($color);
        $color = RGB::toCMY($color);
        return $color;
    }

    public static function toCMYK($values)
    {
        $color = $values;
        $color = static::toXYZ($color);
        $color = XYZ::toRGB($color);
        $color = RGB::toCMY($color);
        $color = CMY::toCMYK($color);
        return $color;
    }
}
