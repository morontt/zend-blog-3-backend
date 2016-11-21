<?php

$finder = Symfony\CS\Finder::create()
    ->in([
        __DIR__ . '/spec',
        __DIR__ . '/src',
    ])
;

$config = Symfony\CS\Config::create()
    ->fixers([
        '-phpdoc_params',
        '-phpdoc_short_description',
        '-pre_increment',
        '-spaces_cast',
        'concat_with_spaces',
        'ordered_use',
    ])
    ->finder($finder)
;

return $config;
