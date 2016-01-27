<?php

 require($_SERVER['DOCUMENT_ROOT'].'/campus-rize/wp-load.php'); 

if( isset($_POST['wish_id'])){
    
     $post_id = sanitize_text_field($_POST['wish_id']);
     $wish_content = get_post_meta($post_id,'_give_form_content');
        echo 'Пример 1 - передача завершилась успешно ' . $post_id;
     
   
    $html .= '<form  class="form-horizontal" enctype="multipart/form-data" id="wish_table" method="post">';
                $html .= '<div class="form-group">';
                        $html .= '<label class="col-sm-4 control-label" for="title_form">' . __( 'The title of your goal*: ') . '</label>';
                        $html .= '<div class="col-sm-8">';
                            $html .= '<input name="title_form" class="form-control"  id="title_form" placeholder="Title" required type="text" value=""/>';
                        $html .= '</div>';
                $html .= '</div>';
                
                $html .= '<div class="form-group">';
                        $html .= '<label class="col-sm-4 control-label" for="goal_form">' . __( 'The sum you need for your goal*: ') . '</label>';
                        $html .= '<div class="col-sm-8">';
                            $html .= '<input name="goal_form" class="form-control"  id="goal_form" placeholder="Amount" type="number" required value=""/>';
                        $html .= '</div>';
                $html .= '</div>';

                $html .= '<div class="form-group">';
                        $html .= '<label class="col-sm-4 control-label" for="content_form">' . __( 'The content you want to display*: ') . '</label>';
                        $html .= '<div class="col-sm-8">';
                            $html .= '<textarea id="content_form" name="content_form" class="form-control" required rows="10">' . 
                                    $wish_content[0]. '</textarea>';
                        $html .= '</div>';
                        
                $html .= '</div>';

                $html .= '<div class="form-group">';
                        $html .= '<label class="col-sm-4 control-label" for="youtube_form">' . __( 'https://www.youtube.com/watch?v=: ') . '</label>';
                        $html .= '<div class="col-sm-8">';
                            $html .= '<input name="youtube_form" class="form-control"  id="youtube_form" placeholder="Fiil in the ID of your video from youtube.com" type="text" value=""/>';
                            $html .= '<span id="helpBlock" class="help-block">';
                                $html .= 'For example: mgmVOuLgFB0';
                            $html .= '</span>';
                        $html .= '</div>';
                $html .= '</div>';
                
                $html .= '<div class="form-group">';
                        $html .= '<label class="col-sm-4 control-label" for="vimeo_form">' . __( 'https://player.vimeo.com/video/ ') . '</label>';
                        $html .= '<div class="col-sm-8">';
                            $html .= '<input name="vimeo_form" class="form-control"  id="vimeo_form" placeholder="Fiil in the ID of your video from vimeo.com" type="text" value=""/>';
                            $html .= '<span id="helpBlock" class="help-block">';
                                $html .= 'For example: 149253903';
                            $html .= '</span>';
                        $html .= '</div>';
                $html .= '</div>';
                
                $html .= '<div class="form-group">';
                        $html .= '<label class="col-sm-4 control-label" for="image_form">' . __( 'You can upload a photo for the goal') . '</label>';
                        $html .= '<div class="col-sm-8">';
                            $html .= '<input  type="file" name="image_form" class="form-control"  id="image_form" multiple="false" />';
                        $html .= '</div>';
                $html .= '</div>';

                $html .= '<input type="hidden" name="kp_wish" value="process_kp_wish"/>';
                $html .=  wp_nonce_field('kp_nonce', 'kp_nonce');
                $html .= '<p><input class="btn btn-default" type="submit" value="Create">';
                $html .= '<input class="btn btn-default" type="reset" value="Reset"></p>';
            $html .= '</form>'; 
            
       
    echo $html;
}