<?php
/**
 * The template used for displaying page content in single.php
 *
 */
?>

<article id="post-<?php the_ID(); ?>" <?php //post_class(); ?>>
    <div class="row">
        <div class="col-md-6" id="photo-text">
            <?php if(has_post_thumbnail()): 
                    the_post_thumbnail();  
                  endif; ?>
        </div>
        <div class="col-md-6" id="post-text">

            <div class="post-content">
                <header class="post-header">
                    <h2 class="post-title"><?php the_title(); ?></h2>
                    <?php if ( get_post_meta($post->ID, 'sum', true) ) : ?>
                        <div id="post-sum">
                            <span class="money_amount"><?php echo '$'. get_post_meta($post->ID, 'sum', true); ?></span>
                        </div>
                    <?php endif; ?>
                </header><!-- .entry-header -->
                    <?php the_content(); ?>
                    <?php echo do_shortcode("[uptolike]"); ?>
                <?php echo do_shortcode('[contact-form-7 id="165" title="Donate"]'); ?>
               
            </div><!-- .entry-content -->

        </div>
        </div><!-- #div row-## --> 
</article><!-- #post-## -->

