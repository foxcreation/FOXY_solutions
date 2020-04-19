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

    /**
	 * When widget is loaded on single post page, show post meta details
	 */
    public function widget( $args, $instance ){
		if( is_single() ){
			$categoriesList = get_the_category_list( esc_html__( ', ', 'illdy' ) );
			$tagsList = get_the_tag_list( '', ' | ', '' );
			$number_comments = get_comments_number();

			echo $args['before_widget'];
			$output = '<div class="blog-post-meta">';
			$output .= '<div class="post-meta-time"><i class="fa fa-calendar"></i><time datetime="' . sprintf( '%s-%s-%s', get_the_date( 'Y' ), get_the_date( 'm' ), get_the_date( 'd' ) ) . '">' . get_the_date() . '</time></div>';
			$output .= '<div class="post-meta-categories"><i class="fa fa-folder-o" aria-hidden="true"></i>' . $categoriesList . '</div>';
			$output .= '<div class="post-meta-tags"><i class="fa fa-tags" aria-hidden="true"></i>' . $tagsList . '</div>';
			$output .= ( ( comments_open() )
						? ( ( 0 == $number_comments )
							? sprintf( '<div class="post-meta-comments"><i class="fa fa-comment-o"></i>' . __( 'No comments', 'illdy' ) . '</div>' )
							: ( ( $number_comments > 1 )
								? sprintf( '<div class="post-meta-comments"><i class="fa fa-comment-o"></i><a class="meta-comments" href="%s" title="%s ' . __( 'comments', 'illdy' ) . '">%s ' . __( 'comments', 'illdy' ) . '</a></div>', get_comments_link(), $number_comments, $number_comments )
								: sprintf( '<div class="post-meta-comments"><i class="fa fa-comment-o"></i><a class="meta-comments" href="%s" title="' . __( '1 comment', 'illdy' ) . '">' . __( '1 comment', 'illdy' ) . '</a></div>', get_comments_link() ) ) )
						: sprintf( '<div class="post-meta-comments"><i class="fa fa-comment-o"></i>' . __( 'Comments are off for this post', 'illdy' ) . '</div>' ) );
			$output .= '</div><!--/.blog-post-meta-->';
			echo $output;
			echo $args['after_widget'];
		}
    }
}