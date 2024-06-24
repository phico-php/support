<?php

declare(strict_types=1);

if (!function_exists('str')) {
    function str(): \Phico\Str
    {
        return new \Phico\Str();
    }
}
if (!function_exists('dd')) {
    function dd()
    {
        do {
            ob_end_clean();
        } while (ob_get_level() > 0);

        echo '<pre>';
        var_dump(func_get_args());
        echo '</pre>';
        exit();
    }
}
