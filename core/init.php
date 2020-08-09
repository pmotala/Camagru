<?php
session_start();

$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => 'localhost',
        'username' => 'root',
        'password' => 'qwerty',
        'db_name' => 'camagru',
        'db_options' => array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        )
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800
    )
);

spl_autoload_register(function($class) {
    require_once 'classes/'.$class.'.class.php';
});

require_once 'functions/sanitize.php';
require_once 'functions/alert.php';
require_once 'functions/showimage.php';
require_once 'functions/showedits.php';
require_once 'functions/imageprocessing.php';


if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name')))
{
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashCheck = Database::getInstance()->get('user_session', array('HASH', '=', $hash));

    if ($hashCheck->getCount())
    {
        $user = new User($hashCheck->first()->USERID);
        $user->login();
    }
}