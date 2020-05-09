<?php
/**
 *  The template for displaying the archive.
 *  Showing no sidebar and 'blog-carousel display' (as latest-news part of front-page)
 *
 *  @package WordPress
 *  @subpackage illdy
 */
?>
<?php
    // @todo: either override the header.php with parameter on archive to hide the jumbotron, or make header-archive
    get_header( 'archive' );
?>
<section id="latest-news" class="front-page-section">
    <div class="section-header">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <?php the_archive_title( '<h3>', '</h3>' ); ?>
                </div><!--/.col-sm-12-->
                <div class="col-sm-10 col-sm-offset-1">
                    <?php the_archive_description( '<div class="section-description">', '</div>' ); ?>
                </div><!--/.col-sm-10.col-sm-offset-1-->
            </div><!--/.row-->
        </div><!--/.container-->
    </div><!--/.section-header-->
<?php
    if( have_posts() ){ ?>
        <div class="section-content">
            <div style="width:90%;margin:0 auto;">
                <div class="row blog-carousel">
                <?php
                    // Loop over posts
                    while ( have_posts() ) :
                        the_post();
                        get_template_part( 'template-parts/content', 'archive' );
                    endwhile;
                    wp_reset_query();
                ?>
                </div>
            </div>
        </div>
    <?php
    } else{
        get_template_part( 'template-parts/content', 'none' );
    }
    do_action( 'illdy_after_content_above_footer' );
?>
</section><!--/#latest-news-->

<?php get_footer(); ?>