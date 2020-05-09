<?php
/**
 *    The template for displaying the header.
 *
 * @package    WordPress
 * @subpackage illdy
 */
?>
<?php
$logo_id                   = get_theme_mod( 'custom_logo' );
$logo_image                = wp_get_attachment_image_src( $logo_id, 'full' );
$logo_width                = get_theme_mod( 'illdy_logo_width' );
$text_logo                 = get_theme_mod( 'illdy_text_logo', __( 'Illdy', 'illdy' ) );
$preloader_enable          = get_theme_mod( 'illdy_preloader_enable', 1 );
$is_mobile_safari          = preg_match( '/(iPod|iPhone|iPad)/', $_SERVER['HTTP_USER_AGENT'] );
$accent_color              = get_theme_mod( 'epsilon_accent_color', '#f1d204' );

$style = '';
$url = get_theme_mod( 'header_image', get_theme_support( 'custom-header', 'default-image' ) );

$header_class = 'header-blog';
if ( get_theme_mod( 'illdy_sticky_header_enable', false ) ) {
	$header_class .= ' header-has-sticky-menu';
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php if ( 1 == $preloader_enable && ! is_customize_preview() ) : ?>
	<div class="pace-overlay"></div>
<?php endif; ?>
<header id="header" class="<?php echo $header_class; ?>" style="<?php echo $style; ?>">
<div class="is-sticky"> <!-- enforce to be sticky by default -->
	<div class="top-header">
		<div class="container">
			<div class="row">
				<div class="col-sm-10 col-xs-8 col-8">

					<?php if ( ! empty( $logo_image ) ) : ?>
						<a href="<?php echo esc_url( home_url() ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
							<img class="header-logo-img" src="<?php echo esc_url( $logo_image[0] ); ?>" width="<?php echo $logo_width ? esc_attr( $logo_width ) : ''; ?>"/>
						</a>
					<?php else : ?>
					<?php if ( get_option( 'show_on_front' ) == 'page' ) { ?>
						<a href="<?php echo esc_url( home_url() ); ?>" title="<?php echo esc_attr( $text_logo ); ?>" class="header-logo"><?php echo esc_html( $text_logo ); ?></a>
					<?php } else { ?>
						<a href="<?php echo esc_url( home_url() ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="header-logo"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></a>
					<?php } ?>
					<?php endif; ?>

				</div><!--/.col-sm-2-->
				<div class="col-sm-2 col-xs-4 col-4">
					<nav class="header-navigation">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'primary-menu',
								'menu'           => '',
								'container'      => false,
								'menu_class'     => 'clearfix',
								'menu_id'        => '',
							)
						);
						?>
					</nav>
					<button class="open-responsive-menu"><i class="fa fa-bars"></i></button>
				</div><!--/.col-sm-10-->
			</div><!--/.row-->
		</div><!--/.container-->
	</div><!--/.top-header-->
	<nav class="responsive-menu">
		<ul>
			<?php
			wp_nav_menu(
				array(
					'theme_location'  => 'primary-menu',
					'menu'            => '',
					'container'       => '',
					'container_class' => '',
					'container_id'    => '',
					'menu_class'      => '',
					'menu_id'         => '',
					'items_wrap'      => '%3$s',
				)
			);
			?>
		</ul>
	</nav><!--/.responsive-menu-->
</div>
</header><!--/#header-->
