<?
ob_start();
session_start();

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

date_default_timezone_set('Europe/Moscow');
Header("Pragma: no-cache");

define('DBHOST', 'openserver');
define('DBUSER', 'root'); // юзер бд
define('DBPASS', ''); // пароль бд
define('DBNAME', 'elj'); // имя бд
define('DBPORT', '3306'); // не менять
require_once('inc/class_pdo.php');

// IP посетителя
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
// Определение реального браузера
$ua = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

define('ROOT', 'http://' . $_SERVER['HTTP_HOST']);
// Кодировка
mb_internal_encoding('UTF-8');

// Функции
require_once('inc/func_pdo.php');

//Работа с текстом
require_once('inc/output_text.php');	

// Security
require_once("inc/security.php");

?>