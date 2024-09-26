<?php

$finder = (new PhpCsFixer\Finder())
    ->in([
        __DIR__ . '/spec',
        __DIR__ . '/src',
        __DIR__ . '/migrations',
        __DIR__ . '/config',
        __DIR__ . '/web',
    ])
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'concat_space' => ['spacing' => 'one'],
        'increment_style' => ['style' => 'post'],
        'phpdoc_align' => ['tags' => []],
        'cast_spaces' => ['space' => 'none'],
        'phpdoc_summary' => false,
        'yoda_style' => false,
        'phpdoc_order' => true,
        'no_unused_imports' => true,
        'ordered_imports' => true,
        'no_superfluous_phpdoc_tags' => false,
    ])
    ->setFinder($finder)
    ->setCacheFile(__DIR__ . '/var/.php_cs.cache')
;
