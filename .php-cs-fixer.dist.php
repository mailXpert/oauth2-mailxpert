<?php

/*
 * This document has been generated with
 * https://mlocati.github.io/php-cs-fixer-configurator/#version:2.16.4|configurator
 * you can change this configuration by importing this file.
 */
$config = new PhpCsFixer\Config();
return $config
    ->registerCustomFixers(new PhpCsFixerCustomFixers\Fixers())
    ->setParallelConfig(\PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setRiskyAllowed(true)
    ->setRules([
        '@auto' => true,
        '@auto:risky' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'phpdoc_order' => true,
        'ordered_imports' => true,
        'array_syntax' => ['syntax' => 'short'],
        'global_namespace_import' => true,
        'declare_strict_types' => true,
        \PhpCsFixerCustomFixers\Fixer\CommentSurroundedBySpacesFixer::name() => true,
        \PhpCsFixerCustomFixers\Fixer\ConstructorEmptyBracesFixer::name() => true,
        \PhpCsFixerCustomFixers\Fixer\MultilineCommentOpeningClosingAloneFixer::name() => true,
        \PhpCsFixerCustomFixers\Fixer\NoDoctrineMigrationsGeneratedCommentFixer::name() => true,
        \PhpCsFixerCustomFixers\Fixer\NoUselessDoctrineRepositoryCommentFixer::name() => true,
        \PhpCsFixerCustomFixers\Fixer\NoUselessCommentFixer::name() => true,
        \PhpCsFixerCustomFixers\Fixer\NoUselessDirnameCallFixer::name() => true,
        \PhpCsFixerCustomFixers\Fixer\EmptyFunctionBodyFixer::name() => true,
        \PhpCsFixerCustomFixers\Fixer\NoDuplicatedArrayKeyFixer::name() => true,
        \PhpCsFixerCustomFixers\Fixer\NoDuplicatedImportsFixer::name() => true,
        \PhpCsFixerCustomFixers\Fixer\NoTrailingCommaInSinglelineFixer::name() => true,
        \PhpCsFixerCustomFixers\Fixer\NoUselessStrlenFixer::name() => true,
        \PhpCsFixerCustomFixers\Fixer\PhpdocTypesTrimFixer::name() => true,
    ])
    ->setFinder(PhpCsFixer\Finder::create()
        ->in(__DIR__ . '/src')
        ->in(__DIR__ . '/tests')
    );