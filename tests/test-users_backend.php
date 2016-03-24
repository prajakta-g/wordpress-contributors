<?php

//testcase: one post and two authors created with factory class
class Tests_Users_Backend extends WP_UnitTestCase {

//testing wpi_add_meta method   
    public function test_add_meta_box() {
        global $wpdb;
        global $wp_meta_boxes;
        $pinstance = new Wpi_Post_Init();
        $user = new WP_User(self::factory()->user->create(array('role' => 'administrator')));
        wp_set_current_user($user->ID);
        $pinstance->wpi_add_meta();
        // Confirm it's there.
        $this->assertArrayHasKey('wpi_post-options', $wp_meta_boxes['post']['normal']['high']);

        $user = new WP_User(self::factory()->user->create(array('role' => 'editor')));
        wp_set_current_user($user->ID);
        $pinstance->wpi_add_meta();
        // Confirm it's there.
        $this->assertArrayHasKey('wpi_post-options', $wp_meta_boxes['post']['normal']['high']);



        $user = new WP_User(self::factory()->user->create(array('role' => 'author')));
        wp_set_current_user($user->ID);
        $pinstance->wpi_add_meta();
        // Confirm it's there.
        $this->assertArrayHasKey('wpi_post-options', $wp_meta_boxes['post']['normal']['high']);

        $user = new WP_User(self::factory()->user->create(array('role' => 'contributor')));
        wp_set_current_user($user->ID);
        $pinstance->wpi_add_meta();
        // Confirm it's not there.
        $this->assertFalse($wp_meta_boxes['post']['normal']['high']['wpi_post-options']);


        $user = new WP_User(self::factory()->user->create(array('role' => 'subscriber')));
        wp_set_current_user($user->ID);
        $pinstance->wpi_add_meta();
        // Confirm it's not there.
        $this->assertFalse($wp_meta_boxes['post']['normal']['high']['wpi_post-options']);
    }

}
