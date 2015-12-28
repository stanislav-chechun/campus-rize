<?php
/**
 * The Template for displaying all single Give Forms.
 *
 * Override this template by copying it to yourtheme/give/single-give-forms.php
 *
 * @package       Give/Templates
 * @version       1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header(); echo "FEFEFEFEFEF fefefef";
$ss = get_post_meta(176);
echo '<pre>';
var_dump($ss);
echo '</pre>';
/**
 * give_before_main_content hook
 *
 * @hooked give_output_content_wrapper - 10 (outputs opening divs for the content)
 */
do_action( 'give_before_main_content' );

while ( have_posts() ) : the_post();

	//give_get_template_part( 'single-give-form/content', 'single-give-form' );
$form_content = get_post_meta(176, '_give_form_content');
//echo $form_content['0']; ?>
<article id="post-<?php the_ID(); ?>" <?php //post_class(); ?>>
    <div class="row">
        <div class="col-md-6" id="photo-text">
            <iframe width="600" height="400" src="https://www.youtube.com/embed/RGcr9KG1m8I?rel=0" frameborder="0" rel="0" allowfullscreen></iframe>
        </div>
        <div class="col-md-6" id="post-text">

            <div class="post-content">
                <header class="post-header">
                    <h2 class="post-title"><?php the_title(); ?></h2>
                    <?php echo do_shortcode('[give_goal id="176" show_text="true" show_bar="true"]'); ?>
                    <?php if ( get_post_meta($post->ID, 'sum', true) ) : ?>
                        <div id="post-sum">
                            <span class="money_amount"><?php echo '$'. get_post_meta($post->ID, 'sum', true); ?></span>
                        </div>
                    <?php endif; ?>
                </header><!-- .entry-header -->
                    <?php echo $form_content['0']; ?>
                    <?php echo do_shortcode("[uptolike]"); ?>
                
            </div><!-- .entry-content -->

        </div>
        </div><!-- #div row-## --> 
</article><!-- #post-## --> <?php
endwhile; // end of the loop.

/**
 * give_after_main_content hook
 *
 * @hooked give_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'give_after_main_content' );

/**
 * give_sidebar hook
 *
 * @hooked give_get_sidebar - 10
 */
do_action( 'give_sidebar' );

get_footer();