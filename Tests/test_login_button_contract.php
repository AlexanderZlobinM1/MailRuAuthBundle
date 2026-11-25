<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$source = file_get_contents($root.'/EventListener/MailRuAuthSubscriber.php');
$script = file_get_contents($root.'/Resources/js/auth-alternative-coordinator.js');
if (false === $source || false === $script) {
    throw new RuntimeException('Unable to read MailRuAuthSubscriber.php');
}

$required = [
    'shared alternative-login data attribute' => 'data-mautic-auth-alternative="1"',
    'shared alternative-login class' => 'mautic-auth-alternative-button',
    'participating wrapper marker' => 'data-mautic-auth-alternative-block="1"',
    'localized wrapper label' => 'data-mautic-auth-separator-label',
    'coordinator asset' => 'auth-alternative-coordinator.js',
];

foreach ($required as $name => $needle) {
    if (false === strpos($source, $needle)) {
        throw new RuntimeException('Missing '.$name.': '.$needle);
    }
}

$forbidden = [
    'local separator class' => 'mailruauth-sep',
];

foreach ($forbidden as $name => $needle) {
    if (false !== strpos($source, $needle)) {
        throw new RuntimeException('MailRuAuthBundle still emits a '.$name.': '.$needle);
    }
}

foreach ([
    'wrapper DOM selector' => '[data-mautic-auth-alternative-block="1"]',
    'separator DOM selector' => '[data-mautic-auth-alternative-separator="1"]',
    'singleton coordinator' => 'MauticAuthAlternativeCoordinator',
    'DOM-order top wrapper' => 'var topBlock    = blocks[0]',
    'duplicate cleanup' => 'removeNode(candidate)',
    'separator relocation' => 'insertBefore(separator, topBlock)',
    'repeat DOM synchronization' => 'MutationObserver',
] as $name => $needle) {
    if (false === strpos($script, $needle)) {
        throw new RuntimeException('Missing '.$name.' contract: '.$needle);
    }
}

foreach (['en_US', 'ru', 'sr_RS'] as $locale) {
    $translationPath = $root.'/Translations/'.$locale.'/messages.ini';
    $translation = is_file($translationPath) ? parse_ini_file($translationPath) : false;
    if (!is_array($translation)) {
        throw new RuntimeException('Missing translation file for '.$locale);
    }

    $expected = [
        'en_US' => ['or', 'Sign in with Mail.ru'],
        'ru' => ['или', 'Войти через Mail.ru'],
        'sr_RS' => ['ili', 'Prijava putem Mail.ru'],
    ][$locale];
    if (($translation['mautic.integration.mailruauth.login_separator'] ?? null) !== $expected[0]
        || ($translation['mautic.integration.mailruauth.login_button'] ?? null) !== $expected[1]) {
        throw new RuntimeException('Unexpected login-button translations for '.$locale);
    }
}

fwrite(STDOUT, "MailRu shared login-button contract checks passed.\n");
