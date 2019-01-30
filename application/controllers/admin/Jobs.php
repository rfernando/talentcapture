<?php
use Dompdf\Dompdf;


class Jobs extends MY_Controller{

    /**
     * @var array
     *
     * User Profile Fields Array
     */
    private $jobDetailsFields = [
        [
            'name' => 'title',
            'type' => 'text',
            'placeholder' => 'Job Title',
            'validation' => 'required'
        ],
        [
            'name' => 'industry_id',
            'type' => 'select',
            'placeholder' => 'Industry',
            'class' => 'select2',
            'validation' => 'required'
        ],
        [
            'name' => 'profession_id',
            'type' => 'select',
            'options'  => [],
            'placeholder' => 'Job Profession',
            'class' => 'select2',
            'validation' => 'required'
        ],
        [
            'name' => 'job_location',
            'type' => 'text',
            'placeholder' => 'Location',
            'validation' => 'required'
        ],
        [
            'name' => 'position_type',
            'type' => 'select',
            'placeholder' => 'Resource Type',
            'validation' => 'required',
            'options' => ['Direct Hire'=>'Direct Hire', 'Contract'=>'Contract', 'Contract to Hire'=>'Contract to Hire']
        ],
        [
            'name' => 'salary',
            'type' => 'text',
            'placeholder' => 'Salary or Rate',
            'validation' => 'required'
        ],
        [
            'name' => 'client_name',
            'type' => 'text',
            'placeholder' => 'Client Name',
            'validation' => 'required',
        ],
        [
            'name' => 'client_name_confidential',
            'type' => 'checkbox',
            'label' => 'Client Name Confidential',
            'value' => 1,
            'validation' => 'trim',
        ],
        [
            'name' => 'warranty_period',
            'type' => 'select',
            'placeholder' => 'Warranty Period',
            'options' => ['None'=>'None','30 Days'=>"30 Days",'45 Days'=>"45 Days",'60 Days'=>"60 Days",'90 Days'=>"90 Days",'120 Days'=>"120 Days",'180 Days'=>"180 Days"],
            'class' => 'select2',
            'validation' => 'required'
        ],
        [
            'name' => 'split_percentage',
            'type' => 'text',
            'placeholder' => 'Split Percentage',
            'validation' => 'trim|required'
        ],
		[
            'name' => 'placement_fee',
            'type' => 'select',
            'placeholder' => 'Placement Fee',
            'options' => [''=>'(Placement Fee is equal to the percentage of salary for a successful hire.)','10'=>"10",'11'=>"11",'12'=>"12",'13'=>"13",'14'=>"14",'15'=>"15",'16'=>"16",'17'=>"17",'18'=>"18",'19'=>"19",'20'=>"20",'21'=>"21",'22'=>"22",'23'=>"23",'24'=>"24",'25'=>"25",'26'=>"26",'27'=>"27",'28'=>"28",'29'=>"29",'30'=>"30"],
			'class' => 'select2',
            'validation' => 'required'
        ],
        [
            'name' => 'note',
            'type' => 'textarea',
            'placeholder' => 'General Note',
            'validation' => 'trim'
        ],
        [
            'name' => 'description',
            'type' => 'textarea',
            'placeholder' => 'Description',
            'validation' => 'required'
        ],
        [
            'name' => 'status',
            'type' => 'select',
            'placeholder' => 'Status',
            'options'=> ['Inactive' , 'Active'],
            'validation' => 'required'
        ]
    ];
	
	private $assignAgencyFields = [
		[
            'name' => 'agency_id',
            'type' => 'select',
            'placeholder' => 'Agency',
            'class' => 'select2',
            'validation' => 'required',
			
        ],
    ];

    function __construct() {
        $this->adminUserRequired = TRUE;
        parent::__construct();
    }

    function index() {
        $this->data['jobs'] = Job::withTrashed()->with('user')->orderBy('id', 'desc')->get();
        $this->load->blade('admin.jobs.index', $this->data);
    }

    function view($id){
		
        if($id == NULL || !is_numeric($id)) redirect(admin_url('jobs/index'));
        $this->data['job'] = Job::find($id);
        $this->data['candidates'] =  $this->data['job']->candidates()->get();
		
		$this->data['assignAgencyFields'] = $this->get_values2($id);
		
        $this->data['agencies'] = $this->data['job']->accepted_by_agencies()->with('user_profile')->get();
        $this->load->blade('admin.jobs.view', $this->data);
    }

    function edit($id){
        if($id == NULL || !is_numeric($id)) redirect(admin_url('jobs/index'));
        $this->data['job'] = Job::find($id);
        $this->data['jobDetailsFields'] = $this->get_values($id);
        $this->load->blade('admin.jobs.edit', $this->data);
    }

    function save($id){
        if($id == NULL || !is_numeric($id)) redirect(admin_url('jobs/index'));
        $job = Job::find($id);
        foreach ($this->input->post() as $fieldName => $value){
            $job->$fieldName = $value;
        }
        $job->updated_by = get_admin_user('id');
        $job->save();
        $this->set_flashMsg('success','The Job has been Updated Successfully');
        redirect(admin_url('jobs'));

    }
	
	function assign_agency($id){
		/*print_r();*/
        if($this->input->post()['agency_id'][0] == "a" ) 
		{
			$this->set_flashMsg('error','Please Select an Agency');
			redirect(admin_url('jobs/view/'.$id));
		}
		else
		{
			$this->db->insert('accepted_jobs', ['user_id'=>$this->input->post()['agency_id'], 'job_id'=>$id,'estatus'=>1, 'created_at' => date('Y-m-d H:i:s')]);

            // Getting the job details
            $job = Job::find($id);

            // Get the job owner details
            $job_owner_id = $job->user_id;
            $job_owner = User::find($job_owner_id);
            $job_owner_email = $job_owner->email;

            //Getting the agency details
            $agency_user_id = $this->input->post()['agency_id'];
            $agency_profile = User_profile::where('user_id','=',$agency_user_id)->first();
            $agency_user = User::find($agency_user_id);
            $agency_user_email = $agency_user->email;

            // Send email to job owner

            // Assign to email variables
            $email_variable['USERNAME'] = $agency_user->first_name.' '. $agency_user->last_name;
            $email_variable['AGENCYNAME'] = $agency_profile->company_name;
            $email_variable['JOBNAME'] = ucfirst($job->title);

            $email_data=email_template(26,$email_variable);

            if($email_data){
                
                $emailData = [
                    'to'        => $job_owner_email,
                    'from'      => WEBSITE_FROM_EMAIL,
                    'subject'   => $email_data['template_subject']
                ];

                $emailData['body'] = $email_data['template_body'];
                email($emailData);
            }

            // Send email to agency
            $email_variable_agency['JOBNAME'] = ucfirst($job->title);
            $email_data_agency = email_template(20,$email_variable_agency);

            $emailDataAgency = [
                'to'        => $agency_user_email,
                'from'      => WEBSITE_FROM_EMAIL,
                'subject'   => $email_data_agency['template_subject']
            ];

            $emailDataAgency['body'] = $email_data_agency['template_body'];
            email($emailDataAgency);

			$this->set_flashMsg('success','The Agency Successfully Assigned');
			redirect(admin_url('jobs/view/'.$id));
		}
    }

    function change_status($id= NULL){
        if($id == NULL || !is_numeric($id)) redirect(admin_url('jobs/index'));
        $job = Job::find($id);
        if(!$job->status){
            if($job->notify_preferred == 1){
                $sameProfessionAgencies =  User::leftJoin('job_preferred_agencies', function($ljoin){
                    $ljoin->on('users.id', '=', 'job_preferred_agencies.agency_id');
                })->where('job_preferred_agencies.job_id', $job->id)->get()->lists('email', 'id');
                /*
                $sameProfessionAgencies = $job->profession()->first()->users()->active()->where('type','agency')->get()->lists('email', 'id');
                */
            }else{
                $sameProfessionAgencies = $job->industry()->first()->users()->active()->where('type','agency')->get()->lists('email', 'id');
            }
            
            //echo "<pre>";
            //echo $job->status;
            //print_r($sameProfessionAgencies);

            //$sameindustryAgencies = $job->industry()->first()->users()->active()->where('type','agency')->get()->lists('email', 'id');
            $this->send_job_alert_email($sameProfessionAgencies, $job->user()->first()->user_profile()->first()->company_name,$id);

            if($job->status == 0)
            {
				$job_owner_user_id = $job->user_id;
				$job_owner_user = User::find($job_owner_user_id);
				$job_owner_type = $job_owner_user->type;
                $this->add_notification($sameProfessionAgencies, $job->user()->first()->user_profile()->first()->company_name,$job_owner_type,$job);
            }
        }
        $job->status = !$job->status;
        $job->save();

        echo json_encode($job);die();

        
    }

    function send_job_alert_email($sameProfessionAgencies, $jobCreatedByUserCompanyName,$job_id){
		$job = Job::find($job_id);
        foreach ($sameProfessionAgencies as $user_id => $userEmail){
            $email_variable['COMPANYNAME'] = $jobCreatedByUserCompanyName;
			$email_variable['J_TITLE'] = $job->title;
			$email_variable['J_INDUSTRY'] = $job->industry()->first()->title;
			$email_variable['J_PROFESSION'] = $job->profession()->first()->title;
			$email_variable['J_SKILLS'] = $job->skills;
			$email_variable['J_LOCATION'] = $job->job_location;
			$email_variable['J_RESOURCE'] = $job->position_type;
			$email_variable['J_SALARY'] = $job->salary;
            $email_variable['J_OPENINGS'] = $job->openings;
            $email_variable['J_VISA_SPONSORSHIP'] = $job->visa_sponsorship;
            $email_variable['J_RELOCATE'] = $job->relocate;
            
            if($job->placement_fee>0){
			     $email_variable['J_FEE'] = $job->placement_fee.'% of the Annual Salary';
            }
            else
            {
                $email_variable['J_FEE'] = '-';
            }

            if($job->split_percentage>0){
                $email_variable['J_SPL_FEE'] = $job->split_percentage.'%';
            }
            else
            {
                $email_variable['J_SPL_FEE'] = 'N/A';
            }

            $email_data=email_template(10,$email_variable);
            if(isset($email_data)){
                $emailData = ['from' => ADMIN_EMAIL, 'to' => $userEmail, 'subject' => $email_data['template_subject']];
                $emailData['body'] = $email_data['template_body'];
                email($emailData);
            }

        }
    }

    function add_notification($sameProfessionAgencies, $jobCreatedByUserCompanyName,$jobCreatedByUserType,$job){
        foreach ($sameProfessionAgencies as $user_id => $userEmail){
            $userId = User::where(['email' => $userEmail])->get();

            $user_notification = array(
                        "notification_text" => 'You have a new '.$jobCreatedByUserType.' TalentGram to review from '.$jobCreatedByUserCompanyName,
                        "notification_url" => 'searches/job_detail/'.$job->id.'/any/new000',
                        "user_id" => $userId[0]->id,
                        "status" => 0,
                        "cn_status" => 0
                    );

            $notificationData = User_notifications::create($user_notification);

			$update_user_id = $userId[0]->id;
			$query = "UPDATE users SET last_job_type ='$jobCreatedByUserType' WHERE id = '$update_user_id';";
			$this->db->query($query);
        }
    }

    function delete($data = NULL){
        if($data == NULL )
            $data = $this->input->post('selectedRows');
        Job::destroy($data);
        $this->set_flashMsg('success','The Job(s) have been successfully Deleted');
        redirect(admin_url('jobs'));
    }


    function restore($data = NULL){
        if($data == NULL )
            $data = $this->input->post('selectedRows');
        Job::withTrashed()->where('id', $data)->restore();
        $this->set_flashMsg('success','The Job has been successfully Restored');
        redirect(admin_url('jobs'));
    }

    private function get_values($id = NULL){
        $job = Job::find($id);
        $userType = $job->user()->first()->type;
        $this->jobDetailsFields[1]['options'] = Industry::lists('title','id');
        if($userType == 'employer') unset($this->jobDetailsFields[7], $this->jobDetailsFields[8], $this->jobDetailsFields[9]);
		if($userType == 'agency') unset($this->jobDetailsFields[10]);
        foreach ($this->jobDetailsFields as &$field){
            $field['value'] = $job->{$field['name']};
        }
        return $this->jobDetailsFields;
    }
private function get_values2($id = NULL){
        $job = Job::find($id);
        $userType = $job->user()->first()->type;
	
	
	$query = "SELECT u.id, CONCAT(u.first_name,' ',u.last_name,' @ ',up.company_name) AS agency_full_name FROM users AS u, user_profiles AS up WHERE u.id=up.user_id AND u.type='agency' AND u.id NOT IN (SELECT user_id FROM accepted_jobs WHERE job_id = '$id');";
	$result123 = $this->db->query($query)->result();
	$myarr=[];
	$myarr["agn"] = "Select an Agency";
	foreach($result123 as $row) 
	{
		$myarr[$row->id] = $row->agency_full_name;
	}
	$this->assignAgencyFields[0]['options'] =$myarr;
        foreach ($this->assignAgencyFields as &$field){
            $field['value'] = $job->{$field['name']};
        }
        return $this->assignAgencyFields;
    }

    public  function get_profession($industryId){
        $category =  Industry::find($industryId)->professions()->lists('title','id');
        $resultArray = [];
        foreach ( $category as $id=>$val){
            $resultArray[] = ["id"=>$id, "text"=>$val];
        }
        echo json_encode($resultArray);
    }

    public function candidate_detail($jobId, $candidateId){
        if($jobId == NULL || !is_numeric($jobId)) redirect(admin_url('jobs/index'));
        $this->data['job'] = Job::find($jobId);
        $this->data['candidates'] =  $this->data['job']->candidates()->get();
        $this->data['candidate'] =  Candidate::find($candidateId);
        $this->data['agencies'] = $this->data['job']->accepted_by_agencies()->with('user_profile')->get();
        $this->data['userType'] = 'admin';
		$this->data['assignAgencyFields'] = $this->get_values2($jobId);
        $this->load->blade('admin.jobs.view', $this->data);
    }



    public function agency_detail($jobId, $agency_id){
        if($jobId == NULL || !is_numeric($jobId)) redirect(admin_url('jobs/index'));

        $this->data['job'] = Job::find($jobId);
        $this->data['candidates'] = $this->data['job']->candidates()->orderBy('client_accepted','desc')->get();
        //$this->data['candidate'] =  Candidate::find($candidateId);
        //$this->data['agencies'] = $this->data['job']->accepted_by_agencies()->get()->toArray();
        $this->data['agencies'] = $this->data['job']->accepted_by_agencies()->with('user_profile')->get();
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
		
        $this->data['usertype'] = $this->data['agency']->type;
        
        
        //$userFavAgenciesList = User::find(get_user('id'))->favourite_agencies()->lists('id');
       // $this->data['isFavourite'] = in_array($agency_id, $userFavAgenciesList);
       // die($jobId);
		$this->data['assignAgencyFields'] = $this->get_values2($jobId);
        $this->load->blade('admin/jobs/view_agency_detail',$this->data);
    }
	
	function hire_candidates($jobID = false, $candidateId = false, $agnId = false){

        if($hired_candidates = $this->input->post('hired_candidates')){
            foreach ($hired_candidates as $hiredCandidate) {
                $hiredCandidate['added_by'] = get_admin_user('id') != '' ? get_admin_user('id') : 1; 

                $hiredCandidate['type'] = 'Hire';
                Hire_detail::create($hiredCandidate);
                $candidate = Candidate::find($hiredCandidate['candidate_id']);
                //Check Candidate is already hired

                if ($candidate->hired != '1'){
                    $candidate->hired = 1;
                    $candidate->start_date = date('Y-m-d', strtotime($hiredCandidate['start_date']));
                    $candidate->base_salary = $hiredCandidate['base_salary'];
                    $candidate->additional_info = $hiredCandidate['additional_info'];
                    $candidate->save();
					
					//$this->update_candidate_actions($candidate->job_id,"candidates_hired");
					
                    $candidateOwner = $candidate->user_id;
                    $hire_details = Hire_detail::where("added_by", $candidateOwner)->where("candidate_id", $candidateId)->get()->toArray();

                    //Send email to agency that added that candidate

                    // Create Email variable
                    $email_variable['CANDIDATENAME'] = $candidate->name;
                    $email_variable['STARTDATE'] = date("n-j-Y", strtotime($hiredCandidate['start_date']));
                    $email_variable['BASESALARY'] = $hiredCandidate['base_salary'];
                    $email_variable['FIRSTNAME'] = get_admin_user('first_name');
                    $email_variable['LASTNAME'] = get_admin_user('last_name');

                    $useremail = $candidate->agency()->first()->email;

                    $emailData['body'] = "";
                    $userType = get_admin_user('type');
                    if (count($hire_details) > 0) {
                        $email_data=email_template(1,$email_variable);
                    } else if ($userType == "employer") {
                        $email_data=email_template(2,$email_variable);
                    } else if ($userType == "agency") {
                        $email_data=email_template(3,$email_variable);
                    }

                    if(isset($email_data)){
                        $emailData = ['to' => $useremail, 'bcc' => ADMIN_EMAIL, 'from' => WEBSITE_FROM_EMAIL, 'subject' => $email_data['template_subject']];

                        $emailData['body'] = $email_data['template_body'];
                        //$emailData['body'].= "Start Date:".$hiredCandidate['start_date']."<br><br>";

                        //$emailData['body'].= "Salary or Rate:".$hiredCandidate['base_salary']."<br><br>";

                        //$emailData['body'] = 'Congratulations! '.get_user('first_name')." ".get_user('last_name').' has marked your candidate as hired! Please login to your account and verify the hire details by selecting the "request payment" icon.';
                        /*echo "<pre>";
                        print_r($emailData);
                        exit;*/
                        email($emailData);
                    }
                }

            }
        }
        //$this->sync_ratings_and_favourites();
        if($jobID && $agnId){
            $this->set_flashMsg('success','Congratulations on hiring this candidate. A representative will follow up with you soon to verify the details.');
            redirect(admin_url('placements/load_talentg_dtls/'.$jobID.'/'.$agnId));
        }
        elseif($jobID){
            $this->set_flashMsg('success','Selected candidate(s) have been hired Successfully !!');
            redirect(base_url('placements/view_detail/'.$jobID));
        }
    }
	
	function confirm_hired($jobID = false, $candidateId = false){
		if($hired_candidates = $this->input->post('hired_candidates')){

            foreach ($hired_candidates as $hiredCandidate) {

				$hiredCandidate['added_by'] = get_user('id') != '' ? get_user('id') : 1;
                

                $hiredCandidate['type'] = 'Hire';
                Hire_detail::create($hiredCandidate);
                $candidate = Candidate::find($hiredCandidate['candidate_id']);
                /*Update candidate table for recent modification */
                $updated_date=date("Y-m-d H:i:s");
                $query_candidate = "UPDATE candidates SET updated_at='".$updated_date."' WHERE id='".$hiredCandidate['candidate_id']."'";
                $this->db->query($query_candidate);

                //Check Candidate is already hired
                if ($candidate->hired != '1'){
                    $candidate->hired = 1;
                    $candidate->start_date = date('Y-m-d', strtotime($hiredCandidate['start_date']));
                    $candidate->base_salary = $hiredCandidate['base_salary'];
                    $candidate->additional_info = $hiredCandidate['additional_info'];
                    $candidate->save();
					
					//$this->update_candidate_actions($candidate->job_id,"candidates_hired");
					
					//update the status of the talentgram modification for RP-640
					$candidate_jobID=$candidate->job_id;
					$candidate_owner=$candidate->user_id;
					$query = "UPDATE accepted_jobs SET estatus ='1', updated_at='$updated_date' WHERE job_id='$candidate_jobID' AND user_id='$candidate_owner';";
					$this->db->query($query);
                    $candidateOwner = $candidate->user_id;
                    $hire_details = Hire_detail::where("added_by", $candidateOwner)->where("candidate_id", $candidateId)->get()->toArray();
					$job = Job::find($jobID);
                    //Send email to agency that added that candidate
                    // Create Email variable
                    $email_variable['CANDIDATENAME'] = $candidate->name;
					$email_variable['JOBTITLE'] = $job->title;
                    $email_variable['STARTDATE'] = date("n-j-Y", strtotime($hiredCandidate['start_date']));
                    $email_variable['BASESALARY'] = $hiredCandidate['base_salary'];
                    $useremail = $candidate->agency()->first()->email;
                    $emailData['body'] = "";
                    $email_data=email_template(30,$email_variable);
					
                    if(isset($email_data)){
                        $emailData = ['to' => $useremail, 'bcc' => ADMIN_EMAIL, 'from' => WEBSITE_FROM_EMAIL, 'subject' => $email_data['template_subject']];
                        $emailData['body'] = $email_data['template_body'];
                        email($emailData);
                    }
					
					$user_profile=User_profile::where(array('user_id'=>$job->user_id))->first(['company_name']);
                    //Set notification
                    $user_notification = array(
                        "notification_text" => 'Your candidate '.$candidate->name.' has been hired by '.$user_profile->company_name,
                        "notification_url" => 'searches/candidate_detail/'.$jobID.'/'.$candidateId.'/any/new000',
                        "user_id" => $candidateOwner,
                        "status" => 0,
                        "cn_status" => 0
                    );
                    $notificationData = User_notifications::create($user_notification);
					
					$jobOwner = User::find($job->user_id);
					$useremail = $jobOwner->email;
                    $emailData['body'] = "";
                    $email_data=email_template(31,$email_variable);
					
                    if(isset($email_data)){
                        $emailData = ['to' => $useremail, 'bcc' => ADMIN_EMAIL, 'from' => WEBSITE_FROM_EMAIL, 'subject' => $email_data['template_subject']];
                        $emailData['body'] = $email_data['template_body'];
                        email($emailData);
                    }
					$user_notification = array(
                        "notification_text" => 'A candidate '.$candidate->name.' has been marked as hired by Administrator',
                        "notification_url" => 'jobs/candidate_detail/'.$jobID.'/'.$candidateId,
                        "user_id" => $jobOwner->id,
                        "status" => 0,
                        "cn_status" => 0
                    );
                    $notificationData = User_notifications::create($user_notification);
					
                }

            }
        }
        
            $this->set_flashMsg('success','Selected candidate(s) have been hired Successfully !!');
            redirect(admin_url('jobs/view/'.$jobID));
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

    public function employmenthistory_pdf($candidate_id){

        $candidate = Candidate::with('hire_details')->find($candidate_id);
       
        $dompdf = new DOMPDF();
        $html = $this->load->blade('employment_history',['candidate'=>$candidate],true);
        $dompdf->loadHtml($html);
        
        $dompdf->render();
 
        // Get the generated PDF file contents
        $pdf = $dompdf->output();
 
        // Output the generated PDF to Browser
       $dompdf->stream('test.pdf',['Attachment'=>0]);

    }

}