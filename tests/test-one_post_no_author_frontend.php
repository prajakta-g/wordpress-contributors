<?php

//testcase: one post and no author
class Tests_One_Post_No_Author extends WP_UnitTestCase {

    protected $post_id = 0;

    public function setUp() {
        parent::setUp();


        $post = array(
            'post_status' => 'publish',
            'post_content' => 'Test Content',
            'post_title' => 'Test Title',
            'post_type' => 'post'
        );

        // insert a post and make sure the ID is ok
        $this->post_id = self::factory()->post->create($post);

        setup_postdata(get_post($this->post_id));
    }

//testing wpi_display_meta() and wpi_author_display_block() methods
    public function test_post_content() {
        $pinstance1 = new Wpi_Post_Init();
        $content_post = get_post($this->post_id);
        //checks if post contains only content and no contibutor box		
        $this->assertContains('Test Content', $pinstance1->wpi_display_meta($content_post->post_content));
        $this->assertEmpty(get_post_meta($this->post_id, '_custom-meta-box', true));
        $value = get_post_meta($this->post_id, '_custom-meta-box', true);
        if ($value == "") {
            $this->assertCount(0, array());
            $v = array();
        }
        $this->assertEquals(0, $pinstance1->wpi_author_display_block($v));
    }

}
