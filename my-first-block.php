<?php
/**
 * Plugin Name:       My First Block
 * Description:       Example block scaffolded with Create Block tool.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       my-first-block
 *
 * @package           create-block
 */

namespace My_first_block;


use My_first_block\Repositories\OffersRepository;

if ( ! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
require __DIR__ . '/autoload.php';

/**
 * Returns the path of the plugin directory.
 *
 * @return string
 */
function pluginPath(): string
{
    return plugin_dir_path(__FILE__);
}

/**
 * Returns the URL of the plugin directory.
 *
 * @return string
 */
function pluginUrl(): string
{
    return plugins_url(basename(__DIR__) . '/');
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function init()
{
    register_block_type(__DIR__ . '/build', [
        'render_callback' => [OffersRepository::class, 'render'],
    ]);
}

add_action('init', __NAMESPACE__ . '\init');

/**
 * Adds Dashicons support on a frontend.
 *
 * @return void
 */
function dashicons()
{
    wp_enqueue_style('dashicons');
}

add_action('wp_enqueue_scripts', __NAMESPACE__ . '\dashicons');
