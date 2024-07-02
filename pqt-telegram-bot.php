<?php

/**
 * @package PQT_TELEGRAM_BOT
 */

use PQT_TELEGRAM_BOT\Lib\Menu\AdminMenu;
use PQT_TELEGRAM_BOT\Lib\Service\InitService;

/*
Plugin Name: PQT TELEGRAM BOT
Plugin URI: https://github.com/trungro12/pqt-telegram-bot
Description: Send Message To Telegram, Manage API for Website to Send Message from your API to Telegram
Version: 1.0.0
Requires at least: 5.8
Requires PHP: 5.6.20
Author: Trung Pham
Author URI: https://github.com/trungro12/
License: GPLv2 or later
*/

// Make sure we don't expose any info if called directly
if (!function_exists('add_action')) {
	echo 'Can not run Plugin!';
	exit;
}

define("PQT_TELEGRAM_BOT_NAME", 'PQT TELEGRAM BOT');
define("PQT_TELEGRAM_BOT_NAMESPACE", 'PQT_TELEGRAM_BOT');
define("PQT_TELEGRAM_BOT_PLUGIN_DIR", rtrim(plugin_dir_path(__FILE__), DIRECTORY_SEPARATOR));
require_once 'autoload.php';

add_action('init', [InitService::class, 'init']);
add_action('admin_init', [InitService::class, 'adminInit']);

AdminMenu::adminInit();