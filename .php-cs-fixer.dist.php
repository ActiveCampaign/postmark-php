<?php

/**
 * PHP CS Fixer Configuration file for ActiveCampaign.
 *
 * This is a simple but highly opinionated rule set for `php-cs-fixer` which is
 * fully compliant with the [PER Coding Style 2.0 Standard](https://www.php-fig.org/per/coding-style/).
 *
 * To apply these fixes run: `./vendor/bin/php-cs-fixer fix <path/to/file/or/directory>`
 *
 * @see https://activecampaign.atlassian.net/wiki/spaces/DEV/pages/24051783/ActiveCampaign+PHP+Coding+Style+Standards
 * @see https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/ruleSets/index.rst
 */
$finder = PhpCsFixer\Finder::create()->in(__DIR__);
$config = new PhpCsFixer\Config();

return $config->setRules([
    '@PhpCsFixer' => true,
    //'@PHP82Migration' => true,
    'concat_space' => ['spacing' => 'one'], // This is required by [PER coding style rule 6.2 binary operators](https://www.php-fig.org/per/coding-style/#62-binary-operators)
    'global_namespace_import' => [
        'import_classes' => true,
        'import_constants' => false,
        'import_functions' => false,
    ],
]);