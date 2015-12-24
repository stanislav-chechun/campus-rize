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
                </header><!-- .entry-header -->
                    <?php the_content(); ?>

            </div><!-- .entry-content -->

        </div>
</article><!-- #post-## -->
</div><!-- #div row-## --> 
