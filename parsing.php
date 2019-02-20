<?php
// Version
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

function decode($str){
    
    
    $dict_utf = array(
    'u0410'=> 'А', 'u0430'=> 'а',
    'u0411'=> 'Б', 'u0431'=> 'б',
    'u0412'=> 'В', 'u0432'=> 'в',
    'u0413'=> 'Г', 'u0433'=> 'г',
    'u0414'=> 'Д', 'u0434'=> 'д',
    'u0415'=> 'Е', 'u0435'=> 'е',
    'u0401'=> 'Ё', 'u0451'=> 'ё',
    'u0416'=> 'Ж', 'u0436'=> 'ж',
    'u0417'=> 'З', 'u0437'=> 'з',
    'u0418'=> 'И', 'u0438'=> 'и',
    'u0419'=> 'Й', 'u0439'=> 'й',
    'u041a'=> 'К', 'u043a'=> 'к',
    'u041b'=> 'Л', 'u043b'=> 'л',
    'u041c'=> 'М', 'u043c'=> 'м',
    'u041d'=> 'Н', 'u043d'=> 'н',
    'u041e'=> 'О', 'u043e'=> 'о',
    'u041f'=> 'П', 'u043f'=> 'п',
    'u0420'=> 'Р', 'u0440'=> 'р',
    'u0421'=> 'С', 'u0441'=> 'с',
    'u0422'=> 'Т', 'u0442'=> 'т',
    'u0423'=> 'У', 'u0443'=> 'у',
    'u0424'=> 'Ф', 'u0444'=> 'ф',
    'u0425'=> 'Х', 'u0445'=> 'х',
    'u0426'=> 'Ц', 'u0446'=> 'ц',
    'u0427'=> 'Ч', 'u0447'=> 'ч',
    'u0428'=> 'Ш', 'u0448'=> 'ш',
    'u0429'=> 'Щ', 'u0449'=> 'щ',
    'u042a'=> 'Ъ', 'u044a'=> 'ъ',
    'u042d'=> 'Ы', 'u044b'=> 'ы',
    'u042c'=> 'Ь', 'u044c'=> 'ь',
    'u042d'=> 'Э', 'u044d'=> 'э',
    'u042e'=> 'Ю', 'u044e'=> 'ю',
    'u042f'=> 'Я', 'u044f'=> 'я',
    '\\'=>''
    );
    
    $find = array();
    $repl = array();
    foreach($dict_utf as $a => $b){
        $find[] = $a;
        $repl[] = $b;
    }
    
    
    return str_replace($find, $repl, $str);

}

define('VERSION', '1.5.3.1');

// Configuration
require_once('admin/config.php');

// Install
if (!defined('DIR_APPLICATION')) {
    header('Location: install/index.php');
    exit;
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Application Classes
require_once(DIR_SYSTEM . 'library/cart/customer.php');
require_once(DIR_SYSTEM . 'library/cart/currency.php');
require_once(DIR_SYSTEM . 'library/cart/tax.php');
require_once(DIR_SYSTEM . 'library/cart/weight.php');
require_once(DIR_SYSTEM . 'library/cart/length.php');
require_once(DIR_SYSTEM . 'library/cart/cart.php');
require_once(DIR_SYSTEM . 'library/cart/affiliate.php');
// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Config
$config = new Config();
$registry->set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

// Store
if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
    $store_query = $db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`ssl`, 'www.', '') = '" . $db->escape('https://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
} else {
    $store_query = $db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`url`, 'www.', '') = '" . $db->escape('http://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
}

if ($store_query->num_rows) {
    $config->set('config_store_id', $store_query->row['store_id']);
} else {
    $config->set('config_store_id', 0);
}

// Settings
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0' OR store_id = '" . (int)$config->get('config_store_id') . "' ORDER BY store_id ASC");


foreach ($query->rows as $setting) {
    if (!$setting['serialized']) {
        
       $config->set($setting['key'], decode($setting['value']));
    } else {
        $value = decode($setting['value']);
        @$config->set($setting['key'], unserialize(decode($setting['value'])));
    }
}

if (!$store_query->num_rows) {
    $config->set('config_url', HTTP_SERVER);
    $config->set('config_ssl', HTTPS_SERVER);
}

// Url
$url = new Url($config->get('config_url'), $config->get('config_use_ssl') ? $config->get('config_ssl') : $config->get('config_url'));
$registry->set('url', $url);

// Log
$log = new Log($config->get('config_error_filename'));
$registry->set('log', $log);

function error_handler($errno, $errstr, $errfile, $errline) {
    global $log, $config;

    switch ($errno) {
        case E_NOTICE:
        case E_USER_NOTICE:
            $error = 'Notice';
            break;
        case E_WARNING:
        case E_USER_WARNING:
            $error = 'Warning';
            break;
        case E_ERROR:
        case E_USER_ERROR:
            $error = 'Fatal Error';
            break;
        default:
            $error = 'Unknown';
            break;
    }

    if ($config->get('config_error_display')) {
        echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
    }

    if ($config->get('config_error_log')) {
        $log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
    }

    return true;
}

// Error Handler
set_error_handler('error_handler');

// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$response->setCompression($config->get('config_compression'));
$registry->set('response', $response);

// Cache
$cache = new Cache('file');
$registry->set('cache', $cache);

// Session
$session = new Session();
$registry->set('session', $session);

// Language Detection
$languages = array();

$query = $db->query("SELECT * FROM " . DB_PREFIX . "language WHERE status = '1'");

foreach ($query->rows as $result) {
    $languages[$result['code']] = $result;
}

$detect = '';

if (isset($request->server['HTTP_ACCEPT_LANGUAGE']) && ($request->server['HTTP_ACCEPT_LANGUAGE'])) {
    $browser_languages = explode(',', $request->server['HTTP_ACCEPT_LANGUAGE']);

    foreach ($browser_languages as $browser_language) {
        foreach ($languages as $key => $value) {
            if ($value['status']) {
                $locale = explode(',', $value['locale']);

                if (in_array($browser_language, $locale)) {
                    $detect = $key;
                }
            }
        }
    }
}

if (isset($session->data['language']) && array_key_exists($session->data['language'], $languages) && $languages[$session->data['language']]['status']) {
    $code = $session->data['language'];
} elseif (isset($request->cookie['language']) && array_key_exists($request->cookie['language'], $languages) && $languages[$request->cookie['language']]['status']) {
    $code = $request->cookie['language'];
} elseif ($detect) {
    $code = $detect;
} else {
    $code = $config->get('config_language');
}

if (!isset($session->data['language']) || $session->data['language'] != $code) {
    $session->data['language'] = $code;
}

if (!isset($request->cookie['language']) || $request->cookie['language'] != $code) {
    setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $request->server['HTTP_HOST']);
}

$config->set('config_language_id', $languages[$code]['language_id']);
$config->set('config_language', $languages[$code]['code']);

// Language

$language = new Language($languages[$code]['directory']);
//$language->load($languages[$code]['filename']);
$registry->set('language', $language);

// Document
$registry->set('document', new Document());

// Customer
$registry->set('customer', new Cart\Customer($registry));

// Affiliate
$registry->set('affiliate', new Cart\Affiliate($registry));

if (isset($request->get['tracking']) && !isset($request->cookie['tracking'])) {
    setcookie('tracking', $request->get['tracking'], time() + 3600 * 24 * 1000, '/');
}

// Currency
$registry->set('currency', new Cart\Currency($registry));

// Tax
$registry->set('tax', new Cart\Tax($registry));

// Weight
$registry->set('weight', new Cart\Weight($registry));

// Length
$registry->set('length', new Cart\Length($registry));

// Cart
$registry->set('cart', new Cart\Cart($registry));

//  Encryption
$registry->set('encryption', new Encryption($config->get('config_encryption')));

$config->set('import_pack', true);

// Front Controller
$controller = new Front($registry);


$action = new Action('dataexchange/import1c');


// Dispatch
$controller->dispatch($action, new Action('error/not_found'));

// Output
$response->output();
