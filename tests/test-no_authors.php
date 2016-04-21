<?php

// testcase :checks if one author is present then admin is shown in contributor list 
class Tests_No_Authors extends WP_UnitTestCase {

    public function setUp() {
        parent::setUp();

        $this->author_id = self::factory()->user->create(array(
            'role' => 'author',
            'user_login' => 'test_author',
            'description' => 'test_author',
        ));


        $post = array(
            'post_author' => $this->author_id,
            'post_status' => 'publish',
            'post_content' => 'Test Content',
            'post_title' => 'Test Title',
            'post_type' => 'post'
        );

        // insert a post and make sure the ID is ok
        $this->post_id = self::factory()->post->create($post);

        setup_postdata(get_post($this->post_id));
    }

//testing wpi_get_all_authors Method
    public function test_author_count() {
        $pinstance = new Wpi_Post_Init();
        $post = get_post($this->post_id);
        $author_id = $post->post_author;
        $value = $pinstance->wpi_get_all_authors($author_id);
//since there are two users in system one admin by default and another author i.e. created .After excluding author admin with ID 1 remains.
        $this->assertEquals(1, count($value));
        $this->assertSame(1, (int) $value[0]->ID);
        $this->assertNotSame($author_id, (int) $value[0]->ID);
    }

}
