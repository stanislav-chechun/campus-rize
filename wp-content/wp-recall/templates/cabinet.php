<?php global $rcl_options,$user_LK; ?>

<?php rcl_before(); ?>

<div id="rcl-<?php echo $user_LK; ?>" class="wprecallblock">
    <?php rcl_notice(); ?>

    <div id="lk-conteyner">
        <div class="lk-header rcl-node">
            <?php rcl_header(); ?>
        </div>
        <!--<div class="lk-sidebar">
            <div class="lk-avatar">
                <?php //rcl_avatar(120); ?>
            </div>
            <div class="rcl-node">
                <?php rcl_sidebar(); ?>
            </div>
        </div>-->
        <!--<div class="lk-content">
            <h2><?php rcl_username(); ?>
            </h2>
            <div class="rcl-action">
                <?php rcl_action(); ?>
            </div>
            <p class="text-muted"><?php //$user = $_GET['user']; $user_info = get_userdata($user); echo $user_info->roles[0]; ?></p>
            <div class="rcl-user-status">
                <?php rcl_status_desc(); ?>
            </div>
            <div class="rcl-content">
                <?php rcl_content(); ?>
            </div>
            <div class="lk-footer rcl-node">
                <?php rcl_footer(); ?>
            </div>
        </div>-->

    </div>

    <?php $class = (isset($rcl_options['buttons_place'])&&$rcl_options['buttons_place']==1)? "left-buttons":""; ?>
    <div id="rcl-tabs">
        <div id="lk-menu" class="rcl-menu <?php echo $class; ?> rcl-node">

            <div class="lk-sidebar">
                <div class="lk-avatar">
                    <?php rcl_avatar(120); ?>
                </div>
                <div class="rcl-node">
                    <?php rcl_sidebar(); ?>
                </div>
            </div>
            <div class="lk-content">
                <h2><?php rcl_username(); ?>
                </h2>
                <div class="rcl-action">
                    <?php rcl_action(); ?>
                </div>
                <p class="text-muted"><?php $user = $_GET['user']; $user_info = get_userdata($user); echo $user_info->roles[0]; ?></p>
                <div class="rcl-user-status">
                    <?php rcl_status_desc(); ?>
                </div>
                <div class="rcl-content">
                    <?php rcl_content(); ?>
                </div>
                <div class="lk-footer rcl-node">
                    <?php rcl_footer(); ?>
                </div>
            </div>

            <?php rcl_buttons(); ?>
        </div>
        <!--<div id="chat-inner-tabs">
            <?php 
                //echo rcl_get_button_tab(array('name'=>'Contacts','id_tab'=>'contacts'));
                //echo rcl_get_button_tab(array('name'=>'Groups','id_tab'=>'groups'));
                //echo rcl_get_button_tab(array('name'=>'History','id_tab'=>'privat'));  
            ?>
        </div>-->
        <div id="lk-content" class="rcl-content">
            <div id="chat-inner-tabs">
            <?php 
                echo rcl_get_button_tab(array('name'=>'Contacts','id_tab'=>'contacts'));
                echo rcl_get_button_tab(array('name'=>'Groups','id_tab'=>'groups'));
                echo rcl_get_button_tab(array('name'=>'History','id_tab'=>'privat'));  
            ?>
        </div>
            <?php rcl_tabs(); ?>
        </div>
    </div>
</div>

<?php rcl_after(); ?>

