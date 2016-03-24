<?php

// testcase :checks if no authors are present in the blog
class Tests_No_Authors extends WP_UnitTestCase {

//testing wpi_get_all_authors Method
    public function test_author_count() {
        $pinstance = new Wpi_Post_Init();
        $value = $pinstance->wpi_get_all_authors();
        $this->assertEquals(0, $value);
    }

}
