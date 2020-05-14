<?php
/**
 *  The template for displaying the blog-page.
 *
 *  @package WordPress
 *  @subpackage foxy-illdy
 */
?>
<?php
    get_header( 'archive' );

    function getCategoryList(){
        $catDisplayList = array();
        $categories = get_categories();
        if( $categories ){
            foreach( $categories as $cat ){
                $catDisplayList[] = '<a class="cat" href="'. esc_url( get_category_link( $cat->term_id ) ) .
                                    '" title="'. esc_attr( $cat->name ) .'">'. esc_html( $cat->name ) .' (' . $cat->count . ')</a>';
            }
        }
        return $catDisplayList;
    }

    function getTagList(){
        $tagDisplayList = array();
        $tags = get_tags();
        if( $tags ){
            foreach( $tags as $tag ){
                $tagDisplayList[] = '<a class="tag" href="'. esc_url( get_tag_link( $tag->term_id ) ) .
                                    '" title="'. esc_attr( $tag->name ) .'">'. esc_html( $tag->name ) .' (' . $tag->count . ')</a>';
            }
        }
        return $tagDisplayList;
    }
?>
<section id="latest-news" class="front-page-section">
    <div class="section-header">
        <div class="container">
            <div class="row">
                <div class="col-sm-12"> <h3> All posts </h3> </div>
                <div class="col-sm-10 col-sm-offset-1">
                    <div class="section-description">
                        On this page you can scroll through all articles, or filter by category or tag. <br />
                        Categories: <small> <?php echo join( " | ", getCategoryList() ); ?> </small> <br />
                        Tags: <small> <?php echo join( " | ", getTagList() ); ?> </small>
                    </div>
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