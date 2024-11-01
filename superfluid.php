<?php
/**
 * Plugin Name: superfluid widget
 * Plugin URI: http://thesuperfluid.com
 * Description: A widget that lets you receive Quids from superfluid members.
 * Version: 0.1
 * Author: superfluid llc
 * Author URI: http://thesuperfluid.com
 */

add_action( 'widgets_init', 'load_superfluid_widget' );
add_filter( 'the_content', 'insert_superfluid_widget' );

function insert_superfluid_widget($text){
  $w = new superfluid_widget;
  $settings = $w->get_settings();
  $opts = $settings['3'];
  $superfluid_id = $opts['superfluid_id'];
  if($opts['show_under_posts'])
     $text = $text.'<div style=\'width:100%; height:28px; margin:10px 0;\'><iframe width=\'300\' height=\'28\' style=\'overflow:hidden; border:0;\' src=\'http://thesuperfluid.com/api/give/'.$superfluid_id.'?loose=true&value='.$opts['default_amount'].'\'></iframe></div>';
  return $text;
}

function load_superfluid_widget() {
        register_widget( 'superfluid_widget' );
}

class superfluid_widget extends WP_Widget {

        function superfluid_widget() {
                $widget_ops = array( 'classname' => 'superfluid', 'description' => __('A widget that lets you share receive Quids from superfluid members.', 'superfluid') );
                $control_ops = array( 'width' => 200, 'id_base' => 'superfluid-widget' );
                $this->WP_Widget( 'superfluid-widget', __('superfluid widget', 'superfluid'), $widget_ops, $control_ops );
        }

        function widget( $args, $instance ) {
                extract( $args );

                /* Our variables from the widget settings. */
                $title = apply_filters('widget_title', $instance['title'] );
                $width = apply_filters('widget_width', $instance['width'] );
                $height = apply_filters('widget_height', $instance['height'] );
                $superfluid_id = $instance['superfluid_id'];
                $s_width = $instance['width'];
                $s_height = $instance['height'];
                $default_amount = $instance['default_amount'];
                if( !$s_width )
                  $s_width = 200;
                if( !$s_height )
                  $s_height = 60;

                /* Before widget (defined by themes). */
                echo $before_widget;
                printf( '<iframe width=\'%s\' height=\'%s\' style=\'overflow:hidden; border:0;\' src=\'http://thesuperfluid.com/api/give/%s?value='.$default_amount.'\'></iframe>', $s_width, $s_height, $superfluid_id );

                /* After widget (defined by themes). */
                echo $after_widget;
        }

        /**
         * Update the widget settings.
         */
        function update( $new_instance, $old_instance ) {
                $instance = $old_instance;

                $instance['superfluid_id'] = strip_tags( $new_instance['superfluid_id'] );
                $instance['width'] = strip_tags( $new_instance['width'] );
                $instance['height'] = strip_tags( $new_instance['height'] );
                $instance['default_amount'] = strip_tags( $new_instance['default_amount'] );
                $instance['show_under_posts'] = $new_instance['show_under_posts'];

                return $instance;
        }

        function form( $instance ) {

                $defaults = array( 'superfluid_id' => '0', 'width' => 300, 'height' => 40, 'default_amount' => 5, 'show_under_posts' => true);
                $instance = wp_parse_args( (array) $instance, $defaults ); ?>

                
        <p>
               <label for="<?php echo $this->get_field_id( 'superfluid_id' ); ?>"><?php _e('superfluid id:', 'hybrid'); ?></label>
               <input id="<?php echo $this->get_field_id( 'superfluid_id' ); ?>" name="<?php echo $this->get_field_name( 'superfluid_id' ); ?>" value="<?php echo $instance['superfluid_id']; ?>" />
        </p>
        <p>
               <label for="<?php echo $this->get_field_id( 'default_amount' ); ?>"><?php _e('default amount:', 'hybrid'); ?></label>
               <input id="<?php echo $this->get_field_id( 'default_amount' ); ?>" name="<?php echo $this->get_field_name( 'default_amount' ); ?>" value="<?php echo $instance['default_amount']; ?>" />
        </p>
        <p>
               <label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e('width:', 'hybrid'); ?></label>
               <input id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo $instance['width']; ?>" />
        </p>
        <p>
               <label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e('height:', 'hybrid'); ?></label>
               <input id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" value="<?php echo $instance['height']; ?>" />
        </p>
        <p>
                <input class="checkbox" type="checkbox" <?php checked( $instance['show_under_posts'], true ); ?> id="<?php echo $this->get_field_id( 'show_under_posts' ); ?>" name="<?php echo $this->get_field_name( 'show_under_posts' ); ?>" />
                <label for="<?php echo $this->get_field_id( 'show_under_posts' ); ?>">Show under every post?</label>
        </p>
        <?php
        }
}

?>
