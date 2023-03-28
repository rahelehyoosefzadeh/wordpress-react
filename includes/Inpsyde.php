<?php

namespace MyTask;

/**
 * Summary of Inpsyde Plugin
 * the main Class of plugin
 * Author:      Raheleh Yoosefzadeh
 * Version:     1.0.0
 * Author URI:  https://github.com/rahelehyoosefzadeh
 * License:     GPLv3+
 * License URI: ./license.txt
 */
class Inpsyde
{
    /**
     * Private varibales: apiUrl, customEndpoint, detailsPath
     * @var string
     */
    private $apiUrl = 'https://jsonplaceholder.typicode.com/users/';
    private $customEndpoint =  'my-lovely-users-table';
    private $detailsPath = 'details';

    /**
     * Summary of init
     * Method init is used to initialize and instantiate the Inpsyde Plugin Class
     * @return void
     */
    static function init()
    {
        $instance = new Inpsyde();
        $instance->add_hooks();
    }

    /**
     * Summary of add_hooks
     * hooks are used in this method inorder to make the working
     * @return void
     */
    public function add_hooks()
    {
        add_action('init', [$this, 'register_endpoint']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('rest_api_init', [$this, 'register_ajax_route']);
        add_action('template_include', [$this, 'display_react_component']);
        add_filter('query_vars', [$this, 'add_query_vars']);
    }

    /**
     * Summary of register_endpoint
     * customEndpoint (given `my-lovely-users-table` )
     * is added at the / address of the website.and
     * flushes rewrite rules so that it is not required
     * to save permalinks in admin dashboard
     * @return void
     */
    public function register_endpoint()
    {
        add_rewrite_endpoint($this->customEndpoint, EP_ROOT);
        flush_rewrite_rules();
    }

    /**
     * Summary of add_query_vars
     * possible query vars of the plugin are set
     * to the $vars array and then filtered in the query_vars hook
     * @param mixed $vars
     * @return array
     */
    public function add_query_vars($vars): array
    {
        $vars[] = $this->customEndpoint;
        $vars[] = $this->detailsPath;
        return $vars;
    }

    /**
     * Summary of display_react_component
     * here my custom template will be returned to be included in the plugin's endpoin
     * later the react component is loaded in the div "frontend" which is enclosed within
     * the main body of new template.
     * @return void
     */
    public function display_react_component($template)
    {
        global $wp_query;
        if (isset($wp_query->query_vars[$this->customEndpoint])) {
            $template = __DIR__ . '/../templates/app.php';
        }
        return $template;
    }

    /**
     * Summary of enqueue_scripts
     * neccessary scripts and style required for react component
     * and passing the users array to frontend are enqueued here
     * the siteUrl is also passed to the front end scripts so that
     * the later ajax queries are called correctly using this suteUrl in the ajax url
     * @return void
     */
    public function enqueue_scripts(): void
    {
        wp_enqueue_style('app-style', plugin_dir_url(__FILE__) . './../build/index.css');
        wp_enqueue_script(
            'app-script',
            plugin_dir_url(__FILE__)
                . './../build/index.js',
            ['wp-element'],
            '1.0.0',
            true
        );

        try {
            $dataArray = [
                'siteUrl' => site_url(),
                'users' => $this->get_data(),
            ];
            wp_localize_script('app-script', 'dataArray', $dataArray);
        } catch (Exception $e) {
            // handle error here
            error_log($e->getMessage());
        }
    }

    /**
     * Summary of get_data
     * the users data from the 3rd party provided
     * in the Task description is remotely got from the
     * an I apply transient machanism baes on the WordPress documentaion in :
     * https://developer.wordpress.org/apis/transients/
     * 'https://jsonplaceholder.typicode.com/users/' url
     * @return array
     */
    public function get_data()
    {
        $cacheKey = 'inpsyde_plugin_data';
        $cachedData = get_transient($cacheKey);

        if (false !== $cachedData) {
            return $cachedData;
        }

        try {
            $response = wp_remote_get($this->apiUrl);
            $json = wp_remote_retrieve_body($response);
            $data = json_decode($json, true);
            if ($data) {
                set_transient($cacheKey, $data, HOUR_IN_SECONDS); // cache for 1 minute
            }
            return $data ?? [];
        } catch (Exception $exp) {
            // I handle error here and log the error in websites log
            error_log($exp->getMessage());
            return [];
        }
    }

    /**
     * Summary of register_ajax_route
     * here I register my custom endpoint inpsyde/v1/details
     * for calling user's details from the 3rd party
     * in the plugin's backend as required and
     * in the front end ajax requests are maded to this route.
     * this method calls the get_details method and
     * provide the selected user's details (given id)
     * @return void
     */
    public function register_ajax_route(): void
    {
        try {
            register_rest_route('inpsyde/v1', '/details', [
                'methods' => 'GET',
                'callback' => [$this, 'get_details'],
            ]);
        } catch (Exception $exp) {
            // handle error here
            error_log($exp->getMessage());
        }
    }

    /**
     * Summary of get_details
     * here a remoe get call is made to the external url as the 3rd party
     * and provide the frontend's ajax request response
     * @param mixed $request
     * @return WP_Error|WP_REST_Response
     */
    public function get_details($request)
    {
        try {
            $userId = $request->get_param('id');
            $cacheKey = 'inpsyde_plugin_datails_' . $userId;
            $cachedDetails = get_transient($cacheKey);
            if ($cachedDetails !== false) {
                return $cachedDetails;
            }
            $response = wp_remote_get($this->apiUrl . $userId);
            $data = json_decode(wp_remote_retrieve_body($response), true);
            if ($data) {
                set_transient($cacheKey, $data, HOUR_IN_SECONDS); // cache for 1 hour
            }
            return rest_ensure_response($data ?? []);
        } catch (Exception $e) {
            // handle error here
            error_log($exp->getMessage());
            return rest_ensure_response([]);
        }
    }
}
