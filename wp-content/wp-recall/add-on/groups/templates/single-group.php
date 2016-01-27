<?php global $rcl_group,$rcl_group_widgets; ?>
<?php if(rcl_is_group_area('sidebar')): ?>
    <div class="group-sidebar">
        <div class="group-avatar">
            <?php rcl_group_thumbnail('medium'); ?>
        </div>   
        <div class="sidebar-content">
            <?php rcl_group_area('sidebar'); ?>
        </div>
    </div>
<?php endif; ?>
<div class="group-wrapper">
    <div class="group-content">
        <?php if(!rcl_is_group_area('sidebar')): ?>
            <div class="group-avatar">
                <?php rcl_group_thumbnail('medium'); ?>
            </div>
        <?php endif; ?>
        <div class="group-metadata">
            <h1 class="group-name"><?php rcl_group_name(); ?></h1>
            <div class="group-description">
                <?php rcl_group_description(); ?>
            </div>
            <div class="group-meta">
                <p><b>Status:</b> <?php rcl_group_status(); ?></p>
            </div>
            <div class="group-meta">
                <p><b>Users:</b> <?php rcl_group_count_users(); ?></p>
            </div>
        </div>
        <?php if(rcl_is_group_area('content')) rcl_group_area('content'); ?>
        <h3 class="title-widget"><?php rcl_group_post_counter(); ?> <?php _e('posts','wp-recall'); ?></h3>
        <div class="group-posts-wrapper">
            <?php if ( have_posts() ) : ?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 text-center">
                            <a href="<?php echo get_home_url(); ?>/student-member-login?user=<?php echo get_the_author_meta('ID'); ?>">
                                <span class="author-avatar-img"><?php echo get_avatar( get_the_author_meta('ID') ); ?></span>
                                <?php the_author(); ?>
                            </a>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-9">                   
                            <h2><?php the_title(); ?></h2>
                            <?php the_content(); ?>
                            <p class="text-muted post-date"><?php the_time('d F Y, G:i'); ?></p>                            
                        </div>
                    </div>
                <?php endwhile; ?>
                <?php radiate_paging_nav(); ?>            
            <?php endif; ?>
        </div>
    </div>
</div>
<?php if(rcl_is_group_area('footer')): ?>
    <div class="group-footer">
        <?php rcl_group_area('footer'); ?>
    </div>
<?php endif; ?>

