<?php

/**
 * Inpsyde_Plugin_Test is devloped to utilize brain monkey tools 
 * for writing my plugins phpunit test isolating the test from Wordpress load
 * 
 * Author: Raheleh Yoosefzadeh
 */

namespace MyTask;

use PHPUnit\Framework\TestCase;
use Brain\Monkey;
use Brain\Monkey\Functions;


class Inpsyde_Test extends TestCase
{

    // Adds Mockery expectations to the PHPUnit assertions count.

    /**
     * Summary of setUp
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        Monkey\setUp();
    }
    /**
     * Summary of tearDown
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
        Monkey\tearDown();
    }
    /**
     * Summary of test_register_endpoint
     * @return void
     */
    public function test_register_endpoint()
    {
        define('EP_ROOT', '/');

        Monkey\setUp();

        Functions\expect('add_rewrite_endpoint')
            ->once()
            ->with('my-lovely-users-table', EP_ROOT);

        Functions\expect('flush_rewrite_rules')
            ->once()
            ->with();



        $plugin = new Inpsyde();

        $plugin->register_endpoint();

        Monkey\tearDown();
    }

    /**
     * Summary of test_add_hooks
     * @return void
     */
    public function test_add_hooks()
    {
        $plugin = new Inpsyde();
        Monkey\setUp();

        Functions\expect('add_action')->once()->with('init', [$plugin, 'register_endpoint']);
        Functions\expect('add_action')->once()->with('wp_enqueue_scripts', [$plugin, 'enqueue_scripts']);
        Functions\expect('add_action')->once()->with('rest_api_init', [$plugin, 'register_ajax_route']);
        Functions\expect('add_filter')->once()->with('query_vars', [$plugin, 'add_query_vars']);
        Functions\expect('add_action')->once()->with('template_include', [$plugin, 'display_react_component']);

        $plugin->add_hooks();
        Monkey\tearDown();
    }

    /**
     * Summary of test_init
     * @return void
     */
    public function test_init()
    {
        $plugin = new Inpsyde();
        Monkey\setUp();
        Functions\expect('add_action')
            ->once()
            ->with('init', [$plugin, 'register_endpoint']);

        Functions\expect('add_action')
            ->once()
            ->with('wp_enqueue_scripts', [$plugin, 'enqueue_scripts']);

        Functions\expect('add_action')
            ->once()
            ->with('rest_api_init', [$plugin, 'register_ajax_route']);

        Functions\expect('add_action')
            ->once()
            ->with('template_include', [$plugin, 'display_react_component']);

        Functions\expect('add_filter')
            ->once()
            ->with('query_vars', [$plugin, 'add_query_vars']);


        $plugin->init();
        Monkey\tearDown();
    }
    /**
     * Summary of test_register_ajax_route
     * @return void
     */
    public function test_register_ajax_route()
    {
        $plugin = new Inpsyde();
        Monkey\setUp();
        Functions\expect('register_rest_route')
            ->once()
            ->with(
                'inpsyde/v1',
                '/details',
                array(
                    'methods' => 'GET',
                    'callback' => array($plugin, 'get_details'),
                )
            );

        $plugin->register_ajax_route();
        Monkey\tearDown();
    }
}
