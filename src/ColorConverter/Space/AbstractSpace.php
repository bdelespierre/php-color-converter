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

use BadMethodCallException;
use DomainException;

abstract class AbstractSpace implements SpaceInterface
{
    /**
     * @param mixed ...$values
     *
     * @return ColorConverter\Color
     */
    public static function getColor(...$values)
    {
        if (static::class == self::class) {
            throw new BadMethodCallException("you are calling an abstraction");
        }

        return new \ColorConverter\Color(static::class, ...$values);
    }

    /**
     * @param  array  $values
     *
     * @return boolean
     */
    public static function validate(array $values)
    {
        $boundaries = static::getSpaceBoundaries();
        $length = max(array_map('count', $boundaries));

        if (count($values) < $length) {
            return false;
        }

        for ($i=0; $i<$length; $i++) {
            if ($values[$i] < $boundaries[0][$i]) {
                return false;
            }

            if ($values[$i] > $boundaries[1][$i]) {
                return false;
            }
        }

        return true;
    }
}
