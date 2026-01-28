<?php

/*
 * This document has been generated with
 * https://mlocati.github.io/php-cs-fixer-configurator/#version:2.16.4|configurator
 * you can change this configuration by importing this file.
 */
$config = new PhpCsFixer\Config();
return $config
    ->setParallelConfig(\PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PER-CS' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@autoPHPMigration' => true,
        '@autoPHPMigration:risky' => true,
//        '@PHP7x1Migration' => true,
//        '@PHP7x1Migration:risky' => true,
        'phpdoc_order' => true,
        'ordered_imports' => true,
        'array_syntax' => ['syntax' => 'short'],
        'global_namespace_import' => true,
    ])
    ->setFinder(PhpCsFixer\Finder::create()
        ->in(__DIR__ . '/src')
        ->in(__DIR__ . '/tests')
    );