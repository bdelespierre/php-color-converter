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

namespace Color;

abstract class Space
{
	public static function getSpaceBoundaries()
	{
		throw new \BadMethodCallException("not implemented");
	}

	public static function getLabels()
	{
		throw new \BadMethodCallException("not implemented");
	}

	public static function validate(array $values)
	{
		throw new \BadMethodCallException("not implemented");
	}

	protected static function interpolateArgs($num, $args)
	{
		$values = $num == 1 && is_array($args[0]) ? $args[0] : $args;

		if (!static::validate(array_values($values)))
			throw new \DomainException("invalid " . get_called_class() . " color values: " . implode(',', $values));

		return $values;
	}

	public static function getColor()
	{
		if (__CLASS__ == $class = get_called_class())
			throw new \DomainException("cannot create a colorspace from $class");

		$values = static::interpolateArgs(func_num_args(), func_get_args());
		$space  = substr($class, strrpos($class, '\\') +1); // strip namespace

		return new Color($space, $values);
	}
}