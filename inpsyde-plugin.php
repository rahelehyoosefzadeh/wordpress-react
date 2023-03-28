<?php

namespace MyTask;

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

require_once 'includes/Inpsyde.php';

// Instantiate the plugin class
add_action('plugins_loaded', function () {
    $plugin = new Inpsyde();
    $plugin->init();
});
