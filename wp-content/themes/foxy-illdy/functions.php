<?php
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri() . '/layout/css/bootstrap.min-modified.css', array(), '3.3.6-fixed', 'all' );
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
        $categoriesList = get_the_category_list( esc_html__( ', ', 'illdy' ) );
        $tagsList = get_the_tag_list( '', ' | ', '' );

		if( is_single() ){
			echo $args['before_widget'];
			$output = '<div class="blog-post-meta">';
			$output .= '<div class="post-meta-time"><i class="fa fa-calendar"></i>'. $this->getTimeText() .'</div>';
			$output .= '<div class="post-meta-categories"><i class="fa fa-folder-o" aria-hidden="true"></i>' . ( ( empty( $categoriesList ) ) ? '-' : $categoriesList ) . '</div>';
            $output .= '<div class="post-meta-tags"><i class="fa fa-tags" aria-hidden="true"></i>' . ( ( empty( $tagsList ) ) ? '-' : $tagsList ). '</div>';
			$output .= '<div class="post-meta-comments"><i class="fa fa-comment-o"></i>'. $this->getCommentsText() .'</div>';
			$output .='</div><!--/.blog-post-meta-->';
			echo $output . $args[ 'after_widget' ];
		} else if( is_archive() ){
            echo $args['before_widget'];
            $output = '<div class="blog-post-meta">';
            $output .= '<span class="post-meta-time"><i class="fa fa-calendar"></i>'. $this->getTimeText() .'</span>';
            $output .= '<span class="post-meta-categories"><i class="fa fa-folder-o" aria-hidden="true"></i>' . ( ( empty( $categoriesList ) ) ? '-' : $categoriesList ) . '</span>';
            $output .= '<span class="post-meta-comments"> <i class="fa fa-comment-o"></i>'. get_comments_number() .'</span>';
            $output .= '<br />';
            $output .= '<span class="post-meta-tags"><i class="fa fa-tags" aria-hidden="true"></i>' . ( ( empty( $tagsList ) ) ? '-' : $tagsList ). '</span>';
            $output .= '</div><!--/.blog-post-meta-->';
            echo $output . $args[ 'after_widget' ];
		}
    }

    public function getTimeText(){
        return '<time datetime="' . sprintf( '%s-%s-%s', get_the_date( 'Y' ), get_the_date( 'm' ), get_the_date( 'd' ) ) . '">' . get_the_date() . '</time>';
    }

    public function getCommentsText(){
        $number_comments = get_comments_number();
        return ( ( comments_open() )
                ? ( ( 0 == $number_comments )
                    ? __( 'No comments', 'illdy' )
                    : ( ( $number_comments > 1 )
                        ? sprintf( '<a class="meta-comments" href="%s" title="%s ' . __( 'comments', 'illdy' ) . '">%s ' . __( 'comments', 'illdy' ) . '</a>', get_comments_link(), $number_comments, $number_comments )
                        : sprintf( '<a class="meta-comments" href="%s" title="' . __( '1 comment', 'illdy' ) . '">' . __( '1 comment', 'illdy' ) . '</a>', get_comments_link() ) ) )
                : __( 'Comments are off for this post', 'illdy' ) );
    }
}