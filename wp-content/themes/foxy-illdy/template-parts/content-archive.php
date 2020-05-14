<?php
/**
 *    The template for displaying the content.
 *
 * @package    WordPress
 * @subpackage illdy
 */
$post_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'illdy-front-page-latest-news' );
add_action( 'widgets_init', 'load_postmeta_widget' );
?>

<div class="illdy-blog-post col-12 col-sm-6 col-lg-4 col-xl-3">
    <div class="post archive-post">
    <?php if ( has_post_thumbnail() ) {
            echo '<div class="post-image" style="background-image: url('. esc_url( $post_thumbnail[0] ) . ');"></div>';
        } elseif ( get_theme_mod( 'illdy_disable_random_featured_image' ) ) {
            echo '<div class="post-image"  style="background-image: url('. illdy_get_random_featured_image() .');"></div>';
        }
    ?>
        <h5><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="post-title"><?php the_title(); ?></a></h5>
        <div class="post-entry">
            <?php the_excerpt(); ?>
        </div><!--/.post-entry-->
        <a href="<?php the_permalink(); ?>" title="<?php _e( 'Read more', 'illdy' ); ?>" class="post-button">
            <i class="fa fa-chevron-circle-right"></i><?php _e( 'Read more', 'illdy' ); ?>
        </a>

        <?php
            the_widget( 'postmeta_widget', array( 'format' => postmeta_widget::FORMAT_ARCHIVE_POST ) );
        ?>
    </div><!--/.post-->
</div><!--/.col-sm-4-->