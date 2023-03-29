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

require 'vendor/autoload.php';

require_once 'includes/Inpsyde.php';

// Instantiate the plugin class and initialize the plugin to work
$plugin = new Inpsyde();
$plugin->init();
