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

class HEX extends AbstractSpace
{
    public static function getSpaceBoundaries()
    {
        return [['#000000'],['#FFFFFF']];
    }

    public static function getLabels()
    {
        return ['value'];
    }

    public static function validate(array $values)
    {
        if (!count($values)) {
            return false;
        }

        if (!preg_match('/^#?([0-9a-f]{3}|[0-9a-f]{6})$/i', $values[0])) {
            return false;
        }

        return hexdec($values[0]) >= 0
            && hexdec($values[0]) <= 0xFFFFFF;
    }

    public static function toRGB($values)
    {
        list($HEX) = $values;

        // transform the HEX value (if neeed)
        $HEX[0]     == '#' && $HEX = substr($HEX, 1);
        strlen($HEX) == 3  && $HEX = "{$HEX[0]}{$HEX[0]}{$HEX[1]}{$HEX[1]}{$HEX[2]}{$HEX[2]}";

        $R = (int)hexdec("{$HEX[0]}{$HEX[1]}");
        $G = (int)hexdec("{$HEX[2]}{$HEX[3]}");
        $B = (int)hexdec("{$HEX[4]}{$HEX[5]}");

        return [$R,$G,$B];
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
