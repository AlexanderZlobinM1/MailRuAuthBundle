<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$source = file_get_contents($root.'/EventListener/MailRuAuthSubscriber.php');
if (false === $source) {
    throw new RuntimeException('Unable to read MailRuAuthSubscriber.php');
}

$required = [
    'shared alternative-login data attribute' => 'data-mautic-auth-alternative="1"',
    'shared alternative-login class' => 'mautic-auth-alternative-button',
];

foreach ($required as $name => $needle) {
    if (false === strpos($source, $needle)) {
        throw new RuntimeException('Missing '.$name.': '.$needle);
    }
}

$forbidden = [
    'local separator class' => 'mailruauth-sep',
    'local separator translation lookup' => 'mautic.integration.mailruauth.login_separator',
];

foreach ($forbidden as $name => $needle) {
    if (false !== strpos($source, $needle)) {
        throw new RuntimeException('MailRuAuthBundle still emits a '.$name.': '.$needle);
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
