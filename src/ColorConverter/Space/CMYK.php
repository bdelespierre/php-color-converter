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

class CMYK extends AbstractSpace
{
    public static function getSpaceBoundaries()
    {
        return [[0,0,0,0],[1,1,1,1]];
    }

    public static function getLabels()
    {
        return ['C','M','Y','K'];
    }

    public static function CMYK_to_CMY()
    {
        list($C,$M,$Y,$K) = $values;

        //CMYK and CMY values from 0 to 1

        $C = ( $C * ( 1 - $K ) + $K );
        $M = ( $M * ( 1 - $K ) + $K );
        $Y = ( $Y * ( 1 - $K ) + $K );

        return [$C,$M,$Y];
    }

    public static function toHEX($values)
    {
        $color = $values;
        $color = static::toCMY($color);
        $color = CMY::toRGB($color);
        $color = RGB::toHEX($color);
        return $color;
    }

    public static function toXYZ($values)
    {
        $color = $values;
        $color = static::toCMY($color);
        $color = CMY::toRGB($color);
        $color = RGB::toXYZ($color);
        return $color;
    }

    public static function toRGB($values)
    {
        $color = $values;
        $color = static::toCMY($color);
        $color = CMY::toRGB($color);
        return $color;
    }

    public static function toYxy($values)
    {
        $color = $values;
        $color = static::toCMY($color);
        $color = CMY::toRGB($color);
        $color = RGB::toXYZ($color);
        $color = XYZ::toYxy($color);
        return $color;
    }

    public static function toHunterLab($values)
    {
        $color = $values;
        $color = static::toCMY($color);
        $color = CMY::toRGB($color);
        $color = RGB::toXYZ($color);
        $color = XYZ::toHunterLab($color);
        return $color;
    }

    public static function toCIELab($values)
    {
        $color = $values;
        $color = static::toCMY($color);
        $color = CMY::toRGB($color);
        $color = RGB::toXYZ($color);
        $color = XYZ::toCIELab($color);
        return $color;
    }

    public static function toCIELch($values)
    {
        $color = $values;
        $color = static::toCMY($color);
        $color = CMY::toRGB($color);
        $color = RGB::toXYZ($color);
        $color = XYZ::toCIELab($color);
        $color = CIELab::toCIELch($color);
        return $color;
    }

    public static function toCIELuv($values)
    {
        $color = $values;
        $color = static::toCMY($color);
        $color = CMY::toRGB($color);
        $color = RGB::toXYZ($color);
        $color = XYZ::toCIELuv($color);
        return $color;
    }

    public static function toHSL($values)
    {
        $color = $values;
        $color = static::toCMY($color);
        $color = CMY::toRGB($color);
        $color = RGB::toHSL($color);
        return $color;
    }

    public static function toHSV($values)
    {
        $color = $values;
        $color = static::toCMY($color);
        $color = CMY::toRGB($color);
        $color = RGB::toHSV($color);
        return $color;
    }
}
