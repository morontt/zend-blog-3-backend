<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/spec',
        __DIR__ . '/src',
    ])
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'concat_space' => ['spacing' => 'one'],
        'increment_style' => ['style' => 'post'],
        'phpdoc_align' => ['tags' => []],
        'phpdoc_summary' => false,
        'yoda_style' => false,
        'phpdoc_order' => true,
    ])
    ->setFinder($finder)
    ->setCacheFile(__DIR__ . '/var/cache/.php_cs.cache')
;
