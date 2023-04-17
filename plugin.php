<?php

/**
 * Plugin Name: Common Plugins Meta
 * Plugin URI:  https://github.com/rebelinteractivegroup/rig-plugins
 * Description: A simple meta package for commonly used WordPress plugins
 * Version:     1.0.0
 * Author:      Rob Voss
 * Author URI:  https://github.com/Rob-Voss
 * Licence:     MIT
 */

add_action('plugins_loaded', new class
{
    /**
     * Invoke the plugin.
     *
     * @return void
     */
    public function __invoke()
    {

        /**
         * Process shortcodes inside of titles, descriptions, and open graph data.
         *
         * @return string
         */
        foreach([
            'the_title',
            'get_the_title',
            'wpseo_title',
            'wpseo_metadesc',
            'wpseo_opengraph_title',
            'wpseo_opengraph_desc',
            'wpseo_opengraph_site_name',
            'wpseo_twitter_title',
            'wpseo_twitter_description'
        ] as $hook) {
            add_filter($hook, 'do_shortcode');
        }

        /**
         * Process shortcodes inside of WordPress SEO's schema.
         *
         * @param  string[] $data
         * @return string[]
         */
        foreach(['wpseo_schema_webpage', 'wpseo_schema_article'] as $hook) {
            add_filter($hook, function ($data) {
                return array_map(function ($item) {
                    return is_string($item) ? do_shortcode($item) : $item;
                }, $data);
            });
        }

        /**
         * Hide version on WordPress SEO's HTML output.
         *
         * @return bool
         */
        add_filter('wpseo_hide_version', '__return_true');

        /**
         * Register the defined Google Maps API key with ACF.
         *
         * @return void
         */
        add_filter('acf/init', function () {
            if (! defined('GOOGLE_MAPS_API_KEY') || ! function_exists('acf_update_setting')) {
                return;
            }

            acf_update_setting('google_api_key', GOOGLE_MAPS_API_KEY);
        });
    }
});
