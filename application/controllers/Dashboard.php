<?php



/**
 * Class Dashboard
 *
 * This is Dashboard Class
 * All Functionality related to
 * Dashboard will go in this class
 */
class Dashboard extends MY_Controller{

    /**
     * @var array
     *
     * User Profile Fields Array
     */

    public function __construct() {
        $this->authUserRequired = TRUE;
        parent::__construct();
        if($this->get_profile_percentage() < 100)
            redirect('profile/update');
    }

    /**
     * index
     *
     * Function to check user Role and
     * accordingly display appropriate Dashboard
     */
    public function index($myjobid = null) {
        // Check subscription plan
        $checksubscription =check_subscription();
        
        //$this->data['subscription']=check_subscription();
        $user = User::find(get_user('id'));                                          // Get Current user object
         //echo "<pre>"; print_r($user);
        
        $user_industries = $user->industries()->lists('id');  // agency industries                                        
        // Get Current user object
        //echo "<pre>#########"; print_r($user_industries);
        $user_plans = Users_plans::where('plan_id', 2)->where('status', 'Completed')->get();
        $users_plans_arr = array();
        foreach($user_plans as $k=>$v) {
         
             $today = date('Y-m-d H:i:s');
             $ts1 = strtotime($v->created_at);
             $ts2 = strtotime($today);

             $days_between = ceil(abs($ts2 - $ts1)/86400);
             if($v->no_of_days > $days_between) {

                    if($v->user_id != $user->id) {
                        
                        $user_found = User::find($v->user_id);
                       
                        $ind = $user_found->industries()->lists('id'); // user industries
                        
                        $intersect = array_intersect($ind,$user_industries); 
                        //echo "<pre>@@@@@@@@"; print_r($ind);
                       
                        if(!empty($intersect)) {
                            
                            $p_user[] = User::with('user_profile','favourite_agencies')->find($v->user_id);
                            // echo "<pre>"; print_r($platinum_user);
                            // $users_plans_arr[] = $v->user_id;
                        }

                    }
             }

        }
        if(isset($p_user)){
            $this->data['platinum_users'] = $p_user;
        }
        // echo "<pre>"; print_r($this->data['platinum_users']);exit;

        // exit;

        // echo "<pre>"; print_r($users_plans_arr);exit;




        $this->data['myJobs'] = $user->created_jobs()->open();                              // Get Jobs Created By current user
        $this->data['acceptedJobs'] = $user->accepted_jobs()->open();
        $this->data['myjobid'] = $myjobid;    

        $userType = get_user('type');

        if($userType == 'agency')
        {
            $this->data['latestBlogs'] = Admin_blogs::where('view_by' ,'!=', 'Employer')->where('status','=',1)->orderBy('created_at','desc')->get();
        }
        else
        {
            $this->data['latestBlogs'] = Admin_blogs::where('view_by' ,'!=', 'Agency')->where('status','=',1)->orderBy('created_at','desc')->get();
        }
        
        $this->data['meetingFields'] = $this->get_values();
        $this->data['timeZoneList'] = $this->generate_timezone_list();
		
		$myUser = User::find(get_user('id'))->id;
		
		$query = "SELECT last_job_type FROM users WHERE id='".User::find(get_user('id'))->id."';";
     
		$result123 = $this->db->query($query)->result();
		if(count($result123)>0 && $result123[0]->last_job_type != "")
		{
			$this->data['type_check'] = $result123[0]->last_job_type;
		}
		else
		{
			$query = "SELECT id,notify_preferred,user_id,created_at FROM jobs ORDER BY created_at DESC;";
			
			$result123 = Job::orderBy('created_at','desc')->first();
			$user_typ = User::where('id','=',$result123->user_id)->first();	
			
			if($result123 && $result123->notify_preferred==1)
			{
				$query = "SELECT * FROM preferred_agencies WHERE user_id='$result123->user_id' AND agency_id='".get_user('id')."';";
				if (count($this->db->query($query)->result())>0)
				{
					
					$this->data['type_check'] = $user_typ->type;
				}
				else
				{
					$result1234 = Job::where('notify_preferred','!=','1')->orderBy('created_at','desc')->first();
					$user_typ2 = User::where('id','=',$result123->user_id)->first();	
					$this->data['type_check'] = $user_typ2->type;
				}
			}
			elseif($result123)
			{
				$this->data['type_check'] = $user_typ->type;
			}
			else
			{
				$this->data['type_check'] = "";
			}
		}
		// Get the jobs Accepted by current user
        $userIndustrySpecialityAreas = $user->industries()->lists('id');
        if((count($userIndustrySpecialityAreas))){
            //$jobs = Job::active()->open()->where('user_id', '!=', get_user('id'))->whereIn('industry_id', $userIndustrySpecialityAreas);
            $jobs = Job::active()->open()->where('user_id', '!=', get_user('id'));
            if($user->type == 'agency'){
                $userProfessionSpecialityAreas = $user->professions();
                if($userProfessionSpecialityAreas->count())
                    //$jobs = $jobs->whereIn('profession_id', $userProfessionSpecialityAreas->lists('id'));
                    //echo '<pre>@@@@@@@@@@@';print_r($jobs->get());exit();
                $this->data['agency'] = User::with('user_profile')->find(get_user('id'));
                $this->data['agency']->load('agency_ratings', 'user_profile');
                $avgRating = 0;
                //$user_id = array();
				$rating_count=0;
				
				//Change reviews to start averaging after one review	
				if(count($this->data['agency']->agency_ratings)>0){
					foreach ($this->data['agency']->agency_ratings as $rating)
					{
						if($rating->pivot->rat_status==1)
							{
								$rating_count++;
							}
					}
				}
				$this->data['no_of_reviews'] = $rating_count;
				
				
                if($rating_count >= 7){
                    foreach ($this->data['agency']->agency_ratings as $rating) {
                        if($rating->pivot->rat_status==1)
						{
							$avgRating += $rating->pivot->rating;
						}
                    }
					if($rating_count>=1){

                        $avgRating = $avgRating/$rating_count;
                   }else{
                         $avgRating =0;
                   }
                }
				
                $this->data['avgRating'] = $avgRating;
            }

            $this->data['agency'] = User::with('user_profile')->find(get_user('id'));
            $this->data['agency']->load('agency_ratings', 'user_profile');

            $rejected_rater = array();
            foreach ($this->data['agency']->agency_ratings as $rating) {
                if($rating->pivot->rating <= 2.5){
                    $rejected_rater[] = $rating->pivot->user_id;
                }
            }
            $this->data['rejected_rater'] = $rejected_rater;

            $exclude =  array_merge($user->accepted_jobs()->lists('id'), $user->rejected_jobs()->lists('id'), $this->getJobsAcceptedByMax());

            /*echo "<pre>@@@@";
            print_r($exclude);
            exit;
*/
            if(!empty($checksubscription))
            {
                $this->data['subscription']=check_subscription();
                if($this->data['subscription']['dstatus'] == "")
                {
                    $this->data['job_user_type'] = array('employer');
                }else{
                    $this->data['job_user_type'] = array('employer','agency');
                }
            }

            //echo "<pre>=================================";
           // print_r( $jobs->orderBy('updated_at','desc')->get());
            $this->data['newJobsAlert'] = (count($exclude)) ? $jobs->whereNotIn('id',$exclude)->orderBy('created_at', 'desc')->get() : $jobs->get();

                       //echo '<pre>+++++++++++++++++++++';print_r($jobs->orderBy('updated_at','desc')->get());
                        //exit;
        }else{
            $this->data['newJobsAlert']  = [];
        }
        //print_r($this->data['agency']['id']); //16
//        print_r($user['id']); //16
//        print_r($this->data['newJobsAlert'][0]['id']); //47
//        print_r($this->data['newJobsAlert'][0]['user_id']); //6
//        print_r($this->data['newJobsAlert'][0]['industry_id']); //2

        $stats_job = ['jobs_posted', 'jobs_closed', 'candidates_accepted', 'candidates_declined', 'candidates_hired'];
        $stats_so=array();
        if($user->type == 'agency')
            $stats_so = ['so_accepted', 'so_candidates_accepted', 'so_candidates_declined', 'so_candidates_hired'];

        foreach ($stats_job as $stat){
            $methodName =  "get_$stat".'_count';
            $this->data['stats_job'][$stat] = $this->$methodName($user->id);
        }

        if(isset($stats_so)){
            foreach ($stats_so as $stat){
                $methodName =  "get_$stat".'_count';
                $this->data['stats_so'][$stat] = $this->$methodName($user->id);
            }
        }

        $this->update_latest_messaging();

        $userData = $this->session->userdata();
        $this->data['userType'] = $userData['type'];
        /*Reject Reason options list*/
        $this->data['reject_reasons'] = My_reject_reasons::withTrashed()->orderBy('reject_option')->get();
        $this->load->blade($this->user['type'].'/dashboard', $this->data);
    }



function  update_notification_status($id){

        $notification = User_notifications::find($id);
        if(isset($notification)){
            $notification->status = 1;
            $notification->cn_status = 1;
        }

        $notification->save();
        redirect(base_url($notification->notification_url));
    }

function  add_notificatio($id){

        $notification = User_notifications::find($id);
        if(isset($notification)){
            $notification->status = 1;
             $notification->cn_status = 1;
        }

        $notification->save();
        redirect(base_url('dashboard'));
    }

private function get_values(){

    $meetingFields = [
        [
            'name' => 'meeting_title',
            'type' => 'text',
            'placeholder' => 'Meeting Title',
            'validation' => 'required'
        ],
        [
            'name' => 'meeting_location',
            'type' => 'text',
            'placeholder' => 'Meeting Location',
            'validation' => 'required'
        ],
    ];
    return $meetingFields;
}

function generate_timezone_list()
{
    static $regions = array(
        DateTimeZone::AFRICA,
        DateTimeZone::AMERICA,
        DateTimeZone::ANTARCTICA,
        DateTimeZone::ASIA,
        DateTimeZone::ATLANTIC,
        DateTimeZone::AUSTRALIA,
        DateTimeZone::EUROPE,
        DateTimeZone::INDIAN,
        DateTimeZone::PACIFIC,
    );
 
    $timezones = array();
    foreach( $regions as $region )
    {
        $timezones = array_merge( $timezones, DateTimeZone::listIdentifiers( $region ) );
    }
 
    $timezone_offsets = array();
    foreach( $timezones as $timezone )
    {
        $tz = new DateTimeZone($timezone);
        $timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
    }
 
    // sort timezone by timezone name
    ksort($timezone_offsets);
 
    $timezone_list = array();
    foreach( $timezone_offsets as $timezone => $offset )
    {
        $offset_prefix = $offset < 0 ? '-' : '+';
        $offset_formatted = gmdate( 'H:i', abs($offset) );
 
        $pretty_offset = "UTC${offset_prefix}${offset_formatted}";
       
        $t = new DateTimeZone($timezone);
        $c = new DateTime(null, $t);
        $current_time = $c->format('g:i A');
 
        $timezone_list[$timezone] = "(${pretty_offset}) $timezone ";
    }
 
    return $timezone_list;
}

/* Modification done in update_latest_messaging system for the google inbox*/
function update_latest_messaging()
    {
        $hostname = '{imap.gmail.com:993/imap/ssl/novalidate-cert}[Gmail]/All Mail'; 
        $username = 'jeff.oliver@talentcapturerecruiting.com'; 
        $password = 'jGo0629!@#';

        //  try to connect 
        $inbox     = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
        $inbox     = imap_open($hostname,$username,$password);
        $mailboxes = imap_list($inbox, $hostname, '*');

        // grab emails 
        $emails = imap_search($inbox,'UNSEEN');

        // if emails are returned, cycle through each... 
        if($emails) 
        {
            $array = [];

            foreach($emails as $email_number) 
            {
                $body_temp     = imap_fetchbody($inbox,$email_number,2);
                $body          = explode('From:', $body_temp);
                // get information specific to this email 
                //$body      = imap_fetchbody($inbox,$email_number,1);
                $overview  = imap_fetch_overview($inbox,$email_number,0);
                $header    = imap_header($inbox, $email_number);
                $structure = imap_fetchstructure($inbox,$header->Msgno);
                $n_msgs    = imap_num_msg($inbox);

                if($overview[0]->subject!='' && strpos($overview[0]->subject, 'TalentCapture Messaging - T-') !== false)
                {
                    $subject   = explode('-C-', explode('TalentCapture Messaging - T-', $overview[0]->subject)['1']);
                    
                    $template = explode('-S-',$subject['1']);
                    
                    $conv_details = Candidate_mail_messaging::where('candidate_id','=',$subject['0'])->where('job_id','=',$template['0'])->first();
                    
                    $body = $body[0];

                    $body = str_replace('\r\n','-RN-FOUND-', $body);
                    
                    $body = str_replace('\r','-R-FOUND-', $body);
                    $body = str_replace('\n','-N-FOUND-', $body);
                    $body = str_replace('<o:p></o:p>','-OP-FOUND-', $body);
                    $body = str_replace('<o:p>&nbsp;</o:p>','-OP-FOUND-', $body);
                    $body = str_replace('<p style="3D&quot;margin-top:0;margin-bottom:0&quot;"><br>
</p>','-OP-FOUND-', $body);
                    $body = str_replace('<p style="3D&quot;margin-top:0;margin-bottom:0&quot;"><br></p>','-OP-FOUND-', $body);
                    $body = str_replace('<div><br></div>','-OP-FOUND-', $body);
                    $body = str_replace('<br>','-BRFOUND-', $body);

                    $body = strip_tags($body);

                    $body = str_replace('Content-Type: text/plain; charset="us-ascii"','', $body);

                    $body = str_replace('Content-Transfer-Encoding: quoted-printable','', $body);
                    
                    /*
                    $body = str_replace('\r','', $body);
                    $body = str_replace('\n','', $body);
                    $body = str_replace('\r\n','', $body);
                    $body = str_replace('<br>','', $body);
                    */

                    $body = str_replace('-OP-FOUND-','<br />', $body);
                    $body = str_replace('-R-FOUND-','<br />', $body);
                    $body = str_replace('-N-FOUND-','<br />', $body);
                    $body = str_replace('-RN-FOUND-','<br />', $body);
                    $body = str_replace('-BRFOUND-','<br />', $body);

                    $body = explode("=",$body);

                    foreach ($body as $key => $value) {
                        $body1[] = trim($value) ." ";
                    }

                    $body = implode("",$body1);

                    //$job_owner_detail = Users::find($conv_details->job_owner_id);
        
                    $msg_chat['job_owner_id'] = $conv_details->job_owner_id;

                    $msg_chat['candidate_owner_id'] = $conv_details->candidate_owner_id;
                    
                    $msg_chat['last_conversation'] = "<div style='padding-top: 10px;'>".$body."</div>"; // str_replace('>','<br>', $body);

                    $candidate_details = Candidate::find($subject['0']);

                    $msg_chat['company_name'] = "<h5 style='line-height: 0.1;'><b>To : </b>".ucwords(get_user('first_name').' '.get_user('last_name')). " @ " .$candidate_details->agency()->first()->user_profile()->first()->company_name ."\r\n\r\n"."</h5>";

                    $msg_chat['message_subject'] = "<h5 style='line-height: 0.1;padding-top: 10px;'><b>Subject :</b>RE: ".$template['1']."</h5>";

                    $msg_chat['last_conversation'] = "Message From: ". $candidate_details->name ."\r\n\r\n".$msg_chat['last_conversation'];

                    $msg_chat['candidate_id'] = $subject['0'];

                    $msg_chat['job_id'] = $template['0'];

                    $msg_chat['conversation_subject'] = $overview[0]->subject;

                    $conversation = Candidate_mail_messaging::create($msg_chat);

                    //Send a notification to job owner
                    $job_id = $template['0'];
                    $candidate_id = $subject['0'];
                    $job = Job::find($job_id);
                    $candidate = Candidate::find($candidate_id);

                    $user_notification = array(
                                "notification_text" => 'Reply received from the candidate '.ucfirst($candidate->name).' related to your job '.ucfirst($job->title),
                                "notification_url" => 'jobs/candidate_detail/'.$job_id .'/'.$candidate_id.'/email',
                                "user_id" => $job->user_id,
                                "status" => 0,
                                "cn_status" => 0
                            );



                    $notificationData = User_notifications::create($user_notification);

                    $recruiter_details = User::find($msg_chat['job_owner_id']);

                    $recruiter_email = $recruiter_details->email;

                    $recruiter_name = $recruiter_details->first_name.' '.$recruiter_details->last_name;

                    $agency_detail = User::find($msg_chat['candidate_owner_id']);

                    $agency_email = $agency_detail->email;

                    $agency_name = $agency_detail->first_name.' '.$agency_detail->last_name;

                    // Send CANDIDATE FEED BACK EMAIL
                    $email_variable33['NAME'] = ucfirst($recruiter_name);
                    $email_variable33['CANDIDATENAME'] = ucfirst($candidate_details->name);
                    $email_variable33['MSG'] = $body;
                    $email_variable33['url'] = '<a href="'.base_url().'jobs/candidate_detail/'.$template['0'].'/'.$subject['0'].'/email/'.$msg_chat['job_owner_id'].'">log into</a>';
                    $email_data33=email_template(33,$email_variable33);

                    if(isset($email_data33)){
                    $emailData33 = [
                        'to'        => $recruiter_email,
                        'bcc'       => ADMIN_EMAIL,
                        'from'      => WEBSITE_FROM_EMAIL,
                        'subject'   => $email_data33['template_subject']
                    ];

                    $emailData33['body'] = $email_data33['template_body'];
                    email($emailData33);
                    }

                    $email_variable34['NAME'] = ucfirst($agency_name);
                    $email_variable34['CANDIDATENAME'] = ucfirst($candidate_details->name);
                    $email_variable34['MSG'] = $body;
                    $email_variable34['url'] = '<a href="'.base_url().'searches/candidate_detail/'.$template['0'].'/'.$subject['0'].'/emp/none/email/'.$msg_chat['candidate_owner_id'].'">log into</a>';
                    $email_data34=email_template(34,$email_variable34);

                    if(isset($email_data34)){
                    $emailData34 = [
                        'to'        => $agency_email,
                        'bcc'       => ADMIN_EMAIL,
                        'from'      => WEBSITE_FROM_EMAIL,
                        'subject'   => $email_data34['template_subject']
                    ];

                    $emailData34['body'] = $email_data34['template_body'];
                    email($emailData34);
                    }

                    /*
                    $coversation= Candidate_mail_messaging::where('candidate_id','=',$subject['0'])->where('job_id','=',$subject['1'])->first();
                    $result['status'] = 1;
                    $result['update_conversation'] = $msg_chat['last_conversation'];
                    $result['msg'] = $this->load->blade('partials/_coversation',compact('coversation'),true);
                    echo json_encode($result); die();
                    */
                    //$msg_chat['last_conversation'] = '';
                    unset($body1);
                } 
            }
        }  
        // close the connection 
        imap_close($inbox);

        return true;
    }

}