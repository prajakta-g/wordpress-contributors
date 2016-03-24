<?php

class ContributorTest extends WP_UnitTestCase {

    public function test_plugin_activated() {
        $this->assertTrue(is_plugin_active('wordpress-contributors/wordpress-contributors.php'));
    }

}
