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

class XYZ extends AbstractSpace
{
    /**
     * XYZ (Tristimulus) Reference values of a perfect reflecting diffuser
     *  - used in CIE-L*ab & CIE-L*uv to XYZ (an vice-versa) conversions
     *  - indexed by white reference and luminant (in degres)
     *
     * Observers:
     *  -  2° (CIE 1931)
     *  - 10° (CIE 1964)
     *
     * @var array
     */
    public static $XYZ_ref = [
        'A'    => ['2' => [109.850, 100,  35.585], '10' => [111.144, 100,  35.200]], // incandescent
        'C'    => ['2' => [ 98.074, 100, 118.232], '10' => [ 97.285, 100, 116.145]],
        'D50'  => ['2' => [ 96.422, 100,  82.521], '10' => [ 96.720, 100,  81.427]],
        'D55'  => ['2' => [ 95.682, 100,  92.149], '10' => [ 95.799, 100,  90.926]],
        'D65'  => ['2' => [ 95.047, 100, 108.883], '10' => [ 94.811, 100, 107.304]], // daylight
        'D75'  => ['2' => [ 94.972, 100, 122.638], '10' => [ 94.416, 100, 120.641]],
        'F2'   => ['2' => [ 99.187, 100,  67.395], '10' => [103.280, 100,  69.026]], // fluorescent
        'F7'   => ['2' => [ 95.044, 100, 108.755], '10' => [ 95.792, 100, 107.687]],
        'F11'  => ['2' => [100.966, 100,  64.370], '10' => [103.866, 100,  65.627]],
    ];

    /**
     * Default XYZ reference values
     *  - Observer 2°, Illuminant D65
     *
     * @var float
     */
    public static $ref_X =  95.047;
    public static $ref_Y = 100.000;
    public static $ref_Z = 108.883;

    public static function getSpaceBoundaries()
    {
        return [[0,0,0],[static::$ref_X, static::$ref_Y, static::$ref_Z]];
    }

    public static function getLabels()
    {
        return ['X','Y','Z'];
    }

    public static function toRGB($values)
    {
        list($X,$Y,$Z) = $values;

        $var_X = $X / 100;
        $var_Y = $Y / 100;
        $var_Z = $Z / 100;

        $var_R = $var_X *  3.2406 + $var_Y * -1.5372 + $var_Z * -0.4986;
        $var_G = $var_X * -0.9689 + $var_Y *  1.8758 + $var_Z *  0.0415;
        $var_B = $var_X *  0.0557 + $var_Y * -0.2040 + $var_Z *  1.0570;

        if ( $var_R > 0.0031308 ) $var_R = 1.055 * pow( $var_R, ( 1 / 2.4 ) ) - 0.055;
        else                      $var_R = 12.92 * $var_R;
        if ( $var_G > 0.0031308 ) $var_G = 1.055 * pow( $var_G, ( 1 / 2.4 ) ) - 0.055;
        else                      $var_G = 12.92 * $var_G;
        if ( $var_B > 0.0031308 ) $var_B = 1.055 * pow( $var_B, ( 1 / 2.4 ) ) - 0.055;
        else                      $var_B = 12.92 * $var_B;

        $R = $var_R * 255;
        $G = $var_G * 255;
        $B = $var_B * 255;

        return [$R,$G,$B];
    }

    public static function toYxy($values)
    {
        list($X,$Y,$Z) = $values;

        //X from 0 to  95.047
        //Y from 0 to 100.000
        //Z from 0 to 108.883

        $Y = $Y;
        $x = $X / ( $X + $Y + $Z );
        $y = $Y / ( $X + $Y + $Z );

        return [$Y,$x,$y];
    }

    public static function toHunterLab($values)
    {
        list($X,$Y,$Z) = $values;

        $HL = 10 * sqrt( $Y );
        $Ha = 17.5 * ( ( ( 1.02 * $X ) - $Y ) / sqrt( $Y ) );
        $Hb = 7 * ( ( $Y - ( 0.847 * $Z ) ) / sqrt( $Y ) );

        return [$HL,$Ha,$Hb];
    }

    public static function toCIELab($values)
    {
        list($Y,$X,$Z) = $values;

        $var_X = $X / static::$ref_X;
        $var_Y = $Y / static::$ref_Y;
        $var_Z = $Z / static::$ref_Z;

        if ( $var_X > 0.008856 ) $var_X = pow($var_X , ( 1/3 ));
        else                     $var_X = ( 7.787 * $var_X ) + ( 16 / 116 );
        if ( $var_Y > 0.008856 ) $var_Y = pow($var_Y , ( 1/3 ));
        else                     $var_Y = ( 7.787 * $var_Y ) + ( 16 / 116 );
        if ( $var_Z > 0.008856 ) $var_Z = pow($var_Z , ( 1/3 ));
        else                     $var_Z = ( 7.787 * $var_Z ) + ( 16 / 116 );

        $CIEL = ( 116 * $var_Y ) - 16;
        $CIEa = 500 * ( $var_X - $var_Y );
        $CIEb = 200 * ( $var_Y - $var_Z );

        return [$CIEL,$CIEa,$CIEb];
    }

    public static function toCIELuv($values)
    {
        list($X,$Y,$Z) = $values;

        $var_U = ( 4 * $X ) / ( $X + ( 15 * $Y ) + ( 3 * $Z ) );
        $var_V = ( 9 * $Y ) / ( $X + ( 15 * $Y ) + ( 3 * $Z ) );

        $var_Y = $Y / 100;
        if ( $var_Y > 0.008856 ) $var_Y = pow($var_Y , ( 1/3 ));
        else                     $var_Y = ( 7.787 * $var_Y ) + ( 16 / 116 );

        $ref_X = static::$ref_X;
        $ref_Y = static::$ref_Y;
        $ref_Z = static::$ref_Z;

        $ref_U = ( 4 * $ref_X ) / ( $ref_X + ( 15 * $ref_Y ) + ( 3 * $ref_Z ) );
        $ref_V = ( 9 * $ref_Y ) / ( $ref_X + ( 15 * $ref_Y ) + ( 3 * $ref_Z ) );

        $CIEL = ( 116 * $var_Y ) - 16;
        $CIEu = 13 * $CIEL * ( $var_U - $ref_U );
        $CIEv = 13 * $CIEL * ( $var_V - $ref_V );

        return [$CIEL,$CIEu,$CIEv];
    }

    public static function toHEX($values)
    {
        $color = $values;
        $color = static::toRGB($color);
        $color = RGB::toHEX($color);
        return $color;
    }

    public static function toCIELch($values)
    {
        $color = $values;
        $color = static::toCIELab($color);
        $color = CIELab::toCIELch($color);
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
