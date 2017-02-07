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

namespace ColorConverter;

use ArrayAccess;
use BadMethodCallException;
use DomainException;
use JsonSerializable;
use LogicException;
use OutOfRangeException;

class Color implements ArrayAccess, JsonSerializable
{
    /**
     * @var string
     */
    protected $space;

    /**
     * @var array
     */
    protected $values;

    /**
     * @param string|object $space
     * @param mixed ...$values
     *
     * @throws DomainException if either the space or the color values are unsupported or invalid
     */
    public function __construct($space, ...$values)
    {
        if (is_object($space)) {
            $space = get_class($space);
        }

        if (!static::isSupported($space)) {
            throw new DomainException("unsupported color space: $space");
        }

        if (!$space::validate($values)) {
            throw new DomainException("invalid color values: " . json_encode($values));
        }

        $this->space = $space;
        $this->values = $values;
    }

    /**
     * Allows use of transformation
     *
     * @example $color->toHEX();
     *
     * @param  string $method
     * @param  array  $args
     *
     * @throws BadMethodCallException If method names doesn't begin with 'to'
     *
     * @return Color
     */
    public function __call($method, $args)
    {
        if (!preg_match('/^to(\w+)$/', $method, $matches)) {
            throw new BadMethodCallException("unsupported method: $method");
        }

        list(,$newSpace) = $matches;
        return $this->to($newSpace);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $space = $this->space;
        return array_combine($space::getLabels(), $this->values);
    }

    /**
     * @param  string|object $newSpace
     *
     * @return Color
     */
    public function to($newSpace)
    {
        if ($newSpace == $this->space) {
            return $this;
        }

        if (!is_callable($fn = "{$this->space}::to{$newSpace}")) {
            throw new DomainException("cannot convert to: $newSpace");
        }

        return new static($newSpace, ...$fn($this->values));
    }

    /**
     * Calculates the euclidian distance between current color and another color
     *
     * @param  Color $other
     *
     * @return float
     */
    public function delatE(self $other)
    {
        list($L1,$a1,$b1) = $this->to(Space\CIELab::class)->values;
        list($L2,$a2,$b2) = $other->to(Space\CIELab::class)->values;

        // euclidian distance between Lab1 & Lab2 colors
        return sqrt(
            ($L2 - $L1) * ($L2 - $L1) +
            ($a2 - $a1) * ($a2 - $a1) +
            ($b2 - $b1) * ($b2 - $b1)
        );
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->values;
    }

    /**
     * @return string
     */
    public function getSpace()
    {
        return $this->space;
    }

    /**
     * @param int $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->values[$offset]);
    }

    /**
     * @param int $offset
     *
     * @return bool
     */
    public function offsetGet($offset)
    {
        if (!isset($this->values[$offset])) {
            throw new OutOfRangeException("illegal offset: $offset");
        }

        return $this->values[$offset];
    }

    /**
     * @param int $offset
     * @param float $value
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (!isset($this->values[$offset])) {
            throw new OutOfRangeException("illegal offset: $offset");
        }

        $this->values[$offset] = $value;
    }

    /**
     * @throws LogicException always
     */
    public function offsetUnset($offset)
    {
        throw new LogicException("cannot unset a color offset");
    }

    /**
     * Tells whenever a space is supported or not, some aliases are accepted
     *
     * @param string|object &$space
     *
     * @return string|false
     */
    public static function isSupported(&$space)
    {
        static $memo = [];

        if (isset($memo[$space])) {
            return $space = $memo[$space];
        }

        if (class_exists($space, true) && in_array(Space\SpaceInterface::class, class_implements($space))) {
            return $memo[$space] = $space = $space;
        }

        // aliasing
        switch ($space) {
            case "Hunter-Lab":
            case "HLab":
                $space = "HunterLab";
                break;

            case "CIE-L*ab":
            case "Lab":
            case "L*a*b*":
            case "CIELAB":
                $space = "CIELab";
                break;

            case "CIE-L*CH°":
            case "LCH":
            case "L*c*h*":
                $space = "CIELCH";
                break;

            case "CIE-L*uv":
            case "CIELUV":
            case "L*u*v*":
                $space = "CIELuv";
                break;

            case 'Hex':
                $space = "HEX";
                break;
        }

        $space = "\\ColorConverter\\Space\\{$space}";

        return class_exists($space, true) && in_array(Space\SpaceInterface::class, class_implements($space))
            ? $memo[$space] = $space
            : false;
    }
}
