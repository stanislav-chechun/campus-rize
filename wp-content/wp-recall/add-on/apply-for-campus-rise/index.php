<?php

add_action('init','add_tab_apply_for_campus_rise');

	function add_tab_apply_for_campus_rise(){

		$user = $_GET['user'];
		$user_info = get_userdata($user);

		if ( $user_info->roles[0] == 'student' ){		
	    	rcl_tab('apply_for_campus', 'apply_for_campus_rise_showup', 'Apply for Campus Rise', array('public'=>0,'class'=>'fa-clone','order'=>20));
	    }
	}

	function apply_for_campus_rise_showup(){		

		$content .= "<p>If you consider yourself to be a high-achieving, low-income student who has recently been admitted to a college or university in Michigan, then you are a perfect fit for Campus Rise. 
						Due to the high levels of aid and service we provide our students, we can only commit to admitting 5 students during 2016. 
						These five students will receive Campus Rise benefits until they have graduated from college and secured a job in the industry of their choice.
					 </p>
					 <p>Admission to Campus Rise includes an application process, as well as financial and academic verification. 
					 	Below you will find an application checklist with all accompanying forms and documents. 
					 	<strong>Please create an applicant profile to track your progress.</strong> 
					 	Then, submit all documents using the forms below. 
					 	When you finish a form, you will see a check mark next to the item you completed.
					 </p>
					 <div id='begin-cra' class='wsite-button text-center'>Begin Your Campus Rise Application
					 </div>";
		/*$content .= '<form  class="form-horizontal" enctype="multipart/form-data" id="applicant-form" method="post">
                	 	<div class="form-group">
                        	<label class="col-sm-4 control-label" for="title_form">' . __( 'E-mail* ') . '</label>
                        	<div class="col-sm-8">
                            	<input name="af_email" class="form-control"  id="af_email" placeholder="E-mail" required type="email" value=""/>
                        	</div>
                	 	</div>
                	 	<div class="form-group">
                        	<label class="col-sm-4 control-label" for="title_form">' . __( 'Phone*<br /><span class="text-muted">format: +XX (XXX) XXX-XX-XX</span> ') . '</label>
                        	<div class="col-sm-8">
                            	<input name="af_phone" class="form-control"  id="af_phone" placeholder="Phone" required type="text" pattern="\+[0-9]{2} \([0-9]{3}\) [0-9]{3}-[0-9]{2}-[0-9]{2}" value=""/>
                        	</div>
                	 	</div>
                	 	<div class="form-group">
                        	<label class="col-sm-4 control-label" for="title_form">' . __( 'Date of birth* ') . '</label>
                        	<div class="col-sm-8">
                            	<input name="af_bdate" class="form-control"  id="af_bdate" placeholder="Date of birth" required type="date" value=""/>
                        	</div>
                	 	</div>';*/
        $content .= do_shortcode("[contact-form-7 id='423' title='Apply for Campus Rise - basic information']");
                
               /*            
          
                

                $html .= '<input type="hidden" name="kp_wish" value="process_kp_wish"/>';
                $html .=  wp_nonce_field('kp_nonce', 'kp_nonce');
                //$html .= '<p><input class="btn btn-default" type="submit" value="Create">';
                //$html .= '<input class="btn btn-default" type="reset" value="Reset"></p>';
                $html .= '<input class="wsite-button-white" type="reset" value="Reset">';*/
               
               /* $content .= '<input class="wsite-button pull-right" type="submit" value="Submit">';
            $content .= '</form>';*/

		return $content; 
	}

?>