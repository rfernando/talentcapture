<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;


class Agency extends MY_Controller{

    public function __construct() {
        $this->authUserRequired = TRUE;
        parent::__construct();

        $this->data['agencies'] = User::find(get_user('id'))->favourite_agencies()->with('user_profile')->get();

        if($this->get_profile_percentage() < 100)
            redirect('profile/update');
    }

    public function index(){
        $this->data['page'] = 'favourites._search';
        $this->load->blade('favourites/agencies', $this->data);
    }

    public function detail($agency_id) {
        $this->data['agency'] = User::with('user_profile')->find($agency_id);
        $this->data['agency']->load('agency_ratings', 'user_profile');
        $avgRating = 0;
		$rating_count=0;

		//Change reviews to start averaging after one review	
		if(count($this->data['agency']->agency_ratings)>0){
            foreach ($this->data['agency']->agency_ratings as $rating)
                {
					if($rating->pivot->rat_status==1)
						{
							$avgRating += $rating->pivot->rating;
							$rating_count++;
						}
				}
			
            if($rating_count>=1){

                        $avgRating = $avgRating/$rating_count;
                   }else{
                         $avgRating =0;
                   }
        }
        $this->data['avgRating'] = $avgRating;

        $userFavAgenciesList = User::find(get_user('id'))->favourite_agencies()->lists('id');
        $this->data['isFavourite'] = in_array($agency_id, $userFavAgenciesList);

        $userType = User::find($agency_id)->type;

        if($userType == 'agency')
        {
            $this->data['page'] = 'favourites._agency_detail';
            $this->load->blade('favourites/agencies', $this->data);
        }
        else
        {
            $this->data['page'] = 'favourites._emp_detail';
            $this->load->blade('favourites/emp', $this->data);
        }
    }

    public function remove_favourite($agency_id) {
        $user = User::find(get_user('id'));
        $user->favourite_agencies()->detach($agency_id);
        redirect(base_url('agency'));
    }


    public function add_favourite($agency_id) {
        $user = User::find(get_user('id'));
        $user->favourite_agencies()->attach($agency_id);
        redirect(base_url('agency'));
    }

    public function update_agent_response($rsvp, $agent_id = NULL) {
        $rsvp = strtolower($rsvp);
        if(!in_array($rsvp, ['accepted', 'rejected'])) die('Invalid URL');
        $response = ['status'=>0, 'msg'=> ''];

        try{

            if($rsvp == 'rejected'){
                $this->db->insert($rsvp.'_agencies', ['user_id'=>get_user('id'), 'agency_id'=>$agent_id, 'created_at' => date('Y-m-d H:i:s')]);
                //$msg = ($rsvp == 'accepted') ? 'Job has been added to your Search List' : 'Job has been removed and you will not see it again.';
                $msg = 'Agency Removed';
                $response = ['status'=>1, 'msg'=> $msg];
            }
            if($rsvp == 'accepted')
            {
                $user = User::find(get_user('id'));
                $user->favourite_agencies()->attach($agent_id);
                $msg = 'Agency Approved';
                $response = ['status'=>1, 'msg'=> $msg];
            }
        }
        catch (ModelNotFoundException $e){
            $response['msg'] = '';
        }
        if($this->input->is_ajax_request()){
            echo json_encode($response); die();
        }
        else{
            $array = ['error', 'success'];
            $this->set_flashMsg($array[$response['status']], $response['msg'] );
            //redirect(base_url($redirectURL));
        }
        echo json_encode($response); die();
    }

    public function update_job_response($rsvp, $job_id = NULL) {
	//below section is commented regarding RP-738 this was ent to update the last viewed job type (Agency type or Employer type)	
	//this feature needs to be disabled since it affect the requirements of RP-738
//		$query = "SELECT users.type FROM users, jobs WHERE users.id=jobs.user_id AND jobs.id = '$job_id';";
//		$result123 = $this->db->query($query)->result();
//
//		$save_type = $result123[0]->type;
//		
//		$query = "UPDATE users SET last_job_type ='$save_type' WHERE id='".get_user('id')."';";
//		$this->db->query($query);
			
		
        $rsvp = strtolower($rsvp);

        if(!in_array($rsvp, ['accepted', 'rejected'])) die('Invalid URL');
        $response = ['status'=>0, 'msg'=> ''];

        try{

            $job = Job::findOrFail($job_id);

            $jobsAcceptedByLimitReached = $this->db->select('job_id')->from('accepted_jobs')->where('job_id ='.$job_id)->where('estatus != 2')->get()->result_array();
  
            if($rsvp == 'rejected' || ($job->add_agency == 0 && count($jobsAcceptedByLimitReached) < 5 ) || ($job->add_agency == 1 && count($jobsAcceptedByLimitReached) < 8 )) {

				$query = "SELECT user_id FROM jobs WHERE id='$job_id';";
				$result123 = $this->db->query($query)->result();
				$job_Owner = $result123[0]->user_id;
				
                $query = "SELECT * FROM preferred_agencies WHERE user_id ='$job_Owner' AND agency_id = '".get_user('id')."';";
				$result1234 = $this->db->query($query)->result();
                //$tst = count($result1234);
                $tst = 0;

                if ($rsvp == 'accepted' && $tst>0)
				{
					$this->db->insert($rsvp.'_jobs', ['user_id'=>get_user('id'), 'job_id'=>$job_id,'estatus'=>1, 'created_at' => date('Y-m-d H:i:s')]);
                    $this->db->set('updated_at',date('Y-m-d H:i:s'));
                    $this->db->where('id', $job_id);
                    $this->db->update('jobs');
				}
				else
				{
					$this->db->insert($rsvp.'_jobs', ['user_id'=>get_user('id'), 'job_id'=>$job_id, 'created_at' => date('Y-m-d H:i:s')]);
                    $this->db->set('updated_at',date('Y-m-d H:i:s'));
                    $this->db->where('id', $job_id);
                    $this->db->update('jobs');
				}

                //Send email 
                if($rsvp == 'accepted')
                {
                    $agencyname = User_profile::where('user_id','=',get_user('id'))->first();
                    $email_variable['AGENCYNAME'] = $agencyname->company_name;
                    $email_variable['JOBNAME'] = ucfirst($job->title);

                    $email_data=email_template(19,$email_variable);

                    $User = User::find($job_Owner);

                    $jobowneremail = $User->email;

                    if($email_data){
                        $emailData = [
                            'to'        => $jobowneremail,
                            'from'      => WEBSITE_FROM_EMAIL,
                            'subject'   => $email_data['template_subject']
                        ];

                        $emailData['body'] = $email_data['template_body'];
                        email($emailData);

                        // Set the notification
                        $user_notification = array(
                                    "notification_text" => $agencyname->company_name.' has expressed interest in recruiting for your '.$job->title.' opening',
                                    "notification_url" => 'jobs/view_detail/'.$job->id,
                                    "user_id" => $job_Owner,
                                    "status" => 0,
                                    "cn_status" => 0
                                );

                        $notificationData = User_notifications::create($user_notification);

                    }

                    if($tst>0)
                    {
                        $User1 = User::find(get_user('id'));

                        $email_variable_accept['JOBNAME'] = ucfirst($job->title);
                        $email_data_accept = email_template(20,$email_variable_accept);

                        if($email_data_accept){
                            $emailData_accept = [
                                'to'        => $User1->email,
                                'from'      => WEBSITE_FROM_EMAIL,
                                'subject'   => $email_data_accept['template_subject']
                            ];

                            $emailData_accept['body'] = $email_data_accept['template_body'];
                            email($emailData_accept);
                        }


                        $user_notification = array(
                                    "notification_text" => 'You have been approved as an agency for the following TalentGram: '.ucfirst($job->title),
                                    "notification_url" => 'searches/job_detail/'.$job->id.'/any/new000',
                                    "user_id" => $User1->id,
                                    "status" => 0,
                                    "cn_status" => 0
                                );

                        $notificationData = User_notifications::create($user_notification);

                    }

                    
                }
                
                $rejectmsg = Site_messages::where('type','=','rejected_job')->first();
                $acceptmsg = Site_messages::where('type','=','accepted_job')->first();
                
                $msg = ($rsvp == 'accepted') ? $acceptmsg->msg : $rejectmsg->msg;

                $response = ['status'=>1, 'msg'=> $msg];

            }else{
                //$response['msg'] = 'Maximum number of Agencies that can apply for this Job ahas been reached. Please try another Job.';
                $response['msg'] = 'This TalentGram is no longer available.  The maximum number of five agencies has already accepted.';
            }
        }
        catch (ModelNotFoundException $e){
            $response['msg'] = 'This job may have been removed by admin.';
        }

        if($this->input->is_ajax_request()){
            echo json_encode($response); die();
        }
        else
        {
            $array = ['error', 'success'];
            $this->set_flashMsg($array[$response['status']], $response['msg'] );
            $redirectURL = ($rsvp == 'rejected') ? 'searches' :'searches/job_detail/'.$job_id;
            //redirect(base_url($redirectURL));
        }
		
		//print_r($result123);
        
        try{

            $user1 = User::find($job_Owner);
            $user2 = User::find(get_user('id'));
            $user2->last_job_type = $user1->type;
            $user2->save();

            //echo json_encode($response); 

            redirect(base_url($redirectURL)); //die();
        }
        catch (Exception $e){
            die();
        }

    }

    public function search($searchStr) {
       /** $usersList = User::where('type','agency')
            ->where(function($query) use ($searchStr){
                return $query->orWhere('first_name', 'LIKE', "$searchStr%")
                    ->orWhere('email', 'LIKE', "$searchStr%")
                    ->orWhere('last_name', 'LIKE', "$searchStr%");
            })
            ->with('user_profile')->get(); */

        /*$usersList =  User::leftJoin('user_profiles', function($ljoin){
            $ljoin->on('users.id', '=', 'user_profiles.user_id');
        })->where(function($query) use ($searchStr){
            return $query->orWhere('user_profiles.company_name', 'LIKE', "%$searchStr%")->where('users.type','=','agency')->orWhere('users.first_name','LIKE',"%$searchStr%")->where('users.type','=','agency');
        })->get();
        */

        /*$usersList =  User::leftJoin('user_profiles', function($ljoin){
            $ljoin->on('users.id', '=', 'user_profiles.user_id');
        })->where(function($query) use ($searchStr){
            return $query->orWhere('user_profiles.company_name', 'LIKE', "%$searchStr%")->where('users.type','=','agency')->orWhere('users.first_name','LIKE',"%$searchStr%")->where('users.type','=','agency');
        })->get();
        */

        $searchStr = str_replace('%20', ' ', $searchStr);
        $searchStr = str_replace('and ', '+', $searchStr);
        $searchStr = str_replace('not ', '-', $searchStr);
        $searchStr = str_replace(' ', '* ', $searchStr);
        $searchStr = $searchStr."*";

        

        /*
         $query = "Select DISTINCT u.first_name, u.last_name,u.profile_pic, up.company_name, u.id from users u
            INNER JOIN user_profiles up ON u.id = up.user_id
            INNER JOIN industry_user iu on u.id = iu.user_id
            INNER JOIN industries i ON i.id = iu.industry_id
            INNER JOIN states s on s.id = up.state_id
            WHERE u.type = 'agency' AND (MATCH(first_name, last_name) AGAINST('".$searchStr."' IN BOOLEAN MODE) 
            OR MATCH(city, zipcode, company_name) AGAINST('".$searchStr."' IN BOOLEAN MODE)
            OR MATCH(title) AGAINST('".$searchStr."' IN BOOLEAN MODE)
            OR MATCH(name) AGAINST('".$searchStr."' IN BOOLEAN MODE)
            OR MATCH(abbreviation) AGAINST('".$searchStr."' IN BOOLEAN MODE));";
            */
        
        $query = "Select DISTINCT u.first_name, u.last_name,u.profile_pic, up.company_name, u.id from users u
            INNER JOIN user_profiles up ON u.id = up.user_id
            INNER JOIN industry_user iu on u.id = iu.user_id
            INNER JOIN industries i ON i.id = iu.industry_id
            INNER JOIN states s on s.id = up.state_id
            WHERE u.type = 'agency' AND u.status = 1 AND (MATCH(first_name, last_name) AGAINST('".$searchStr."' IN BOOLEAN MODE) 
            OR MATCH(city, zipcode, company_name) AGAINST('".$searchStr."' IN BOOLEAN MODE)
            OR MATCH(title) AGAINST('".$searchStr."' IN BOOLEAN MODE)
            OR MATCH(name,abb_for_search) AGAINST('".$searchStr."' IN BOOLEAN MODE));";
        

        $usersList = $this->db->query($query)->result('User');

        //var_dump($usersList);
        //p($usersList);
        $this->load->blade('favourites/_search_result', ['usersList' => $usersList]);
    }

    /*adding search candidate functonality on dashboard*/
    public function candidate_search($searchStr)
    {
        $user_id = get_user('id');
        $searchStr = str_replace('%20', ' ', $searchStr);
        $searchStr = str_replace('and ', '+', $searchStr);
        $searchStr = str_replace('not ', '-', $searchStr);
        $searchStr = str_replace(' ', '* ', $searchStr);
        $searchStr = $searchStr;
        
        $query = "Select c.id as candidate_id,c.name as candiate_name,c.city,s.name,j.id,j.title,j.closed from candidates c INNER JOIN states s ON c.state_id = s.id 
        INNER JOIN jobs j on c.job_id = j.id
        where c.name LIKE '%".$searchStr."%' and c.user_id='".$user_id."'";
        $usersList = $this->db->query($query)->result('User');
        
        $this->load->blade('favourites/_search_candidate_result', ['usersList' => $usersList]);
    }

    /**
     * Select Subscription plans
     */
    public function subscription_plans (){

        $data=Users_plans::where('user_id',get_user('id'))->orderBy("created_at","DESC")->first();
        if($this->input->post()){
            $id=$this->input->post('plan_id');
            $plan = Subscription_plans::find($id);
            $product['name']=$plan->plan_name;
            $product['id'] = $plan->id;
            $product['price'] = $plan->amount;
            // Chnages on server only
            /*
            $up=new Users_plans();
            $up->plan_name = $plan->plan_name;
            $up->no_of_days = $plan->no_of_days;
            $up->amount = $plan->amount;
            $up->user_id = get_user('id');
            $up->save();
            */
            $this->plnass($product);
            //redirect(base_url('dashboard'));
        }elseif(isset($data)){
            $date1=date("Y-m-d",strtotime($data->created_at));
            $date2=date("Y-m-d");
            $days=date_difference($date1,$date2);
            if($days > $data->no_of_days) {
                $this->data['subscription_plans'] = Subscription_plans::where('status','1')->get();
                $this->load->blade('agency/subscription_detail', $this->data);
            }else {
                redirect(base_url('dashboard'));
            }
        }else{
            $this->data['subscription_plans'] = Subscription_plans::where('status','1')->get();
            $this->load->blade('agency/subscription_detail', $this->data);
        }
    }


    private function plnass($product){
        $this->load->library('paypal_lib');
        //Set variables for paypal form
        $returnURL = base_url().'agency/success'; //payment success url
        $cancelURL = base_url().'agency/cancel'; //payment cancel url
        $notifyURL = base_url().'agency/ipn'; //ipn url
        //get particular product data

        $userID = 1; //current user id
        $logo = '';

        $this->paypal_lib->add_field('return', $returnURL);
        $this->paypal_lib->add_field('cancel_return', $cancelURL);
        $this->paypal_lib->add_field('notify_url', $notifyURL);
        $this->paypal_lib->add_field('item_name', $product['name']);
        $this->paypal_lib->add_field('custom', $userID);
        $this->paypal_lib->add_field('item_number',  $product['id']);
        $this->paypal_lib->add_field('amount',  $product['price']);
        $this->paypal_lib->image($logo);

        $this->paypal_lib->paypal_auto_form();
    }

    public function success(){
        $id=$_REQUEST['item_number'];
        $plan = Subscription_plans::find($id);
        $up=new Users_plans();
        $up->plan_name = $plan->plan_name;
        $up->no_of_days = $plan->no_of_days;
        $up->amount = $plan->amount;
        $up->plan_id = $plan->id;
        $up->txn_no = $_REQUEST['txn_id'];
        $up->status = $_REQUEST['payment_status'];
        $up->user_id = get_user('id');
        $up->save();
        $this->session->plan = "1";
        $this->set_flashMsg('success','Plan Subscribed Successfully.');
        redirect(base_url('profile/update'));
    }

    public function cancel(){
        $this->set_flashMsg('success','Process Unable to Complete');
        redirect(base_url('dashboard'));
    }
    

}