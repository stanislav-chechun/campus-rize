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

            <?php give_get_template_part( 'single-give-form/content', 'single-give-form' );?>

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