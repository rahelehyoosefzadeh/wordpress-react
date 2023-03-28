<?php

declare(strict_types=1);

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
    public function init()
    {
        $instance = new Inpsyde();
        $instance->addHooks();
    }

    /**
     * Summary of addHooks
     * hooks are used in this method inorder to make the working
     * @return void
     */
    public function addHooks()
    {
        add_action('init', [$this, 'registerEndpoint']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
        add_action('rest_api_init', [$this, 'registerAjaxRoute']);
        add_action('template_include', [$this, 'displayReactComponent']);
        add_filter('query_vars', [$this, 'addQueryVars']);
    }

    /**
     * Summary of registerEndpoint
     * customEndpoint (given `my-lovely-users-table` )
     * is added at the / address of the website.and
     * flushes rewrite rules so that it is not required
     * to save permalinks in admin dashboard
     * @return void
     */
    public function registerEndpoint()
    {
        add_rewrite_endpoint($this->customEndpoint, EP_ROOT);
        flush_rewrite_rules();
    }

    /**
     * Summary of addQueryVars
     * possible query vars of the plugin are set
     * to the $vars array and then filtered in the query_vars hook
     * @param mixed $vars
     * @return array
     */
    public function addQueryVars($vars): array
    {
        $vars[] = $this->customEndpoint;
        $vars[] = $this->detailsPath;
        return $vars;
    }

    /**
     * Summary of displayReactComponent
     * here my custom template will be returned to be included in the plugin's endpoin
     * later the react component is loaded in the div "frontend" which is enclosed within
     * the main body of new template.
     * @param mixed $template
     * @return mixed
     */
    public function displayReactComponent($template)
    {
        global $wp_query;
        if (isset($wp_query->query_vars[$this->customEndpoint])) {
            $template = __DIR__ . '/../templates/app.php';
        }
        return $template;
    }

    /**
     * Summary of enqueueScripts
     * neccessary scripts and style required for react component
     * and passing the users array to frontend are enqueued here
     * the siteUrl is also passed to the front end scripts so that
     * the later ajax queries are called correctly using this suteUrl in the ajax url
     * @return void
     */
    public function enqueueScripts(): void
    {
        wp_enqueue_style(
            'app-style',
            plugin_dir_url(__FILE__) . './../build/index.css',
            [],
            '1.0.0'
        );
        wp_enqueue_script(
            'app-script',
            plugin_dir_url(__FILE__) . './../build/index.js',
            ['wp-element'],
            '1.0.0',
            true
        );

        try {
            $dataArray = [
                'siteUrl' => site_url(),
                'users' => $this->fetchData(),
            ];
            wp_localize_script('app-script', 'dataArray', $dataArray);
        } catch (Exception $exp) {
            // handle error here
            echo esc_html('Whoops, an error:' . $exp->getMessage());
        }
    }

    /**
     * Summary of fetchData
     * the users data from the 3rd party provided
     * in the Task description is remotely got from the
     * an I apply transient machanism baes on the WordPress documentaion in :
     * https://developer.wordpress.org/apis/transients/
     * 'https://jsonplaceholder.typicode.com/users/' url
     * @return array
     */
    public function fetchData(): array
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
            // I handle error here and just display it
            echo esc_html('Whoops, an error:' . $exp->getMessage());
            return [];
        }
    }

    /**
     * Summary of registerAjaxRoute
     * here I register my custom endpoint inpsyde/v1/details
     * for calling user's details from the 3rd party
     * in the plugin's backend as required and
     * in the front end ajax requests are maded to this route.
     * this method calls the fetchDetails method and
     * provide the selected user's details (given id)
     * @return void
     */
    public function registerAjaxRoute(): void
    {
        try {
            register_rest_route('inpsyde/v1', '/details', [
                'methods' => 'GET',
                'callback' => [$this, 'fetchDetails'],
            ]);
        } catch (Exception $exp) {
            // I handle error here and just display it
            echo esc_html('Whoops, an error:' . $exp->getMessage());
        }
    }

    /**
     * Summary of fetchDetails
     * here a remoe get call is made to the external url as the 3rd party
     * and provide the frontend's ajax request response
     * @param mixed $request
     * @return WP_Error|WP_REST_Response
     */
    public function fetchDetails($request): array
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
        } catch (Exception $exp) {
            // I handle error here and just display it
            echo esc_html('Whoops, an error:' . $exp->getMessage());
            return rest_ensure_response([]);
        }
    }
}
