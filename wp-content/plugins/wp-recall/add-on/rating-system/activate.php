<?php
global $wpdb;
$table = RCL_PREF."rating_values";
if($wpdb->get_var("show tables like '". $table . "'") != $table) {
	   $wpdb->query("CREATE TABLE IF NOT EXISTS `". $table . "` (
	  ID bigint (20) NOT NULL AUTO_INCREMENT,
	  user_id INT(20) NOT NULL,
	  object_id INT(20) NOT NULL,
	  object_author INT(20) NOT NULL,
	  rating_value VARCHAR(5) NOT NULL,
          rating_date DATETIME NOT NULL,
          rating_type VARCHAR(20) NOT NULL,
	  UNIQUE KEY id (id)
	) DEFAULT CHARSET=utf8;");
}

$table = RCL_PREF."rating_totals";
if($wpdb->get_var("show tables like '". $table . "'") != $table) {
	   $wpdb->query("CREATE TABLE IF NOT EXISTS `". $table . "` (
	  ID bigint (20) NOT NULL AUTO_INCREMENT,
	  object_id INT(20) NOT NULL,
          object_author INT(20) NOT NULL,
	  rating_total VARCHAR(10) NOT NULL,
          rating_type VARCHAR(20) NOT NULL,
	  UNIQUE KEY id (id)
	) DEFAULT CHARSET=utf8;");
}

$table = RCL_PREF."rating_users";
if($wpdb->get_var("show tables like '". $table . "'") != $table) {
	   $wpdb->query("CREATE TABLE IF NOT EXISTS `". $table . "` (
	  user_id INT(20) NOT NULL,
	  rating_total VARCHAR(10) NOT NULL,
	  UNIQUE KEY id (user_id)
	) DEFAULT CHARSET=utf8;");

}

$table = RCL_PREF."rayting_post";
if($wpdb->get_var("show tables like '". $table . "'") == $table) {
    include_once 'migration.php';
    rcl_update_rating_data();
}

global $rcl_options;
if(!isset($rcl_options['rating_post'])){
    $rcl_options['rating_post'] = 1;
    $rcl_options['rating_comment'] = 1;
    $rcl_options['rating_type_post'] = 0;
    $rcl_options['rating_type_comment'] = 0;
    $rcl_options['rating_point_post'] = 1;
    $rcl_options['rating_point_comment'] = 1;
    $rcl_options['rating_user_post'] = 1;
    $rcl_options['rating_user_comment'] = 1;
    update_option('primary-rcl-options',$rcl_options);
}
