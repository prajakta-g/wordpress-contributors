<?php
/*
 * Main class which implements custom meta box
 * 
 */

class Wpi_Post_Init implements Interface_Meta_Box {

    public function __construct() {

        add_action('add_meta_boxes', array($this, 'wpi_add_meta'));

        add_action('save_post', array($this, 'wpi_save_meta'));

        add_action('wp_enqueue_scripts', array($this, 'wpi_register_plugin_styles'));

        add_filter('the_content', array($this, 'wpi_display_meta'));
    }

    public function wpi_get_all_authors($author_id) {

        $blogusers = array();

		//change is here
        $blogusers = get_users(array('role__in' => array('administrator', 'editor', 'author'), 'exclude' => array($author_id)));

        if (empty($blogusers)) {
            return 0;
        } else
            return $blogusers;
    }

    public function wpi_add_meta() {

        add_meta_box('wpi_post-options', 'Contributors', array($this, 'wpi_call_to_post_options'), 'post', 'normal', 'high');
        if (!current_user_can('publish_posts')) {
            remove_meta_box('wpi_post-options', 'post', 'normal');
        }
    }

    public function wpi_call_to_post_options($post) {

        global $post;

        $author_id = $post->post_author;

        $custom_meta = get_post_meta($post->ID, '_custom-meta-box', true);

        wp_nonce_field('my_meta_box_nonce', 'meta_box_nonce');

        if (empty($custom_meta)) {

            $custom_meta = array();
        }

        $blogusers = $this->wpi_get_all_authors($author_id);

        if (!$blogusers) {
            echo "Currently no user other than admin . Please create some authors/editors/admins.";
        } else {

            foreach ($blogusers as $user) {
                ?>

                <br/><input type="checkbox" name="custom-meta-box[]" value="<?php echo $user->ID ?>" <?php echo (in_array("$user->ID", $custom_meta)) ? 'checked="checked"' : ''; ?> />

                <?php
                echo $user->display_name;
            }
        }
    }

    public function wpi_save_meta() {

        global $post;

        // Bail if we're doing an auto save

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;


        // if our nonce isn't there, or we can't verify it, bail

        if (!isset($_POST['meta_box_nonce']) || !wp_verify_nonce($_POST['meta_box_nonce'], 'my_meta_box_nonce'))
            return;


        // if our current user can't edit this post, bail

        if (!current_user_can('publish_posts'))
            return;


        // Get our form field

        if (isset($_POST['custom-meta-box'])) {


            $custom = $_POST['custom-meta-box'];

            $old_meta = get_post_meta($post->ID, '_custom-meta-box', true);

            // Update post meta

            if (!empty($old_meta)) {

                update_post_meta($post->ID, '_custom-meta-box', $custom);
            } else {

                add_post_meta($post->ID, '_custom-meta-box', $custom, true);
            }
        }



        if (empty($_POST['custom-meta-box'])) {

            delete_post_meta($post->ID, '_custom-meta-box');
        }
    }

    public function wpi_display_meta($content) {

        global $post;

        $custom_meta = array();

        $id = ( isset($post->ID) ? get_the_ID() : NULL );

        if (isset($id) && get_post_meta($post->ID, '_custom-meta-box', true)) {

            $custom_meta = get_post_meta($post->ID, '_custom-meta-box', true);
        }

        $author = $this->wpi_author_display_block($custom_meta);

        return $content . $author;
    }

    public function wpi_author_display_block($custom_meta) {

        if (!empty($custom_meta)) {

            $author = "";

            $author.= "<div class='container contributorbox' ><div class='contributorlabel' >Contributors</div>";

            $author.="<ul class='list-group'>";

            foreach ($custom_meta as $value) {

                $author.= "<li class='list-group-item twocol'>";

                $author.= get_avatar($value);

                $author.= "<a href=\"" . get_bloginfo('url') . "/?author=";

                $author.= $value;

                $author.= "\">";

                $author.=get_the_author_meta('display_name', $value);

                $author.= "</a>";

                $author.= "</li>";
            }

            $author.= "</ul></div>";



            return $author;
        } else
            return;
    }

    public function wpi_register_plugin_styles() {

        wp_register_style('wordpress-contributors', plugins_url('wordpress-contributors/css/authorstyle.css'));

        wp_enqueue_style('wordpress-contributors');
    }

}
?>
