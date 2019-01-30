<?php

// JWT PHP Library https://github.com/firebase/php-jwt
use \Firebase\JWT\JWT;

/**
 * common_helper.php
 *
 * This File Contains Common helper functions.
 */

if (!function_exists('p')){
    function p($data, $die = true){
        echo '<pre>'; print_r($data);
        if ($die)
            die('########');
    }
}

if (!function_exists('v')){
    function v($data, $die = true){
        echo '<pre>'; var_dump($data);
        if ($die)
            die('########');
    }
}

if(!function_exists('get_user')){
    function get_user($field = NULL){
        $CI =& get_instance();
        return ($field == NULL) ? $CI->session->userdata() : $CI->session->userdata($field);
    }
}

if(!function_exists('get_admin_user')){
    function get_admin_user($field = NULL){
        $CI =& get_instance();
        $adminUser = $CI->session->userdata('admin');
        return ($field == NULL) ? $adminUser  : $adminUser[$field];
    }
}

if(!function_exists('generateRandomString')){
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if(!function_exists('upload_file')){
    function upload_file($fileName, $config = null){

        if($config == null){
            $config['upload_path']          = APPPATH.'../public/uploads/';
            $config['allowed_types']        = 'gif|jpg|png|pdf';
//            $config['file_name']            = time(). ".". pathinfo($file, PATHINFO_EXTENSION);
            /*$config['max_size']             = 100;
            $config['max_width']            = 1024;
            $config['max_height']           = 768;*/

        }

        $CI = &get_instance();
        $CI->load->library('upload', $config);

        $result['success'] = $CI->upload->do_upload($fileName);
        $result['upload'] = $CI->upload;
        return$result;
    }
}


if(!function_exists('email')){
    function email($emailData){
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = SMTP_HOSTNAME;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        $mail->setFrom($emailData['from']);
        //$mail->addAddress($emailData['to']);
        //$mail->addAddress('lakmal.silva@perituza.com');
        $mail->addAddress('bobsteventexas1990@gmail.com');
        
        if(isset($emailData['bcc']) && $emailData['bcc'] != ""){
            $mail->addBCC($emailData['bcc']);
        }
        //$mail->addCC("jeff.oliver@rainmakercr.com");
        $mail->isHTML(true);
        $mail->Subject = $emailData['subject'];
        $mail->Body    = $emailData['body'];
        $mail->AltBody = strip_tags($emailData['body']);
        $mail->send();
        return Mail::create($emailData);
    }
}

if(!function_exists('email_resume')){
    function email_resume($emailData){
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = SMTP_HOSTNAME;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        $mail->setFrom($emailData['from']);
        //$mail->addAddress($emailData['to']);
        $mail->addAddress('lakmal.silva@perituza.com');
        if(isset($emailData['bcc']) && $emailData['bcc'] != ""){
            $mail->addBCC($emailData['bcc']);
        }
        //$mail->addCC("jeff.oliver@rainmakercr.com");
        $mail->isHTML(true);
        $mail->Subject = $emailData['subject'];
        $mail->Body    = $emailData['body'];
        $mail->AltBody = strip_tags($emailData['body']);

        $mail->addAttachment($emailData['resume']);
        $mail->send();
        return Mail::create($emailData);
    }
}

if(!function_exists('email_on_message')){
    function email_on_message($emailData){
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = SMTP_HOSTNAME;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        $mail->setFrom($emailData['from']);
        //$mail->addAddress($emailData['to']);
        $mail->addAddress('lakmal.silva@perituza.com');
        if(isset($emailData['bcc']) && $emailData['bcc'] != ""){
            $mail->addBCC($emailData['bcc']);
        }
        //$mail->addCC("appdev@perituza.com");
        $mail->isHTML(true);
        $mail->Subject = $emailData['subject'];
        $mail->Body    = $emailData['body'];
        $mail->AltBody = $emailData['body'];
        $mail->send();
        return Mail::create($emailData);
    }
}


if(!function_exists('admin_url')){
    function admin_url($path = NULL){
        return base_url('admin/'.$path);
    }
}

if(!function_exists('admin_assets_url')){
    function admin_assets_url($path = NULL){
        return base_url('public/admin_assets/'.$path);
    }
}

if(!function_exists('get_awaiting_approval')){
    function get_awaiting_approval($modelName){
        $count = count($modelName::where(['status'=>0])->get());
        return $count;
    }
}

if(!function_exists('flash_msg')){
    function flash_msg(){
        $CI =& get_instance();
        return $CI->session->flashdata('msg');
    }
}

if(!function_exists('check_current_page')){
    function check_current_page($page, $segment = 2){
        $CI =& get_instance();
        return ($page == $CI->uri->segment($segment)) ? 'class="active"' : '';
    }
}

if(!function_exists('get_random_class')){
    function get_random_class(){
        $availableClasses = ['success', 'info', 'danger','warning'];
        return $availableClasses[rand(0,3)];
    }
}

if(!function_exists('time_since')){
    function time_since($timestamp){
        $datetime1=new DateTime("now");
        $datetime2=date_create($timestamp);
        $diff=date_diff($datetime1, $datetime2);
        $timemsg='';
        if($diff->y > 0){
            $timemsg = $diff->y .' year'. ($diff->y > 1?"'s":'');

        }
        else if($diff->m > 0){
            $timemsg = $diff->m . ' month'. ($diff->m > 1?"'s":'');
        }
        else if($diff->d > 0){
            $timemsg = $diff->d .' day'. ($diff->d > 1?"'s":'');
        }
        else if($diff->h > 0){
            $timemsg = $diff->h .' hour'.($diff->h > 1 ? "'s":'');
        }
        else if($diff->i > 0){
            $timemsg = $diff->i .' minute'. ($diff->i > 1?"'s":'');
        }
        else if($diff->s > 0){
            $timemsg = $diff->s .' second'. ($diff->s > 1?"'s":'');
        }

        $timemsg = $timemsg.' ago';
        return $timemsg;
    }
}

if(!function_exists('date_difference')){
    function date_difference($date1,$date2){
        $date1=date_create($date1);
        $date2=date_create($date2);
        $diff=date_diff($date1,$date2);
        return $diff->days;
    }
}


if(!function_exists('check_subscription')) {
    function check_subscription(){
        $data=array();
        $user = User::find(get_user('id'));
        $checkusertrial = Free_trial::where('agency_id','=',get_user('id'))->count();
        if($checkusertrial > 0)
        {
            $freetrial = Free_trial::where('agency_id','=',get_user('id'))->get()->toArray();    
        }
        else
        {
            $freetrial = Free_trial::where('agency_id','=','0')->get()->toArray();   
        }
        if($user->type == 'agency'){
            if ($user->is_trial == '0'){

                // echo "Trail Period Expired";
                // echo "<pre>";

                $user_plans_obj = Users_plans::where(['user_id' => get_user('id')])->orderBy('created_at', 'desc')->limit('1')->first();

                if (!isset($user_plans_obj)) {
                    //echo "No plans bought yet";
                    $date1 = date("Y-m-d", strtotime($user->created_at));
                    $date2 = date("Y-m-d");

                    // echo "Date1 : ".$date1."<br/>";
                    // echo "Date2 : ".$date2."<br/>";
                    // echo "Free Trial Days".$freetrial[0]['no_of_days']."<br/>";
                    // echo date_difference($date1, $date2);
                
                    
                    if ($freetrial[0]['no_of_days'] < date_difference($date1, $date2)) {
                        $data['type'] = 'danger';
                        $data['dstatus'] = 'inactive';
                        $errmsg = Site_messages::where('type','=','subscription_trial')->first();
                        $data['msg'] = $errmsg->msg.' by <a href="' . base_url("agency/subscription_plans") . '">clicking here.</a>';
                    }else{
                        $user->is_trial = '1';
                        $user->save();
                        $data['type'] = 'info';
                        $data['dstatus'] = 'active';
                        $errmsg = Site_messages::where('type','=','trial')->first();
                        $data['msg'] = $errmsg->msg;
                    }
                } else {
                    //echo "Plans exist for this user";
                    
                    $user_plans = $user_plans_obj->toArray();
                    //print_r($user_plans);
                    $date1 = date("Y-m-d", strtotime($user_plans['created_at']));
                    $date2 = date("Y-m-d");

                    // echo "Date1 : ".$date1."<br/>";
                    // echo "Date2 : ".$date2."<br/>";
                    // echo "Plans Days".$user_plans['no_of_days']."<br>"; 
                    // echo date_difference($date1, $date2);

                    if ($user_plans['no_of_days'] < date_difference($date1, $date2)) {
                        //echo "Plan expired";
                        $data['type'] = 'danger';
                        $data['dstatus'] = 'inactive';
                        $errmsg = Site_messages::where('type','=','subscription_trial')->first();
                        $data['msg'] = $errmsg->msg.' by <a href="' . base_url("agency/subscription_plans") . '">clicking here.</a>';
                    } else {
                        //echo "Plan is going on";
                        if ($user_plans_obj->status != 'Completed') {
                            $data['type'] = 'danger';
                            $data['dstatus'] = 'inactive';
                            $errmsg = Site_messages::where('type','=','pending_payment')->first();
                            $data['msg'] = $errmsg->msg;
                            //$data['msg'] = 'Your Payment Process is still pending';
                        } else {
                            $data['my_plan'] = $user_plans_obj;
                            $data['dstatus'] = 'active';
                            $data['type'] = '';
                            $data['msg'] = '';
                            //print_r($data);
                        }

                    }
                }
            } else if ($user->is_trial == '1') {

                //echo "Trail Period Going on";
                //$date1 = date("Y-m-d", strtotime($user->created_at));
                if($checkusertrial>0){
                    $date1 = date("Y-m-d", strtotime($freetrial[0]['created_at']));
                }else{
                     $date1 = date("Y-m-d", strtotime($user->created_at));
                }
                $date2 = date("Y-m-d");

                // echo "Date1 : ".$date1."<br/>";
                // echo "Date2 : ".$date2."<br/>";
                // echo "Free Trial Days".$freetrial[0]['no_of_days']."<br/>";

                // echo date_difference($date1, $date2);
                
                if ($freetrial[0]['no_of_days'] < date_difference($date1, $date2)) {
                    $user->is_trial = '0';
                    $user->save();
                    $data['type'] = 'danger';
                    $data['dstatus'] = 'inactive';
                    $errmsg = Site_messages::where('type','=','subscription_trial')->first();
                    $data['msg'] = $errmsg->msg.' by <a href="' . base_url("agency/subscription_plans") . '">clicking here.</a>';
                }else{
                    $data['type'] = 'info';
                    $data['dstatus'] = 'active';
                    $trialmsg = Site_messages::where('type','=','trial')->first();
                    $data['msg'] = $trialmsg->msg;
                    //$data['msg'] = 'You are currently participating in a free trial period for the agency split fee network.';
                }

            }
        }
        return $data;
    }
}

// Create Dynamic template

if(!function_exists('email_template')){
    function email_template($id,$variable = array()){
        $templates = Email_templates::where('id','=',$id)->where('status','=','1')->get()->toArray();
        if(!empty($templates)){
            foreach($templates as  $template){
                foreach ($variable as $key => $value) {
                    $key = '$'.$key.'$';
                    $template['template_body'] = str_replace($key, $value, $template['template_body']);
                    $template['template_subject'] = str_replace($key, $value, $template['template_subject']);
                }
            }
            if($id == 18) {
                $template['template_body'] = str_replace('@SITEURL@','<a href="'.site_url("messages").'">', $template['template_body']);
            } else {
                $template['template_body'] = str_replace('@SITEURL@','<a href="'.site_url("login").'">', $template['template_body']);
            }
            
            if(isset($variable['RESETLINK'])){
                $template['template_body'] = str_replace('@RESETLINK@','<a href="'.$variable['RESETLINK'].'">', $template['template_body']);
            }

            if(isset($variable['GOOGLELINK'])){
                $template['template_body'] = str_replace('@GOOGLELINK@','<a href="'.$variable['GOOGLELINK'].'">', $template['template_body']);
            }

            if(isset($variable['OUTLOOKLINK'])){
                $template['template_body'] = str_replace('@OUTLOOKLINK@','<a href="'.$variable['OUTLOOKLINK'].'">', $template['template_body']);
            }

            if(isset($variable['MEETINGURL'])){
                $template['template_body'] = str_replace('@MEETINGURL@','<a href="'.$variable['MEETINGURL'].'">', $template['template_body']);
            }

            $template['template_body'] = str_replace('!@','</a>', $template['template_body']);
            $template['template_subject'] = str_replace('@SITEURL@','<a href="'.site_url("login").'">', $template['template_subject']);
            $template['template_subject'] = str_replace('!@','</a>', $template['template_subject']);
            return $template;
        }else{
            return null;
        }
    }
}

if(!function_exists('get_email_template_name')){
    function get_email_template_name($id)
    {
        $template_name = '';
        $template = Email_templates::find($id);
        if(isset($template)){
            $template_name = $template->template_name;
        }

        return $template_name;
    }
}


if(!function_exists('UpdateNotificationStatus')){
    function UpdateNotificationStatus($id)
    {
        
        $notification = User_notifications::find($id);
        if(isset($notification)){
            $notification->status = 1;
            $notification->cn_status = 1;
        }

        $notification->save();
        //redirect(base_url('dashboard'));
    }
}

if(!function_exists('generateJWT')){
//function to generate JWT
    function generateJWT () {
        //Zoom API credentials from https://developer.zoom.us/me/
        $key = 'h5LltYdcTJitRGSdspCjBw';
        $secret = 'psbHEcsiSe1HS0uxz2o1IKiEove8eiNqEG8W';
        $token = array(
            "iss" => $key,
            // The benefit of JWT is expiry tokens, we'll set this one to expire in 1 minute
            "exp" => time() + 60
        );

        return JWT::encode($token, $secret);
    }
}


if(!function_exists('createZoomUsers')){
    function createZoomUsers ($userId) {

        $user = User::find($id);
        
        //list users endpoint GET https://api.zoom.us/v2/users
        $ch = curl_init('https://api.zoom.us/v2/users');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      
        // add token to the authorization header
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->generateJWT()
        ));

        $userData = '{
            "action": "custCreate",
            "user_info": {
                "email": "'.$user->email.'",
                "type": 1,
                "first_name": "'.$user->first_name.'",
                "last_name": "'.$user->last_name.'",
                "password": "TalentCapture@2018"
            }
        }';

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $userData);

        $response = curl_exec($ch);
        
        $response = json_decode($response);
        
        return $response;
    }
}

if(!function_exists('getUsers')){
    function getUsers ($userId) {
        //list users endpoint GET https://api.zoom.us/v2/users
        $ch = curl_init('https://api.zoom.us/v2/users/jeff.oliver@rainmakercr.com');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      
        // add token to the authorization header
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $this->generateJWT()
        ));

        $response = curl_exec($ch);

        $response = json_decode($response);
        
        return $response;
    }
}