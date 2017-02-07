<?php

require __DIR__ . '/vendor/autoload.php';

echo ColorConverter\Space\RGB::getColor(255, 255, 255)->toHEX();
