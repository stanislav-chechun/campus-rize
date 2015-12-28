<?php
/**
 * The template used for displaying page content in single.php
 *
 */
?>

<article id="post-<?php the_ID(); ?>" <?php //post_class(); ?>>
    <div class="row">
        <div class="col-md-8" id="photo-text">
            <?php //if(has_post_thumbnail()): 
                    
                   //do_shortcode('[youtube http://www.youtube.com/watch?v=RGcr9KG1m8I&w=320&h=240]');
                      
                  //endif; ?>
            <iframe width="600" height="400" src="https://www.youtube.com/embed/RGcr9KG1m8I?rel=0" frameborder="0" rel="0" allowfullscreen></iframe>
        </div>
        <div class="col-md-4" id="post-text">

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
</article><!-- #post-## -->
</div><!-- #div row-## --> 
