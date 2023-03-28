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
     * Summary of test_registerEndpoint
     * @return void
     */
    public function test_registerEndpoint()
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

        $plugin->registerEndpoint();

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

        Functions\expect('add_action')->once()->with('init', [$plugin, 'registerEndpoint']);
        Functions\expect('add_action')->once()->with('wp_enqueue_scripts', [$plugin, 'enqueueScripts']);
        Functions\expect('add_action')->once()->with('rest_api_init', [$plugin, 'registerAjaxRoute']);
        Functions\expect('add_filter')->once()->with('query_vars', [$plugin, 'addQueryVars']);
        Functions\expect('add_action')->once()->with('template_include', [$plugin, 'displayReactComponent']);

        $plugin->addHooks();
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
            ->with('init', [$plugin, 'registerEndpoint']);

        Functions\expect('add_action')
            ->once()
            ->with('wp_enqueue_scripts', [$plugin, 'enqueueScripts']);

        Functions\expect('add_action')
            ->once()
            ->with('rest_api_init', [$plugin, 'registerAjaxRoute']);

        Functions\expect('add_action')
            ->once()
            ->with('template_include', [$plugin, 'displayReactComponent']);

        Functions\expect('add_filter')
            ->once()
            ->with('query_vars', [$plugin, 'addQueryVars']);


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
                    'callback' => array($plugin, 'fetchDetails'),
                )
            );

        $plugin->registerAjaxRoute();
        Monkey\tearDown();
    }
}
