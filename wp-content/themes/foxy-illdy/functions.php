<?php
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri() . '/layout/css/bootstrap.min-modified.css', array(), '3.3.6-fixed', 'all' );
}

/**
 * Custom Widget to show Blog Post Meta details in a sidebar.
 */
function load_postmeta_widget(){
    register_widget( 'postmeta_widget' );
}
add_action( 'widgets_init', 'load_postmeta_widget' );

// Creating the widget
class postmeta_widget extends WP_Widget{
    public const FORMAT_INHERIT        = 'inherit';
    public const FORMAT_SINGLE_POST    = 'single';
    public const FORMAT_ARCHIVE_POST   = 'archive';

    private const ALL_FORMATS = [ self::FORMAT_INHERIT, self::FORMAT_SINGLE_POST, self::FORMAT_ARCHIVE_POST ];
    private $formatType = self::FORMAT_INHERIT; // set default to inherit (base on is_single and is_archive)

    function __construct(){
        parent::__construct(    'postmeta_widget',
                                'Sidebar Post Meta Display',
                                array( 'description' => 'Widget to show Post Metadata on any other location than post' )
                            );
    }

    /**
     * Methods to verify the Post Type to allow distinction in display
     * Allowing to overrule the is_ methods() for e.g. archive on All Blog Posts
     */
    public function isSinglePost(){ return $this->formatType == self::FORMAT_SINGLE_POST || ( $this->formatType === self::FORMAT_INHERIT && is_single() );}
    public function isArchivePost(){ return $this->formatType == self::FORMAT_ARCHIVE_POST || ( $this->formatType === self::FORMAT_INHERIT && is_archive() ); }

    /**
	 * When widget is loaded on single post page, show post meta details
	 */
    public function widget( $args, $instance ){
        // Retrieve formatType from WP Widget Options, else continue with formatType set
        if( isset( $instance[ 'format' ] ) && !empty( $instance[ 'format' ] ) ){ $this->formatType = $instance[ 'format' ]; }

        $categoriesList = get_the_category_list( esc_html__( ', ', 'illdy' ) );
        $tagsList = get_the_tag_list( '', ' | ', '' );

		if( $this->isSinglePost() ){
			echo $args['before_widget'];
			$output = '<div class="blog-post-meta">';
			$output .= '<div class="post-meta-time"><i class="fa fa-calendar"></i>'. $this->getTimeText() .'</div>';
			$output .= '<div class="post-meta-categories"><i class="fa fa-folder-o" aria-hidden="true"></i>' . ( ( empty( $categoriesList ) ) ? '-' : $categoriesList ) . '</div>';
            $output .= '<div class="post-meta-tags"><i class="fa fa-tags" aria-hidden="true"></i>' . ( ( empty( $tagsList ) ) ? '-' : $tagsList ). '</div>';
			$output .= '<div class="post-meta-comments"><i class="fa fa-comment-o"></i>'. $this->getCommentsText() .'</div>';
			$output .='</div><!--/.blog-post-meta-->';
			echo $output . $args[ 'after_widget' ];
		} else if( $this->isArchivePost() ){
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

    public function form( $instance ){
        $format = ( isset( $instance[ $this->get_field_id( 'format' ) ] ) && !empty( $instance[ $this->get_field_id( 'format' ) ] ) )
                    ? $instance[ $this->get_field_id( 'format' ) ]
                    : self::FORMAT_SINGLE_POST;
        echo '<p>
                <label for="'. $this->get_field_id( 'format' ) .'">'. _e( 'Format:' ) .'</label>
                <select id="'. $this->get_field_id( 'format' ) .'" name="'. $this->get_field_name( 'format' ) .'">';
                foreach( self::ALL_FORMATS as $formatOption ){
                    echo '<option value="'. $formatOption .'" '. ( $format == $formatOption ? "selected" : "" ) .'>'. $formatOption .'</option>';
                }
        echo    '</select>
            </p>';
    }

    public function update( $new_instance, $old_instance ){
        $instance = array();
        $instance[ 'format' ] = ( !empty( $new_instance[ 'format' ] ) ) ? strip_tags( $new_instance[ 'format' ] ) : '';
        return $new_instance;
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