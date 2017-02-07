<?php

namespace ColorConverter\Space;

interface SpaceInterface
{
    public static function getSpaceBoundaries();

    public static function getLabels();

    public static function validate(array $values);
}
