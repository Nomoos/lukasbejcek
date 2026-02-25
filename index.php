<?php
/**
 * Front to the WordPress application.
 *
 * WordPress core files are in the `wordpress/` subdirectory.
 * This file bootstraps WordPress from there so that the public site
 * runs at http://localhost/fotbal_club/ (not /fotbal_club/wordpress/).
 *
 * @link https://developer.wordpress.org/advanced-administration/server/wordpress-in-directory/
 * @package WordPress
 */

define( 'WP_USE_THEMES', true );

/** Loads the WordPress Environment and Template */
require __DIR__ . '/wordpress/wp-blog-header.php';
