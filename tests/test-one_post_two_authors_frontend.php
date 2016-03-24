<?php

//testcase: one post and two authors created with factory class
class Tests_One_Post_Two_Authors extends WP_UnitTestCase {

    protected $author_id = 0;
    protected $author_id_2 = 0;
    protected $post_id = 0;

    public function setUp() {
        parent::setUp();

        $this->author_id = self::factory()->user->create(array(
            'role' => 'author',
            'user_login' => 'test_author',
            'description' => 'test_author',
        ));


        $this->author_id_2 = self::factory()->user->create(array(
            'role' => 'author',
            'user_login' => 'test_author_two',
            'description' => 'test_author_two',
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

//testing wpi_display_meta() and wpi_author_display_block() methods
    public function test_post_content() {

        $author = array($this->author_id, $this->author_id_2);
        add_post_meta($this->post_id, '_custom-meta-box', $author, true);
        $this->assertCount(2, get_post_meta($this->post_id, '_custom-meta-box', true));

        $pinstance = new Wpi_Post_Init();
        $content_post = get_post($this->post_id);
//checks if post content contains POST content and contributor box
        $this->assertContains('Test Content', $pinstance->wpi_display_meta($content_post->post_content));
        $this->assertContains('Contributors', $pinstance->wpi_author_display_block(get_post_meta($this->post_id, '_custom-meta-box', true)));
//checks if contribution box contains the name of authors
        $this->assertContains('test_author', $pinstance->wpi_author_display_block(get_post_meta($this->post_id, '_custom-meta-box', true)));
        $this->assertContains('test_author_two', $pinstance->wpi_author_display_block(get_post_meta($this->post_id, '_custom-meta-box', true)));
//checks if links of gravatar are present (2 authors so link of gravatr should be 4, 2 for each author as one in img src and other in )
        $this->assertEquals(preg_match_all('/(http)(.)(\\/)(\\/).*?(\\.)(gravatar\\.com)(\\/)(avatar)(\\/).*?((?:[a-z][a-z]*[0-9]+[a-z0-9]*))/', $pinstance->wpi_author_display_block(get_post_meta($this->post_id, '_custom-meta-box', true))), 4);
//checks if two links of authors are present as authors are two so links should be two.
        $this->assertEquals(preg_match_all('/(http).*?((?:\\/[\\w\\.\\-]+)+).*?(\\?)(author)(=)(\\d+)/', $pinstance->wpi_author_display_block(get_post_meta($this->post_id, '_custom-meta-box', true))), 2);
    }

    public function test_author_count() {
        $pinstance = new Wpi_Post_Init();
        $value = $pinstance->wpi_get_all_authors();
//checks for authors not equal to zero and equal to 2
        $this->assertNotEquals(0, $value);
        $this->assertCount(2, $value);
    }

}
