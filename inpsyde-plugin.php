<?php

/**
 * Plugin Name: Inpsyde Plugin
 * Plugin URI:  https://github.com/rahelehyoosefzadeh/inpsyde/
 * Description: This plugin  displays lovely users table as a react component in an endpoint
 *              and displays the details for one selected user at a time - that's my Inpsyde task.
 * Author:      Raheleh Yoosefzadeh
 * Version:     1.0.0
 * Author URI:  https://github.com/rahelehyoosefzadeh
 * License:     GPLv3+
 * License URI: ./license.txt
 */

declare(strict_types=1);

namespace MyTask;

if (! defined('ABSPATH')) {
    echo 'Access denied!';
    exit;
}

if (! version_compare(phpversion(), '5.3.0', '>=')) {
    echo esc_html('My Inpsyde Plugin requires <strong>PHP 5.3 + </strong>.<br>');
    echo esc_html('Your Installation PHP is ' . phpversion());
    exit;
}

require 'vendor/autoload.php';

require_once 'includes/Inpsyde.php';

// Instantiate the plugin class and initialize the plugin to work
$plugin = new Inpsyde();
$plugin->init();
