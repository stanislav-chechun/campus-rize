<?php global $rcl_user,$rcl_users_set; ?>
<div class="user-single">
    <div class="thumb-user">
        <a title="<?php rcl_user_name(); ?>" href="<?php rcl_user_url(); ?>">
            <?php rcl_user_avatar(125); ?>
            <?php rcl_user_action(); ?>
            <h3 class="user-name text-center">
	            <a href="<?php rcl_user_url(); ?>"><?php rcl_user_name(); ?></a>
	        </h3>
        </a>
        <?php rcl_user_rayting(); ?>
    </div>
</div>