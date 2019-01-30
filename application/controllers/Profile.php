<?php

/**
 * Class Profile
 *
 * All the Functionality related to the Profile Page
 * will be placed in this class.
 */
class Profile extends MY_Controller{


    /**
     * @var array
     *
     * User Profile Fields Array
     */
    private $updateProfileFields = [
        [
            'name' => 'users[first_name]',
            'type' => 'text',
            'placeholder' => 'First Name',
            'validation' => 'required'
        ],
        [
            'name' => 'users[last_name]',
            'type' => 'text',
            'placeholder' => 'Last Name',
            'validation' => 'required'
        ],
        [
            'name' => 'user_profiles[linkedin_url]',
            'type' => 'text',
            'placeholder' => 'Linkedin',
            'validation' => 'required|valid_url',
            'for_diff-label' => 'social-icon-linked-in'
        ],
        [
            'name' => 'user_profiles[facebook_url]',
            'type' => 'text',
            'placeholder' => 'Corporate Facebook',
            'validation' => 'valid_url',
            'for_diff-label'    => 'social-icon-facebook'
        ],
        [
            'name' => 'user_profiles[twitter_url]',
            'type' => 'text',
            'placeholder' => 'Twitter',
            'validation' => 'valid_url',
            'for_diff-label'    => 'social-icon-twitter'
        ],
        [
            'name' => 'user_profiles[company_address]',
            'type' => 'text',
            'placeholder' => 'Company Address',
            'validation' => 'required'
        ],
        [
            'name' => 'user_profiles[city]',
            'type' => 'text',
            'placeholder' => 'City',
            'validation' => 'required'
        ],
        [
            'name' => 'user_profiles[state_id]',
            'type' => 'select',
            'placeholder' => 'State',
            'validation' => 'required',
        ],
        [
            'name' => 'user_profiles[zipcode]',
            'type' => 'text',
            'placeholder' => 'Zip Code',
            'validation' => 'required|numeric'
        ],
        [
            'name' => 'user_profiles[company_name]',
            'type' => 'text',
            'placeholder' => 'Company Name',
            'validation' => 'required'
        ],
        [
            'name' => 'user_profiles[company_desc]',
            'type' => 'textarea',
            'placeholder' => 'Company Description',
            'validation' => 'required'
        ],
        [
            'name' => 'user_profiles[company_website_url]',
            'type' => 'text',
            'placeholder' => 'Company Website',
            'validation' => 'required|valid_url'
        ],


    ];


    /**
     * Profile constructor.
     */
    function __construct() {
        $this->authUserRequired = TRUE;
        parent::__construct();
        $this->user = User::find($this->user['id']);
        if(!$this->user['accept_terms'])
            redirect(base_url('terms_and_conditions'));

        /*if($this->get_profile_percentage() < 100)
            $this->update();*/

        $stats = ['jobs_posted', 'jobs_closed', 'candidates_accepted', 'candidates_declined', 'candidates_hired'];
        if($this->user->type == 'agency')
            $stats = array_merge($stats, ['so_accepted', 'so_rejected', 'so_candidates_hired']);
        foreach ($stats as $stat){
            $methodName =  "get_$stat".'_count';
            $this->data['stats'][$stat] = $this->$methodName($this->user->id);
        }
    }

    /**
     * update
     *
     * Function to display the Update user profile page.
     */
    public function update() {
        $user = $this->user;
        $this->data['user'] = $this->user;
        $this->data['user_profile'] = $this->user->user_profile()->first();
        $this->data['updateProfileFields'] = $this->getValues();
        $this->data['changePassFields'] = $this->getValues();
        $this->data['industries'] = Industry::orderBy('title','asc')->lists('title','id');
        $this->data['my_charities'] = My_charities::orderBy('title','asc')->lists('title','id');
        //$this->data['industries'] = Industry::orderBy('title','asc')->get();
        $this->data['users_plan']=check_subscription();

        $stats_job = ['jobs_posted', 'jobs_closed', 'candidates_accepted', 'candidates_declined', 'candidates_hired','agencies_accepted','agencies_declined'];
        $stats_so=array();
        if($user->type == 'agency')
            $stats_so = ['so_accepted','so_rejected','so_candidates_accepted', 'so_candidates_declined', 'so_candidates_hired'];

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

        /*
        $this->data['Users_plan'] =  Users_plans::leftJoin('subscription_plans', function($ljoin){
            $ljoin->on('subscription_plans.id', '=', 'users_plans.plan_id');
        })->where('users_plans.user_id',get_user('id'))->orderBy("users_plans.created_at","DESC")->first()->toSql();
        var_dump($this->data['Users_plan']);
        */

        //$this->data['Users_plan'] = Users_plans::where('user_id',get_user('id'))->orderBy("created_at","DESC")->first();

        if(isset($this->data['users_plan']['my_plan'])){
            $this->data['subscription_plans'] = Subscription_plans::find($this->data['users_plan']['my_plan']->plan_id);
        }

        $this->data['profession'] = [];
        $this->data['industrySpecialization'] = [];
        $this->data['professionSpecialization'] = [];
        $this->data['myCharities'] = [];
        if(isset($this->session->plan) && $this->uri->segment(3)!='myplan' && $this->session->plan == "1"){
            $this->data['myplan'] = 'active';
            $this->data['settingactive'] = '';
            $this->data['profilegactive'] = '';
            $this->session->unset_userdata('plan');
        }elseif($this->uri->segment(3) != "" && $this->uri->segment(3)!='myplan'){
            $this->data['settingactive'] = 'active';
            $this->data['profilegactive'] = '';
            $this->data['myplan'] = '';
        }elseif($this->uri->segment(3) == "myplan"){
            $this->data['myplan'] = 'active';
            $this->data['settingactive'] = '';
            $this->data['profilegactive'] = '';
        } else{
            $this->data['settingactive'] = '';
            $this->data['myplan'] = '';
            $this->data['profilegactive'] = 'active';
        }
        if($this->user->industries()->count()){
            $this->data['industrySpecialization'] = $this->user->industries()->lists('id');
            $this->data['profession'] = $this->getProfessionOptions($this->data['industrySpecialization'], true);
            $this->data['professionSpecialization'] = $this->user->professions()->lists('id');
        }
        if($this->user->my_charities()->count()){
            $this->data['myCharities'] = $this->user->my_charities()->lists('id');
        }
		$lg_user=get_user('id');
		$today_dt=date('Y-m-d');
		
		
		
		
		/*"SELECT c.id, c.name, j.title, h.start_date, h.base_salary, j.warranty_period, j.placement_fee, j.split_percentage, u.type, j.id AS jobId FROM hire_details AS h, candidates AS c, candidates AS cc, users AS u, jobs AS j, jobs AS jj WHERE h.candidate_id=c.id AND c.user_id='$uid' AND h.candidate_id=cc.id AND cc.job_id=jj.id AND jj.user_id=u.id AND c.job_id=j.id AND h.type='Hire' AND h.id IN (SELECT MAX(id) FROM hire_details GROUP BY candidate_id) AND j.id='$jid';"
			
		$query="SELECT c.name, j.title, h.start_date, h.base_salary, j.warranty_period, j.placement_fee, j.split_percentage FROM hire_details AS h, candidates AS c, users AS u, jobs AS j WHERE h.candidate_id=c.id AND c.user_id='$lg_user' AND h.added_by=u.id AND u.type='agency' AND c.job_id=j.id AND h.type='Hire' AND h.id IN (SELECT MAX(id) FROM hire_details GROUP BY candidate_id) AND h.start_date<'$today_dt';";
		$this->data['agency_placement_info'] = $this->db->query($query)->result();
		
		
		$query="SELECT c.name, j.title, h.start_date, h.base_salary, j.warranty_period, j.placement_fee, j.split_percentage FROM hire_details AS h, candidates AS c, users AS u, jobs AS j WHERE h.candidate_id=c.id AND c.user_id='$lg_user' AND h.added_by=u.id AND u.type='employer' AND c.job_id=j.id AND h.type='Hire' AND h.id IN (SELECT MAX(id) FROM hire_details GROUP BY candidate_id) AND h.start_date<'$today_dt';";*/
		
		$query="SELECT c.name, j.title, h.start_date, h.base_salary, j.warranty_period, j.placement_fee, j.split_percentage, h.id, h.candidate_id FROM hire_details AS h, candidates AS c, users AS u, jobs AS j WHERE h.candidate_id=c.id AND c.user_id='$lg_user' AND u.type='agency' AND c.job_id=j.id AND j.user_id=u.id AND h.type='Hire' AND h.hire_cancelled = 0 AND h.id IN (SELECT MAX(id) FROM hire_details GROUP BY candidate_id) AND h.start_date<'$today_dt';";
		$this->data['agency_placement_info'] = $this->db->query($query)->result();
		
		
		$query="SELECT c.name, j.title, h.start_date, h.base_salary, j.warranty_period, j.placement_fee, j.split_percentage, h.id, h.candidate_id FROM hire_details AS h, candidates AS c, users AS u, jobs AS j WHERE h.candidate_id=c.id AND c.user_id='$lg_user' AND u.type='employer' AND c.job_id=j.id  AND j.user_id=u.id AND h.type='Hire' AND h.hire_cancelled = 0 AND h.id IN (SELECT MAX(id) FROM hire_details GROUP BY candidate_id) AND h.start_date<'$today_dt';";
		$this->data['employer_placement_info'] = $this->db->query($query)->result();	
		
        $this->load->blade('Profile/update', $this->data);
    }



    /**
     * upload_profile_pic
     *
     * Upload user's Profile Pic and save to DB
     */
    public function upload_profile_pic() {
        $file = $_FILES['profile_pic']['name'];
        $config['file_name'] =  time(). ".". pathinfo($file, PATHINFO_EXTENSION);
        $config['upload_path']          = APPPATH.'../public/uploads/';
        $config['allowed_types']        = 'gif|jpg|png';
        $uploadData = upload_file('profile_pic', $config);
        if($uploadData['success']){
            $this->user->profile_pic = $uploadData['upload']->file_name;
            $this->user->save();
            $this->set_flashMsg('success','Your Profile Pic has been Updated Successfully');
        }else{
            $this->set_flashMsg('danger','Your Profile Pic could not be uploaded. Please try again later');
        }
        redirect(base_url('profile/update'));                   

    }

    /**
     * save_specializations
     *
     * Function to save the specialization for each user's
     */
    public function save_specializations() {

        $this->user->industries()->sync($this->input->post('industries'));
        if($this->user->type=='agency')
        {
            $this->user->professions()->sync($this->input->post('professions'));
            $this->set_flashMsg('success','Your preferences for specialty areas has been updated.');
        }
        else
        {
            $this->set_flashMsg('success','You have successfully modified your industry setting.');
        }
        redirect(base_url('profile/update#speciality-areas'));
    }

    /**
     * save_charities
     *
     * Function to save the charities for each user's
     */
    public function save_charities() {
        $my = $this->input->post('my_charities');

        

        if(isset($my)){
            $this->user->my_charities()->sync($this->input->post('my_charities'));

            $my_charity_list = "";

            //Loop the charity list and get the names
            foreach ($my as $my_char) {
                $my_charity_list = $my_charity_list.', '.My_charities::find($my_char)->title;
            }

            //Sending an email to admin
            $email_variable['FULLNAME'] = ucfirst(get_user('first_name').' '.get_user('last_name'));
            $email_variable['COMPNAME'] = User_profile::find(get_user('id'))->company_name;
            $email_variable['CHARITYLIST'] = ltrim($my_charity_list,', ');

            $email_data=email_template(24,$email_variable);

            if(isset($email_data)){
                $emailData = [
                    'to'        => ADMIN_EMAIL,
                    'from'      => WEBSITE_FROM_EMAIL,
                    'subject'   => $email_data['template_subject']
                ];

                $emailData['body'] = $email_data['template_body'];
                email($emailData);
            }

            $this->set_flashMsg('success','You have successfully modified your charity list.');
        }
        else
        {
            $this->set_flashMsg('success','You have successfully modified your charity list.');
        }
        redirect(base_url('profile/update#my-charities'));
    }


    /**
     * save_profile
     *
     * Function to Save the Details in the Database.
     */
    public function save_profile(){
        $termsAccepted = 0;

        if($this->get_profile_percentage() < 100)
            $termsAccepted = 1;

        if($this->save_users_detail() && $this->save_user_profile_details())
            #RP-269 Changed Profile update Message
            //$this->set_flashMsg('success', 'Your Profile has been updated Successfully.');

            $this->set_flashMsg('success', 'Your profile has been successfully updated.');
        else
            $this->set_flashMsg('danger', 'Your Profile could not be updated. Please try again later');

        if($termsAccepted == 0)
            redirect(base_url('Profile/update'));
        else
            redirect(base_url('dashboard'));
    }

    /**
     * save_users_detail
     * @return bool
     *
     * Saves the Details in the Users table.
     */
    private function save_users_detail(){
        $user = $this->input->post('users');
        foreach ($user as $key => $value)
            $this->user->$key = $value;
        $this->user->updated_by = $this->user->id;
        return $this->user->save();
    }

    /**
     * save_user_profile_details
     * @return bool
     *
     * Saves the Details in user_profiles Table.
     */
    private function save_user_profile_details() {
        $userProfile = User_profile::findOrNew($this->user->id);
        foreach ($this->input->post('user_profiles') as $key => $value)
            $userProfile->$key = $value;
            $userProfile->updated_by = $this->user->id;
        return $userProfile->save();
    }

    /**
     * getValues
     *
     * Function to set Values on the
     * formFields which have been filled by user.
     */
    private function getValues() {
        foreach ($this->updateProfileFields as $k=> &$field){
            $field_name =  str_replace(['users[','user_profiles[',']',']'],'',$field['name']);
            if($this->user->$field_name != '' || $this->user->user_profile()->first()->$field_name != ''){
                $field['value'] = ($this->user->$field_name != '') ? $this->user->$field_name : $this->user->user_profile()->first()->$field_name;
            }

            if($k==7){
                $field['options'] = State::lists('name', 'id');
            }
        }

        $userType = $this->user->type;
        if($userType == 'employer') unset($this->updateProfileFields[3], $this->updateProfileFields[4]);

        return $this->updateProfileFields;
    }

    /**
     * change_password
     *
     * Function to Change the Password in the Database.
     */
    public function change_password(){

        $old_pass = md5($this->input->post('old_pass'));
        $new_password = $this->input->post('new_pass');
        $new_pass = md5($new_password);
        $confirm_new_pass = md5($this->input->post('confirm_new_pass'));
        $email = $this->user['email'];
        $user = User::where(['password'=>$old_pass, 'email'=>$email])->first();
        if($user->id == ''){
            $this->set_flashMsg('danger','Passwords do not match.');
            redirect(base_url('Profile/update/1'));
        }
        else if($new_pass != $confirm_new_pass){
           $this->set_flashMsg('danger','Passwords do not match.');
           redirect(base_url('Profile/update/1'));
        }
        else if($new_pass == $old_pass){
            $this->set_flashMsg('danger','Passwords do not match.');
            redirect(base_url('Profile/update/1'));
        }
        else{
            $this->user->password = $new_pass;
            $this->user->save();
            $emailData = [
                'to'        => $email,
                'from'      => WEBSITE_FROM_EMAIL,
                'subject'   => 'Change password Notification'
            ];

            $emailData['body'] = "Hi,"."<br><br>"."You have changed password."."<br><br>"."Your new password: ".$new_password."<br><br>";
            $emailData['body'].= "Please <a href=".base_url('login').">log into your dashboard</a>";
            email($emailData);
            redirect(base_url('logout'));
        }
    }

    function cancel_reported_hire($id, $candidate_id){

        $hired_candidates = Hire_detail::where('candidate_id','=',$candidate_id)->get();

        foreach ($hired_candidates as $hired_candidate) {
            $hired_candidate->hire_cancelled = 1;
            $hired_candidate->hire_cancelled_by = get_user('id');
            $hired_candidate->hire_cancelled_at = date('Y-m-d H:i:s');

            $hired_candidate->save();
        }

        $candidate = Candidate::find($candidate_id);
        $candidate->hired = 0;

        $candidate->save();
		
		//$this->update_candidate_actions($candidate->job_id,"candidates_accepted");


        //Sending the email for the job owner
        $job = Job::find($candidate->job_id);
        $job_owner = User::find($job->user_id);
        $agency_user = User::find($candidate->user_id);

        $job_owner_profile = User_profile::find($job->user_id);
        $agency_user_profile = User_profile::find($candidate->user_id);


        $email_variable['CANCELLEDUSER'] = ucfirst(get_user('first_name')).' '. ucfirst(get_user('last_name'));
        $email_variable['CANDIDATENAME'] = $candidate->name;
        $email_variable['AGENCYUSER'] = ucfirst($agency_user->first_name).' '. ucfirst($agency_user->last_name);
        $email_variable['AGENCYCOMP'] = $agency_user_profile->company_name;
        $email_variable['JOBOWNERNAME'] = ucfirst($job_owner->first_name).' '. ucfirst($job_owner->last_name);
        $email_variable['JOBOWNERCOMP'] = $job_owner_profile->company_name;

        $email_data=email_template(29,$email_variable);

        if(isset($email_data)){
            $emailData = [
                'to'        => $job_owner->email,
                'from'      => WEBSITE_FROM_EMAIL,
                'subject'   => $email_data['template_subject']
            ];

            $emailData['body'] = $email_data['template_body'];
            email($emailData);
        }

        // Set the notification
        $user_notification = array(
                    "notification_text" => ucfirst(get_user('first_name')).' '. ucfirst(get_user('last_name')).' with '.$agency_user_profile->company_name.' Cancelled Hire Details for '.$candidate->name,
                    "notification_url" => 'jobs/view_detail/'.$job->id,
                    "user_id" => $job->user_id,
                    "status" => 0,
                    "cn_status" => 0
                );

        $notificationData = User_notifications::create($user_notification);


        $this->set_flashMsg('success','Placement details updated successfully.');
        redirect(base_url('profile/update'));
    }
	function update_candidate_actions($jobID,$actinType)
	{
		$query = "SELECT $actinType FROM jobs WHERE id = '$jobID';";
		$currentValue = $this->db->query($query)->result();
		$updateValue = $currentValue[0]->$actinType;
		$updateValue += 1;
		$query = "UPDATE jobs SET $actinType ='$updateValue' WHERE id='$jobID';";
		$this->db->query($query);
		echo json_encode("success");
	}


    function create_gsuite_user()
    {

        $googleClient = $this->getClient();

        $service = new Google_Service_Directory($googleClient);

        $postBody = new Google_Service_Directory_User();

        $postBody->setPrimaryEmail('user1@talentcapturerecruiting.com');

        $postBody->setPassword('c5OpfbZ7HiNc8');

        $name = new Google_Service_Directory_UserName();
        $name->setFamilyName('User1');
        $name->setGivenName('TcUser');

        $postBody->setChangePasswordAtNextLogin(false);

        $postBody->setName($name);
        $user = $service->users->insert($postBody);

        /*
        echo "<pre>";
        print_r($user);
        exit;
        */

        return json_encode($user);

    }

    function create_gsuite_user_alias()
    {

        $googleClient = $this->getClient();

        $userKey = 'user1@talentcapturerecruiting.com';
        $alias   = 'tc.useralias1@talentcapturerecruiting.com';

        $dir       = new Google_Service_Directory($googleClient);
        $aliasObj  = new Google_Service_Directory_Alias( array( 'alias' =>  $alias ) );

        $results = $dir->users_aliases->insert($userKey, $aliasObj);

        /*echo "<pre>";
        print_r($results);
        exit;
        */
        return json_encode($user);

    }

    function getClient()
    {
        $scopes = array('https://www.googleapis.com/auth/admin.directory.user');
        $delegatedAdmin = 'jeff.oliver@talentcapturerecruiting.com';

        $client = new Google_Client();
        $client->setApplicationName('Talent Capture');
        $client->setAuthConfig('gsuite_client_secret.json');
        $client->setScopes([Google_Service_Directory::ADMIN_DIRECTORY_USER, Google_Service_Directory::ADMIN_DIRECTORY_USER_ALIAS]);

        $client->setRedirectUri('http://dev.talentcapture.us');
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');


        // Load previously authorized credentials from a file.
        $credentialsPath = $this->expandHomeDirectory('gsuite_credentials.json');
        if (file_exists($credentialsPath)) {
            $accessToken = json_decode(file_get_contents($credentialsPath), true);
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim('4/AAAWwJCMIWvsXt3LD-g6tTcq2bvBQjjH6D9volnTrOEE1XmE8DbCT8y8QNd80NOdJDy6xJ9PYw0jbabQmeKe8KQ#');

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

            // Store the credentials to disk.
            if (!file_exists(dirname($credentialsPath))) {
                mkdir(dirname($credentialsPath), 0700, true);
            }
            file_put_contents($credentialsPath, json_encode($accessToken));
            printf("Credentials saved to %s\n", $credentialsPath);
        }

        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
        }

        return $client;
    }

    function expandHomeDirectory($path)
    {
        $homeDirectory = getenv('HOME');
        if (empty($homeDirectory)) {
            $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
        }
        return str_replace('~', realpath($homeDirectory), $path);
    }


    function subscription_payment()
    {
        //$tx_token = $_GET['tx'];
        $tx_token = $_GET;

        /*echo "<pre>";
        print_r($tx_token);
        exit;
        */
        if(isset($_GET['tx']))
        {
            $id=1;

            if($_GET['amt'] == 150.00)
            {
               $id=2; 
            }

            $plan = Subscription_plans::find($id);

            $up=new Users_plans();

            $up->plan_name = $plan->plan_name;
            $up->no_of_days = $plan->no_of_days;
            $up->amount = $plan->amount;
            $up->plan_id = $plan->id;
            $up->txn_no = $_GET['tx'];
            $up->status = $_GET['st'];
            $up->user_id = get_user('id');
            $up->save();

            $this->session->plan = "1";

            $this->set_flashMsg('success','Plan Subscribed Successfully.');

            redirect(base_url('profile/update'));
        }
        else
        {
            $this->set_flashMsg('error','Subscribtion not updated.');

            redirect(base_url('profile/update'));

        }

        

    }

}