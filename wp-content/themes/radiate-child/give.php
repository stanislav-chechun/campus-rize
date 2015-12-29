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

get_header();
$form_id = 176;
$ss = get_post_meta($form_id);
echo '<pre>';
//var_dump($ss);
echo '</pre>';
/**
 * give_before_main_content hook
 *
 * @hooked give_output_content_wrapper - 10 (outputs opening divs for the content)
 */
do_action( 'give_before_main_content' );

while ( have_posts() ) : the_post();

	//give_get_template_part( 'single-give-form/content', 'single-give-form' );
//$form_content = get_post_meta($id_form, '_give_form_content');
$content = wpautop( get_post_meta( $form_id, '_give_form_content', true ) );
//echo $form_content['0']; ?>
<article id="post-<?php the_ID(); ?>" <?php //post_class(); ?>>
    <div class="row">
        <div class="col-md-6" id="photo-text">
            <iframe width="500" height="400" src="https://www.youtube.com/embed/RGcr9KG1m8I?rel=0" frameborder="0" rel="0" allowfullscreen></iframe>
        </div>
        <div class="col-md-6" id="post-text">
            <div id="parent-form">
                <h2><?php the_title(); ?></h2>
                <?php 
                    $amount_goal  = get_post_meta( $form_id, '_give_set_goal', true );
                    $amount_have = get_post_meta( $form_id, '_give_form_earnings', true );
                    $width_bar = floor(($amount_have/$amount_goal)*100);
                ?>
                <div class="progress">
                    <div class="progress-bar progress-bar-success" role="progressbar" id="form-progress" aria-valuenow="<?php echo $width_bar; ?>" 
                        aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $width_bar; ?>%">
                        <?php echo $width_bar; ?>%
                    </div>
                </div>
                <?php// give_get_template_part( 'single-give-form/content', 'single-give-form' );?>
                <?php give_get_donation_form( $args = array() ); ?>
                
            </div>
        </div>
        </div><!-- #div row-## --> 
        
        <div class="row">
            <div class="col-md-12" id="text-content">
               <div id="give-form-content-<?php echo $form_id ?>" class="text-explain"><?php echo $content; ?> </div>
            </div>
        </div>
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