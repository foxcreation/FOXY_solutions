<?php
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

/**
 * Custom Widget to show Blog Post Meta details in a sidebar.
 */
// Register and load the widget
function load_postmeta_widget(){
    register_widget( 'postmeta_widget' );
}
add_action( 'widgets_init', 'load_postmeta_widget' );

// Creating the widget
class postmeta_widget extends WP_Widget{
    function __construct() {
        parent::__construct(    'postmeta_widget',
                                'Sidebar Post Meta Display',
                                array( 'description' => 'Widget to show Post Metadata on any other location than post' )
                            );
    }

    // Creating widget front-end
    public function widget( $args, $instance ){
        echo $args['before_widget'];
        $output = '<div id="blog"><div class="blog-post">'; // adding surrounding id and class to re-use styling
        $output     .= '<div class="blog-post-meta">';
            $output .= '<span class="post-meta-author"><i class="fa fa-user"></i>' . esc_html( get_the_author() ) . '</span>';
            $output .= '<span class="post-meta-time"><i class="fa fa-calendar"></i><time datetime="' . sprintf( '%s-%s-%s', get_the_date( 'Y' ), get_the_date( 'm' ), get_the_date( 'd' ) ) . '">' . get_the_date() . '</time></span>';
            $output .= '<span class="post-meta-categories"><i class="fa fa-folder-o" aria-hidden="true"></i>' . $categories_list . '</span>';
            $output .= ( ( comments_open() )
                            ? ( 0 == $number_comments )
                                ? sprintf( '<span class="post-meta-comments"><i class="fa fa-comment-o"></i>' . __( 'No comments', 'illdy' ) . '</span>' )
                                : ( $number_comments > 1 )
                                    ? sprintf( '<span class="post-meta-comments"><i class="fa fa-comment-o"></i><a class="meta-comments" href="%s" title="%s ' . __( 'comments', 'illdy' ) . '">%s ' . __( 'comments', 'illdy' ) . '</a></span>', get_comments_link(), $number_comments, $number_comments )
                                    : sprintf( '<span class="post-meta-comments"><i class="fa fa-comment-o"></i><a class="meta-comments" href="%s" title="' . __( '1 comment', 'illdy' ) . '">' . __( '1 comment', 'illdy' ) . '</a></span>', get_comments_link() )
                                : sprintf( '<span class="post-meta-comments"><i class="fa fa-comment-o"></i>' . __( 'Comments are off for this post', 'illdy' ) . '</span>' )
                            : '' );
        $output     .= '</div><!--/.blog-post-meta-->';
        $output .= '</div></div>';
        echo $output;
        echo $args['after_widget'];
    }
}