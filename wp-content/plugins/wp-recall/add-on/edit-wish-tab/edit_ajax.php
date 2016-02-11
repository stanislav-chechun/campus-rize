<?php

 require($_SERVER['DOCUMENT_ROOT'].'/campus-rize/wp-load.php'); 

if( isset($_POST['wish_id'])){
    
    $post_id = sanitize_text_field($_POST['wish_id']);
    $wish_title = get_post_field( 'post_title', $post_id, 'display' );
    $wish_content = get_post_meta($post_id,'_give_form_content');
    $wish_goal =  get_post_meta($post_id,'_give_set_goal');
    $wish_youtube = get_post_meta($post_id,'youtube');
    $wish_vimeo = get_post_meta($post_id,'vimeo');
    
    //Form for editting   
    $html .= '<form  class="form-horizontal" enctype="multipart/form-data" id="wish_table_edit" method="post">';
                $html .= '<div class="form-group">';
                        $html .= '<label class="col-sm-4 control-label" for="title_form_edit">' . __( 'The title of your goal*: ') . '</label>';
                        $html .= '<div class="col-sm-8">';
                            $html .= '<input name="title_form_edit" class="form-control"  id="title_form_edit" placeholder="Title" '
                                    . 'required type="text" value="' .  $wish_title .'"/>';
                        $html .= '</div>';
                $html .= '</div>';
                
                $html .= '<div class="form-group">';
                        $html .= '<label class="col-sm-4 control-label" for="goal_form">' . __( 'The sum you need for your goal*: ') . '</label>';
                        $html .= '<div class="col-sm-8">';
                            $html .= '<input name="goal_form_edit" class="form-control"  id="goal_form_edit" placeholder="Amount" '
                                    . 'type="number" required value="' . intval($wish_goal[0]) .'"/>';
                        $html .= '</div>';
                $html .= '</div>';

                $html .= '<div class="form-group">';
                        $html .= '<label class="col-sm-4 control-label" for="content_form">' . __( 'The content you want to display*: ') . '</label>';
                        $html .= '<div class="col-sm-8">';
                            $html .= '<textarea id="content_form_edit" name="content_form_edit" class="form-control" required rows="10">' . 
                                    $wish_content[0]. '</textarea>';
                        $html .= '</div>';
                        
                $html .= '</div>';

                $html .= '<div class="form-group">';
                        $html .= '<label class="col-sm-4 control-label" for="youtube_form_edit">' . __( 'https://www.youtube.com/watch?v=: ') . '</label>';
                        $html .= '<div class="col-sm-8">';
                            $html .= '<input name="youtube_form_edit" class="form-control"  id="youtube_form_edit" '
                                    . 'placeholder="Fiil in the ID of your video from youtube.com" type="text" value="' .  $wish_youtube[0] .'"/>';
                            $html .= '<span id="helpBlock" class="help-block">';
                                $html .= 'For example: mgmVOuLgFB0';
                            $html .= '</span>';
                        $html .= '</div>';
                $html .= '</div>';
                
                $html .= '<div class="form-group">';
                        $html .= '<label class="col-sm-4 control-label" for="vimeo_form_edit">' . __( 'https://player.vimeo.com/video/ ') . '</label>';
                        $html .= '<div class="col-sm-8">';
                            $html .= '<input name="vimeo_form_edit" class="form-control"  id="vimeo_form_edit" '
                                    . 'placeholder="Fiil in the ID of your video from vimeo.com" type="text" value="' . 
                                    $wish_vimeo[0] .'"/>';
                            $html .= '<span id="helpBlock" class="help-block">';
                                $html .= 'For example: 149253903';
                            $html .= '</span>';
                        $html .= '</div>';
                $html .= '</div>';
                
                $html .= '<div class="form-group">';
                        $html .= '<label class="col-sm-4 control-label" for="image_form_edit">' . __( 'You can upload a photo for the goal') . '</label>';
                        $html .= '<div class="col-sm-4">';
                            $html .= '<input  type="file" name="image_form_edit" class="form-control"  id="image_form_edit" multiple="false" />';
                        $html .= '</div>';
                        $html .= '<div id="thumbnail_wish" class="col-sm-4">';
                            $html .= get_the_post_thumbnail($post_id);
                        $html .= '</div>';
                $html .= '</div>';

                $html .= '<input type="hidden" name="kp_wish_edit" value="process_kp_wish"/>';
                $html .= '<input type="hidden" name="post_id_edit" value="' . $post_id . '"/>';
                $html .=  wp_nonce_field('kp_nonce', 'kp_nonce');
                $html .= '<input class="wsite-button-white" type="reset" value="Reset">';
                $html .= '<input class="wsite-button" type="submit" value="Update">';
            $html .= '</form>'; 
            
       
    echo $html;
}