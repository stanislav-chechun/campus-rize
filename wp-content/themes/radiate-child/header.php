<?php
/**
 * The Header for our theme.
 *
 * 
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
</head>

<body <?php body_class('landing-page  wsite-theme-light  wsite-page-index postload'); ?>>

	<!--<div id="parallax-bg"></div>-->
	<div id="page" class="hfeed site">
		<?php do_action( 'before' ); ?>
		<header id="masthead" class="site-header" role="banner">
			<div class="header-wrap clearfix">
				<div class="content-wrap">
					<nav class="navbar navbar-default">
						<div class="navbar-header">	
							<button type="button" id="navbar-toggle-btn" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>						
							<div id="logo">
								<span class="wsite-logo">
									<a href="<?php echo get_home_url(); ?>">												
										<img src="<?php echo get_home_url(); ?>/wp-content/uploads/2015/12/logo.png" />
									</a>
								</span>
							</div>							
							<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
								<?php wp_nav_menu( array( 'theme_location' => 'primary',
												  'container_class' => 'nav navbar-nav navbar-right', 
												  'menu_class'      => 'wsite-menu-default',
												  'items_wrap'      => '<ul class="%2$s">%3$s</ul>',  ) ); ?>									
							</div>
						</div>								    
					</nav>
				</div>
			</div><!-- .inner-wrap header-wrap -->
		</header>

		<div id="content" class="site-content">
			<div id="navmobile" class="nav">
				<?php wp_nav_menu( array( 'theme_location' => 'primary',
												  'container_class' => 'nav navbar-nav navbar-right', 
												  'menu_class'      => 'wsite-menu-default',
												  'items_wrap'      => '<ul class="%2$s">%3$s</ul>',  ) ); ?>
			</div>
			<div id="main-content" class="inner-wrap">