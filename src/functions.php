<?php

declare(strict_types=1);

if (!function_exists('str')) {
    function str(): \Phico\Support\Str
    {
        return new \Phico\Support\Str();
    }
}
