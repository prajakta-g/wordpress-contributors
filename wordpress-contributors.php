<?php

/*
  Plugin Name: WordPress Contributors
  Description: This plugin displays more than one author-name on a post
  Version: 1.0
  Tested up to: WPMU 3.8
  License: GNU General Public License 2.0 (GPL) http://www.gnu.org/licenses/gpl.html
  Author: Prajakta Ghole
  Author URI:
  Plugin URI:
  tags:User Profiles
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
/**
 * The interface that defines the functions for the meta box.
 */
include_once( plugin_dir_path(__FILE__) . '/interface-meta-box.php' );

include(plugin_dir_path(__FILE__) . "/wp-contributors-init.php");

function wpi_start() {
    $pinstance = new Wpi_Post_Init();
}

wpi_start();
?>
