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
//$form_id = 176;
/**
 * give_before_main_content hook
 *
 * @hooked give_output_content_wrapper - 10 (outputs opening divs for the content)
 */
do_action( 'give_before_main_content' );

while ( have_posts() ) : the_post();

$form_id = get_the_ID();
$content = wpautop( get_post_meta( $form_id, '_give_form_content', true ) );
$amount_goal  = get_post_meta( $form_id, '_give_set_goal', true );
$amount_have = get_post_meta( $form_id, '_give_form_earnings', true );
$width_bar = floor(($amount_have/$amount_goal)*100);
$number_donations = give_get_form_sales_stats( $form_id);
$give_vimeo = get_post_meta($post->ID, 'vimeo', 1);
$give_youtube = get_post_meta($post->ID, 'youtube', 1);
$give_thumbnail = 'ee';
$some_video = 'RGcr9KG1m8I';
?>
<article id="post-<?php the_ID(); ?>">
    <div class="row">
        <div class="col-md-6" id="photo-text">
            <?php 
            if ( $give_youtube !== ''){
            ?>
                <iframe width="515" height="344" src="https://www.youtube.com/embed/<?php echo $give_youtube; ?>?rel=0" frameborder="0" rel="0" allowfullscreen></iframe>
            <?php } 
            elseif ( $give_vimeo !== '') {
            ?>
                <iframe src="https://player.vimeo.com/video/<?php echo $give_vimeo; ?>?color=fff700&byline=0&portrait=0&badge=0" width="515" height="344" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
            <?php } 
            elseif ( $give_youtube == '' && $give_vimeo == '' && $give_thumbnail !== '') {
                if(has_post_thumbnail()): 
                    the_post_thumbnail();  
                endif;
            }?>
        </div>
        <div class="col-md-6" id="post-text">
            <div id="parent-form">
                <h2><?php the_title(); ?></h2>
                <div id="number-donations">
                    <?php echo $number_donations, ' ', 'backers'; ?>
                </div>
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