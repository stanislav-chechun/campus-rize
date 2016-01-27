<?php global $rcl_user,$rcl_users_set; ?>
<div class="user-single">
    <div class="thumb-user">
        <a title="<?php rcl_user_name(); ?>" href="<?php echo get_home_url(); ?>/student-member-login?user=<?php echo $rcl_user->ID; ?>">
            <?php rcl_user_avatar(50); ?>
            <?php rcl_user_action(); ?>
        </a>
    </div>
</div>