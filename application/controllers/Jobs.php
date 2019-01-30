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
            'name' => 'notify_agencies',
            'type' => 'radio',
            'label' => 'Notify Only Preferred Agencies',
            'value' => 1,
            'validation' => 'trim',
        ],
        [
            'name' => 'notify_agencies',
            'type' => 'radio',
            'label' => 'Notify All Agencies',
            'value' => 0,
            'validation' => 'trim',
        ],
        [
            'name' => 'preferred_agencies[]',
            'type' => 'select',
            'options'  => [],
            'placeholder' => 'Preferred Agencies',
            'class' => 'select2',
            'multiple' => 'multiple',
            'style' => 'display:none'
        ],
		[
            'name' => 'placement_fee',
            'type' => 'select',
            'placeholder' => 'Placement Fee Percentage',
            'options' => [''=>'(Placement Fee is equal to the percentage of salary for a successful hire.)','15'=>"15",'16'=>"16",'17'=>"17",'18'=>"18",'19'=>"19",'20'=>"20",'21'=>"21",'22'=>"22",'23'=>"23",'24'=>"24",'25'=>"25",'26'=>"26",'27'=>"27",'28'=>"28",'29'=>"29",'30'=>"30"],
			'class' => 'select2',
            'validation' => 'required'
        ],
        [
            'name' => 'split_percentage',
            'type' => 'select',
            'placeholder' => 'Split Fee Details',
            'options' => [''=>'(Split Fee Percentage.)','40'=>"40",'41'=>"41",'42'=>"42",'43'=>"43",'44'=>"44",'45'=>"45",'46'=>"46",'47'=>"47",'48'=>"48",'49'=>"49",'50'=>"50",'51'=>"51",'52'=>"52",'53'=>"53",'54'=>"54",'55'=>"55",'56'=>"56",'57'=>"57",'58'=>"58",'59'=>"59",'60'=>"60"],
            'class' => 'select3',
            'validation' => 'required'
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
            'name' => 'skills',
            'type' => 'text',
            'placeholder' => 'Primary Skills',
            'validation' => 'required'
        ],
        [
            'name' => 'job_location',
            'type' => 'text',
            'placeholder' => 'Location',
            'validation' => 'required'
        ],
        [
            'name' => 'position_type[]',
            'type' => 'select',
            'placeholder' => 'Resource Type',
            'validation' => 'required',
            'class' => 'select2',
            'options' => ['Direct Hire'=>'Direct Hire', 'Contract'=>'Contract', 'Contract to Hire'=>'Contract to Hire']
        ],
        [
            'name' => 'openings',    // adding the new field to add new job page for the RP-787
            'type' => 'select',
            'placeholder' => 'Openings',
            'options' => ['1'=>"1", '2'=>"2",'3'=>"3",'4'=>"4",'5'=>"5",'6'=>"6",'7'=>"7",'8'=>"8",'9'=>"9",'10'=>"10"],
            'validation' => 'required'
        ],
        [
            'name' => 'salary',
            'type' => 'text',
            'placeholder' => 'Compensation',
            'validation' => 'required'
        ],
        [
            'name' => 'visa_sponsorship',
            'type' => 'select',
            'placeholder' => 'Visa Sponsorship?',
            'options' => ['No'=>"No", 'Yes'=>"Yes"],
            'validation' => 'required'
        ],
		[
            'name' => 'relocate',
            'type' => 'select',
            'placeholder' => 'Relocate?',
            'options' => ['No'=>"No", 'Yes'=>"Yes"],
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
            'name' => 'description',
            'type' => 'textarea',
            'placeholder' => "Job Description",
            'validation' => 'required',
            'for_diff-label' => 'Job Description'
        ],
        [
            'name' => 'note',
            'type' => 'textarea',
            'rows' => 3,
            'placeholder' => 'Provide any additional important details that will be helpful to the agency recruiters here. For example, a relocation package or bonus potential details.',
            'validation' => 'trim',
            'for_diff-label' => 'General Notes'
        ],
        [
            'name' => 'question',
            'type' => 'textarea',
            'rows' => 3,
            'placeholder' => "If there are any specifics you want the recruiter to include when submitting a candidate for this job, enter them here. They can be in the form of questions or topics.",
            'validation' => 'trim',
            'for_diff-label' => 'Interview Questions',      /*Changing the Candidate Screening Questions to Interview Question*/
            'max_length' => 1000
        ],
        [
            'name' => 'closed',
            'type' => 'hidden',
			'value' => '0',
            'placeholder' => '',
            'validation' => 'trim'
        ],
        [
            'name' => 'closed',
            'type' => 'hidden',
            'placeholder' => '',
            'validation' => 'trim'
        ]
    ];

    private $candidateFields = [
        [
            'name' => 'resume',
            'type' => 'file',
            'accept' => 'application/pdf,application/vnd.ms-excel',
            'placeholder' => 'Browse',
            'validation' => 'required'
        ]
    ];
	
	private $jobattachmentuploadFields = [
        [
            'name' => 'jobattachment',
            'type' => 'file',
            'accept' => 'application/pdf,application/vnd.ms-excel',
            'placeholder' => 'Attachments',
            'validation' => 'required'
        ]
    ];
	
    
    function __construct() {
        $this->authUserRequired = TRUE;
        parent::__construct();
        $userType = get_user('type');
        if($userType == 'employer')
        {
            $industryOptions = User::find(get_user('id'))->industries();    
        }
        else
        {
            //$industryOptions = Industry::all(); 
            $industryOptions = Industry::orderBy('title','asc');  
        }
        
        $this->jobDetailsFields[1]['options'] = $industryOptions->lists('title','id');
        if(count($this->jobDetailsFields[1]['options'])){
            $industries = $industryOptions->lists('id');
            $this->jobDetailsFields[2]['options'] = Industry::find($industries[0])->professions()->orderBy('title','asc')->lists('title','id');
        }
    
        $this->data['active_jobs'] = Job::where(['user_id' => get_user('id'),'closed'=>0])->orderBy('created_at', 'desc')->get();
        $this->data['closed_jobs'] = Job::where(['user_id' => get_user('id'),'closed'=>1])->orderBy('created_at', 'desc')->get();
    }

    function index(){
        $this->load->blade('jobs/index',$this->data);
    }

    function add_new(){
        $this->data['jobDetailsFields'] = $this->get_values();
        $checksubscription =check_subscription();
        
        if(!empty($checksubscription))
        {
            $this->data['subscription']=check_subscription();
        }
        else
        {
            $this->data['subscription']['dstatus'] = 'active';
            $this->data['subscription']['type'] = '';
            $this->data['subscription']['msg'] = '';   
        }
      
        $this->load->blade('jobs/add_new',$this->data);
    }

    function save_job(){
        if($this->validateFields()){
            $jobDetails = $this->input->post();
            if(array_key_exists('position_type', $jobDetails)){
                $jobDetails['position_type'] = implode(', ', $jobDetails['position_type']);
            }else{
                $jobDetails['position_type'] = '';
            }
            $jobDetails['user_id'] = get_user('id');
            //p($jobDetails);

            if(!empty($jobDetails['notify_agencies']) && !empty($jobDetails['preferred_agencies'])){
                $jobDetails['notify_preferred'] = 1;
            }

            $jobData = Job::create($jobDetails);


            if(!empty($jobDetails['preferred_agencies'])){
                foreach ($jobDetails['preferred_agencies'] as $preVal){
                    $arrPreferred = array(
                        "job_id" => $jobData->id,
                        "agency_id" => $preVal
                    );
                   Job_preferred_agencies::create($arrPreferred);
                }
            }



            // Email notification after added a job to admin only

            // CREATE EMAIL VARIABLE

            $email_variable['FIRSTNAME'] = ucfirst(get_user('first_name'));
            $email_variable['LASTNAME'] = ucfirst(get_user('last_name'));
            $email_variable['TYPE'] = ucfirst(get_user('type'));
            $email_variable['TITLE'] = ucfirst($jobDetails['title']);
            $email_variable['SALARY'] = $jobDetails['salary'];
            $email_variable['POSITIONTYPE'] = ucfirst($jobDetails['position_type']);
            $email_variable['OPENINGS'] = $jobDetails['openings'];

            $email_data=email_template(4,$email_variable);

            if(isset($email_data)){
                $emailData = [
                    'to'        => ADMIN_EMAIL,
                    'from'      => WEBSITE_FROM_EMAIL,
                    'subject'   => $email_data['template_subject']
                ];

                $emailData['body'] = $email_data['template_body'];
                email($emailData);
            }
			
			// Redirect to the detail page of the Job just created
			
			//$this->load->blade('jobs/view_detail',);
			redirect(base_url('jobs/view_detail'.'/'.$jobData->id));
			
            //$this->set_flashMsg('success','Congratulations! Your job listing has been successfully added.');
            //redirect(base_url('jobs'));
        }
    }

    function add_agencies($job_id = NULL){

        $job = Job::find($job_id);
        $job->add_agency = 1;
        $job->save();

        echo "<script>
        window.location.href='../../dashboard';
        alert('Your request has been sent. You can now review agency recruiters that express interest in recruiting for this job. You can approve up to three additional agency recruiters.');
        </script>";
        
    }
	
	function add_preferred_agencies($job_id = NULL, $place = NULL){
		$job = Job::find($job_id);
		$prefered_agency = User::find($this->input->post('PreferredIdList'));
		$added_agency_data = array("user_id" => $prefered_agency->id,
                                    "job_id" => $job_id,
                                    "estatus" => 1,
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'));

        $added_agency = Accepted_job::create($added_agency_data);
		
		$user_notification = array(
                            "notification_text" => 'Congratulations! You have been added as an approved agency recruiter for the following TalentGram: '.ucfirst($job->title),
                            "notification_url" => 'searches/job_detail/'.$job->id.'/any/new000',
                            "user_id" => $prefered_agency->id,
                            "status" => 0,
                            "cn_status" => 0
                        );

        $notificationData = User_notifications::create($user_notification);
		
		$useremail = $prefered_agency->email;
        $email_variable['TALENTGRAMNAME'] = ucfirst($job->title);
        $email_data=email_template(32,$email_variable);
		if($email_data)
		{
                $emailData = [
                    'to'        => $useremail,
                    'from'      => WEBSITE_FROM_EMAIL,
                    'subject'   => $email_data['template_subject']
                ];

                $emailData['body'] = $email_data['template_body'];
                email($emailData);
        }
		
		if ($place=='index')
		{
			$return_link = base_url('dashboard/index/'.$job_id);
		}
		else if($place=='dash') 
		{
			$return_link = base_url('dashboard');
		}
		echo "<script>
			window.location.href='".$return_link."';
			alert('Agency Added Successfully!');
			</script>";
	}
    function view_detail($id = null){
        if($id == null || !is_numeric($id))
            redirect(base_url('jobs'));
        $this->data['job'] = Job::find($id);
        $this->data['candidates'] = $this->data['job']->candidates()->orderBy('updated_at','desc')->get();
        $this->data['agencies'] = $this->data['job']->accepted_by_agencies()->with('user_profile')->get();
		$this->data['jobattachmentuploadFields'] = $this->jobattachmentuploadFields;
        //$this->data['agencies'] = $this->data['job']->candidates()->agency()->with('user_profile')->get();
        //p($this->data['agencies']->toArray());
        $this->load->blade('jobs/view_detail',$this->data);
    }

    private function get_values($job_id = NULL){
        $userType = get_user('type');
        $userId = get_user('id');
        $this->jobDetailsFields[16]['placeholder'] =  ($userType == 'employer')  ? "Company Name" : $this->jobDetailsFields[16]['placeholder'];
        $this->jobDetailsFields[16]['value'] = ($userType == 'employer') ? User_profile::find(get_user('id'))->company_name : '';
        if($userType == 'employer') unset($this->jobDetailsFields[7], $this->jobDetailsFields[8], $this->jobDetailsFields[16], $this->jobDetailsFields[17]);

        //if($userType == 'agency') unset($this->jobDetailsFields[7]);


        if($job_id != NULL){
            $job = Job::find($job_id);

            $jobIndustry = $job->industry_id;

            $userPrefAgencies =  User::leftJoin('industry_user', function($ljoin){
                $ljoin->on('users.id', '=', 'industry_user.user_id');
            })->join('preferred_agencies', function($ljoin){
                $ljoin->on('users.id', '=', 'preferred_agencies.agency_id');
            })->where('preferred_agencies.user_id', $userId)->where('users.type', 'agency')->where("industry_id", $jobIndustry)->get()->toArray();
            $userVal = array();
            $userAgencyIds = array();
            if(!empty($userPrefAgencies)){
                foreach ($userPrefAgencies as $prefVal) {
                    $userAgencyIds[] = $prefVal['id'];
                    $userVal[$prefVal['id']] = $prefVal['first_name'] . " " . $prefVal['last_name'];
                }
            }
            $this->jobDetailsFields[5]['options'] = $userVal;
            $job_preferred_agencies = Job_preferred_agencies::where("job_id", $job_id)->get()->toArray();

            foreach ($this->jobDetailsFields as &$field){
                if($field['name'] == 'position_type[]')
                    $field['selected'] = (strpos($job->position_type, ', ') !== false) ? explode(', ', $job->position_type) : $job->position_type ;
                else
                    $field['value'] = $job->{$field['name']};

            }
            if(!empty($job->notify_preferred)){
                $this->jobDetailsFields[3]['value'] = 1;
                $this->jobDetailsFields[4]['value'] = 0;
                $this->jobDetailsFields[3]['checked'] = "true";
            }else{
                $this->jobDetailsFields[3]['value'] = 1;
                $this->jobDetailsFields[4]['value'] = 0;
                $this->jobDetailsFields[4]['checked'] = "true";
            }

            if($job->client_name_confidential == 1){
                $this->jobDetailsFields[17]['checked'] = "true";
            }


            $selectedValArr = array();
            foreach ($this->jobDetailsFields as $key => $field){
                if($field['name'] == 'preferred_agencies[]'){
                    foreach ($job_preferred_agencies as $key => $prefVal) {
                        if (in_array($prefVal['agency_id'], $userAgencyIds)) {
                            $arrKey = array_search($prefVal['agency_id'], $userAgencyIds);
                            $selectedValArr[] = $userAgencyIds[$arrKey];
                        }
                    }
                    $this->jobDetailsFields[5]['selected'] = $selectedValArr ;
                }

            }

        }

        return $this->jobDetailsFields;
    }

    function get_profession($industryId){
        $category =  Industry::find($industryId)->professions()->lists('title','id');
        $resultArray = [];
        foreach ($category as $id=>$val){
            $resultArray[] = ["id"=>$id, "text"=>$val];
        }
       echo json_encode($resultArray);
    }

    function get_agencies($industryId){
        $userType = 'agency';
        $userId = get_user('id');
//        $category =  User::where("type", 'agency')->users()->where("industry_id", $industryId);
        $agencies =  User::leftJoin('industry_user', function($ljoin){
            $ljoin->on('users.id', '=', 'industry_user.user_id');
        })->join('preferred_agencies', function($ljoin){
            $ljoin->on('users.id', '=', 'preferred_agencies.agency_id');
        })->where('preferred_agencies.user_id', $userId)->where('users.type', 'agency')->get()->unique()->toArray();

        $resultArray = [];
        
        foreach ($agencies as $id=>$val){
            $resultArray[] = ["id"=>$val['id'], "text"=>$val['first_name'] . " ". $val['last_name']];
        }
        echo json_encode($resultArray);
    }

    function candidate_detail($job_id, $candidate_id){
        if($candidate_id == null || !is_numeric($candidate_id))
            redirect(base_url('jobs'));
        $this->data['job'] = Job::find($job_id);
        $this->data['candidates'] = $this->data['job']->candidates()->orderBy('updated_at','desc')->get();
        $this->data['candidate'] = Candidate::with('hire_details')->find($candidate_id);
        //$this->data['agencies'] = $this->data['job']->accepted_by_agencies()->get()->toArray();
        $this->data['agencies'] = $this->data['candidate']->agency()->get();
        $this->data['user_type'] = get_user('type');

        $this->data['candidatesdocuments']  = Candidate_documents::where([
            'candidate_id' => $candidate_id
        ])->get();
        
        $this->data['conversation_data'] = Candidate_mail_messaging::where('candidate_id','=',$candidate_id)->where('job_id','=',$job_id)->orderby('id','DESC')->get();

        if($this->data['conversation_data']->count()>0){

            $subject_length = strpos('RE: TalentCapture Messaging - T-'.$candidate_id.'-C-'.$job_id.'-S-','-S-') + 3;
            
            $this->data['old_subject'] = substr($this->data['conversation_data'][0]["conversation_subject"],$subject_length);
        }

        $this->data['hasHireDetails'] = $this->data['candidate']->hire_details()->where('type','=','Request Payment')->where('hire_cancelled','!=',1)->count();
        //p($this->data['candidate']->added_by()->first());
        $this->data['candidateFields'] = $this->candidateFields;

        /*Reject Reason options list*/
        $this->data['reject_reasons'] = My_reject_reasons::withTrashed()->orderBy('reject_option')->get();

        /* Fetching the candidate message template*/
        $this->data['message_template'] = Message_templates::all();
        
        header("X-Frame-Options: ALLOW-FROM https://docs.google.com");
        $this->load->blade('jobs/view_candidate_detail',$this->data);
    }

    function  upload_attachment(){
        $uploadData = upload_file('resume', ['upload_path' => APPPATH.'../public/uploads/docs/', 'allowed_types' => 'pdf|xsl|doc|docx' ]);
        $candidateDetails = $this->input->post('candidates');
        if($this->validateFields() && $uploadData['success']){
            $arrCandidateJobid = explode('_',$candidateDetails['candidatejob_id']);
            $candidateDetails['title']     = $uploadData['upload']->file_name;
            $candidateDetails['file_path'] = $uploadData['upload']->upload_path;
            $candidateDetails['candidate_id'] = $arrCandidateJobid[0];
            $candidate = Candidate_documents::create($candidateDetails);
			$candidate = Candidate::find($arrCandidateJobid[0]);
			$candidate->resume = $uploadData['upload']->file_name;
			$candidate->save();
            $this->set_flashMsg('success','Your attachment is successfully uploaded');
            redirect(base_url('jobs/candidate_detail/'.$arrCandidateJobid[1].'/'.$arrCandidateJobid[0]));
        }else{
            $this->set_flashMsg('error','File type uploaded is not allowed');
            redirect(base_url('jobs/candidate_detail/'.$arrCandidateJobid[1].'/'.$arrCandidateJobid[0]));
        }
    }

    function agency_detail($job_id, $agency_id){
        if($agency_id == null || !is_numeric($agency_id))
            redirect(base_url('jobs'));
        $this->data['job'] = Job::find($job_id);
        $this->data['candidates'] = $this->data['job']->candidates()->orderBy('client_accepted','desc')->get();
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
        $userFavAgenciesList = User::find(get_user('id'))->favourite_agencies()->lists('id');
        $this->data['isFavourite'] = in_array($agency_id, $userFavAgenciesList);
        $this->load->blade('jobs/view_agency_detail',$this->data);
    }

    function candidate_rsvp($candidateId, $rsvp){

        $result = ['status'=> 0,'msg'=>''];
        $available_rsvp = ['accept' => 1 , 'reject' => -1];

        if(!in_array($rsvp, array_keys($available_rsvp)))
            redirect(base_url());
        $candidate = Candidate::find($candidateId);
        $candidate->client_accepted = $available_rsvp[$rsvp];
		if ($rsvp=='accept')
		{
			$candidate->client_accepted_at=date("Y-m-d H:i:s");
		}

        // update andidate rejected reason and rejected date
        if($rsvp=='reject')
        {
            if($this->input->post('reject_reason')=='other')
            {
                $reject_reason       = $this->input->post('reject_reason').'('.$this->input->post('other_text').')';
                $reject_reason_email = $this->input->post('other_text');
            } else {
                $reject_reason       = $this->input->post('reject_reason');
                $reject_reason_email = $this->input->post('reject_reason');
            }
            $candidate->candidate_rejected_at=date("Y-m-d H:i:s");
            $candidate->rejected_reason=$reject_reason;
            
        }

        $result['status'] = $candidate->save();
        if($result['status']){
            $class = ($candidate->client_accepted == 1) ? 'success' : 'danger';
            $result['msg'] = "<label class='label label-$class'>".ucfirst($rsvp)."ed</label>";
            // Candidate rejected message
            if($rsvp=='reject')
            {
                $result['msg'] = "<label class='label label-$class'>Candidate successfully rejected. We have notified the agency recruiter who represents the candidate.</label>";
            }

            $useremail = $candidate->agency()->first()->email;
            $userprofile = User_profile::find(get_user('id'));
            $User = User::find(get_user('id'));
            $job = Job::find($candidate->applied_job()->first()->id);

            // SEND MAIL AFTER ACCEPT AND REJECT CANDIDATE
            // CREATE EMAIL VARIBALE
            $email_variable['CANDIDATENAME'] = ucfirst($candidate->name);
            $email_variable['COMPANYNAME'] = ucfirst($userprofile->company_name);
            $email_variable['JOBTITLE'] = ucfirst($job->title);
            $email_variable['FIRSTNAME'] = ucfirst($User->first_name);
            $email_variable['LASTNAME'] = ucfirst($User->last_name);
            // Candidate rejected message
            if($rsvp=='reject')
            {
               $email_variable['REJECTREASON'] = $reject_reason_email;
            }


            if($rsvp == 'accept'){
                $email_data=email_template(5,$email_variable);
				
				//update the status of the talentgram modification for RP-640
				
				$jobID=$candidate->job_id;
				$candidate_owner=$candidate->user_id;
				$updated_date=date("Y-m-d H:i:s");
				$query = "UPDATE accepted_jobs SET estatus ='1', updated_at='$updated_date' WHERE job_id='$jobID' AND user_id='$candidate_owner';";
				$this->db->query($query);
            }else{
                $email_data=email_template(6,$email_variable);
            }

            if($email_data){
                $emailData = [
                    'to'        => $useremail,
                    'from'      => WEBSITE_FROM_EMAIL,
                    'subject'   => $email_data['template_subject']
                ];

                $emailData['body'] = $email_data['template_body'];
                email($emailData);
            }

            // Set the notification
            $user_notification = array(
                        "notification_text" => 'Candidate '.ucfirst($candidate->name).' has been '.$rsvp.'ed',
                        "notification_url" => 'searches/candidate_detail/'.$job->id.'/'.$candidateId.'/any/new000',
                        "user_id" => $candidate->agency()->first()->id,
                        "status" => 0,
                        "cn_status" => 0
                    );

            $notificationData = User_notifications::create($user_notification);
        }

		$job_Id = $candidate->job_id;
        if($rsvp=='reject')
        {
			//$this->update_candidate_actions($job_Id,"candidates_rejected");
			
			if($this->input->post('reject_return_path')=='item')
			{
				return redirect(base_url('dashboard/index/'.$job_Id.'?cddtrejmsg=1'));
			}
			elseif($this->input->post('reject_return_path')=='dashboard')
			{
				return redirect(base_url('dashboard?cddtrejmsg=1'));
			} 
			elseif($this->input->post('reject_return_path')=='jobs')
			{
				return redirect(base_url('jobs/candidate_detail/'.$job_Id.'/'.$candidateId.'?cddtrejmsg=1'));
			}
        }
//		else
//		{
//			$this->update_candidate_actions($job_Id,"candidates_accepted");
//		}

        echo json_encode($result); die();
        redirect(base_url('dashboard'));
    }


    function add_feedback($candidateID){
        // This message is copy for message(chat)

        if($this->input->post('job_provider')!='') {
            $msg_chat['type']         = '1'; 
            $msg_chat['to_user_id']   = $this->input->post('job_provider');
            $msg_chat['text']         = $this->input->post('employer_feedback');
            $msg_chat['from_user_id'] = get_user('id');
            $msg_chat['candidate_id'] = $candidateID;
            $msg_chat['job_id']       = $this->input->post('job_id');

            $message_chat = Message::create($msg_chat);
            // $this->db->insert('messages',$msg_chat);
        }
        $note = Candidate_feedback::create(['feedback'=>$this->input->post('employer_feedback'), 'user_id'=>get_user('id'), 'candidate_id'=>$candidateID]);
        $note->load('added_by');
        $result['status'] = 1;
        $result['msg'] = $this->load->blade('partials/_note',compact('note'),true);
        //Sending email to Agency who added the candidate
        $candidate = Candidate::find($candidateID);
        $useremail = $candidate->agency()->first()->email;
        $candidate_name = $candidate->name;
        $job_id = $candidate->job_id;
        $job_details = Job::find($job_id);
        $job_title = $job_details->title;

        // Send CANDIDATE FEED BACK EMAIL
        $email_variable['JOBTITLE'] = ucfirst($job_title);
        $email_variable['CANDIDATENAME'] = ucfirst($candidate_name);
        $email_variable['EMPLOYEERFEEDBACK'] = $this->input->post('employer_feedback');

        $email_data=email_template(7,$email_variable);

        if(isset($email_data)){
            $emailData = [
                'to'        => $useremail,
                'bcc'       => ADMIN_EMAIL,
                'from'      => WEBSITE_FROM_EMAIL,
                'subject'   => $email_data['template_subject']
            ];

            $emailData['body'] = $email_data['template_body'];
            email($emailData);
        }

        // Set the notification
        $User = User::find(get_user('id'));
        $user_notification = array(
                    "notification_text" => 'Feedback has been provided by '.ucfirst($User->first_name).' '.ucfirst($User->last_name).' for your candidate '.ucfirst($candidate_name),
                    "notification_url" => 'searches/candidate_detail/'.$job_id .'/'.$candidate->id.'/any/any',
                    "user_id" => $candidate->agency()->first()->id,
                    "status" => 0,
                    "cn_status" => 0
                );
        $notificationData = User_notifications::create($user_notification);
        echo json_encode($result); die();
    }

    /*Modification done for the RP-789 and RP-790*/

    function send_mail_to_candidate($candidate_id)
    {
        if(count($this->input->post())>0) 
        {
            $new_template = 0;                                              // saving the candidate message
            $save_template = $this->input->post('save_template');
            $template_name = $this->input->post('template_name');
            if ($save_template == '1') {
                $existing_template = Message_templates::where(['template_name'=>$template_name])->first();
                if (!empty($existing_template)) {
                    $data = array(
                        'subject' => $this->input->post('subject'),
                        'template_name' => $this->input->post('template_name'),
                        'template' => $this->input->post('last_conversation')
                    );
                    $this->db->where('template_name',$template_name);
                    $this->db->update('message_templates',$data);
                }else{
                    $new_template= Message_templates::create(['subject'=>$this->input->post('subject'), 'template_name'=>$template_name, 'template'=>$this->input->post('last_conversation')])->id;
                }
            }

            $job_details = Job::find($this->input->post('job_id'));
            $candidate_details = Candidate::find($this->input->post('candidate_id'));

            $msg_chat['job_owner_id'] = $job_details->user_id;

            $candidate_owner_details = $candidate_details->agency()->first();

            $msg_chat['candidate_owner_id'] = $candidate_owner_details->id;

            $msg_chat['candidate_owner_name'] = $candidate_owner_details->first_name.' '.$candidate_owner_details->last_name;

            $msg_chat['candidate_owner_Company_name'] = $candidate_details->agency()->first()->user_profile()->first()->company_name;

            $msg_chat['talent_gram_name'] = $job_details->title;

            $msg_chat['job_owner_company_name'] = User_profile::find($msg_chat['job_owner_id'])->company_name;

            $href_link = base_url().'news/jobdescription/'.$this->input->post('job_id').'/'.$msg_chat['candidate_owner_id'].'/email-link';
            
            $msg_chat['candidate_id'] = $this->input->post('candidate_id');
            $msg_chat['job_id'] = $this->input->post('job_id');
            $msg_chat['message_subject'] = "<h5><b>Subject:</b> ".$this->input->post('subject')."</h5>";
            $msg_chat['candiate_name'] = "<h5><b>To: </b>". $candidate_details->name ."</h5>";
            $msg_chat['last_conversation'] = "<div style='padding-top: 10px;'>". $this->input->post('last_conversation'). "</div>";

            $msg_chat['signature'] = "<br/><br/><br/><div style='". "font-style: italic;font-size: small;font-family:Times New Roman, Times, serif;'".">You have been submitted as a candidate for an opening on the TalentCapture Recruiting Platform by <strong>".$msg_chat['candidate_owner_name']."</strong> with <strong>".$msg_chat['candidate_owner_Company_name']."</strong> for the <a href=". $href_link." target='_blank'><strong>".$msg_chat['talent_gram_name']."</strong></a> opportunity with <strong>".$msg_chat['job_owner_company_name']."</strong></div>";

            $msg_chat['last_conversation'] = "Message From: ".ucwords(get_user('first_name').' '.get_user('last_name')). " @ " .User_profile::find($msg_chat['job_owner_id'])->company_name ."\r\n\r\n" . $msg_chat['last_conversation']. $msg_chat['signature'];

            $candidate_email = $candidate_details->email;

            // <Job title> - <Candidate Name> - candidate message - T-<candidate id>-C-<job id>
            $subject = 'TalentCapture Messaging - T-'.$this->input->post('candidate_id').'-C-'.$this->input->post('job_id').'-S-'.$this->input->post('subject');
            $msg_chat['conversation_subject'] = $subject;

            $msg_id = Candidate_mail_messaging::create($msg_chat)->id;

            $userprofile = User_profile::find(get_user('id'));

            $emailData = [
                'to' => $candidate_email,       
                'from'      => $userprofile->tc_email_alias,
                'subject'   => $subject                      
            ];

            //$emailData['body'] = $msg_chat['last_conversation'];
            $msg_chat['last_conversation'] = "<div>". $this->input->post('last_conversation'). "</div>";

            $msg_chat['last_conversation'] = "Message From: ".ucwords(get_user('first_name').' '.get_user('last_name')). " @ " .$candidate_details->agency()->first()->user_profile()->first()->company_name ."\r\n\r\n" .$msg_chat['last_conversation']. $msg_chat['signature'];

            $last_conversation_newline = $msg_chat['last_conversation'];
            $emailData['body'] = $last_conversation_newline;

            email($emailData);

            $user_notification = array(
                        "notification_text" => ucfirst($userprofile->company_name).' has messaged your candidate: '.ucfirst($candidate_details->name),
                        "notification_url" => 'searches/candidate_detail/'.$msg_chat['job_id'] .'/'.$msg_chat['candidate_id'].'/any/none/email',
                        "user_id" => $msg_chat['candidate_owner_id'],
                        "status" => 0,
                        "cn_status" => 0
                    );

            $notificationData = User_notifications::create($user_notification);
            //$coversation = Candidate_mail_messaging::where('candidate_id','=',$msg_chat['candidate_id'])->where('job_id','=',$msg_chat['job_id'])->get();
            $coversation = Candidate_mail_messaging::find($msg_id);
            $result['status'] = 1;
            $result['update_conversation'] = $msg_chat['last_conversation'];
            if ($new_template != 0) {
                $result['message_template'] = Message_templates::find($new_template);
            }else{
                $result['message_template'] = '';
            }

            $result['msg'] = $this->load->blade('partials/_coversation',compact('coversation'),true);
            
            echo json_encode($result); die();
            
        }
    }


    function update_latest_messaging($job_id,$candidate_id)
    {
        $coversation= Candidate_mail_messaging::where('candidate_id','=',$candidate_id)->where('job_id','=',$job_id)->get();
        $result['status'] = 1;
        $result['update_conversation'] = $msg_chat['last_conversation'];
        $result['msg'] = $this->load->blade('partials/_coversation',compact('coversation'),true);
        echo json_encode($result); die();

    }


    public function update_job_response($rsvp, $agent_id = NULL,$job_id = NULL) {
    //below section is commented regarding RP-738 this was ent to update the last viewed job type (Agency type or Employer type)	
	//this feature needs to be disabled since it affect the requirements of RP-738
    //  $query = "SELECT users.type FROM users, jobs WHERE users.id=jobs.user_id AND jobs.id = '$job_id';";
    //	$result123 = $this->db->query($query)->result();	
    // $save_type = $result123[0]->type;		
   //	$query = "UPDATE users SET last_job_type ='$save_type' WHERE id='".get_user('id')."';";
   //	$this->db->query($query);
		
		$rsvp = strtolower($rsvp);
        if(!in_array($rsvp, ['accepted', 'rejected'])) die('Invalid URL');
        $response = ['status'=>0, 'msg'=> ''];

        $job = Job::findOrFail($job_id);
        $User = User::find($agent_id);
        $agencyemail = $User->email;

        try{

            if($rsvp == 'rejected'){
               $user = Accepted_job::where('job_id','=',$job_id)->where('user_id','=',$agent_id)->first();
			   $user->estatus = 2;//+
               $user->save();//+
               //$user->delete();
				
               $msg = 'Agency Removed';
               $response = ['status'=>1, 'msg'=> $msg];

               $email_variable['JOBNAME'] = ucfirst($job->title);
                $email_data = email_template(21,$email_variable);

                if($email_data){
                    $emailData = [
                        'to'        => $agencyemail,
                        'from'      => WEBSITE_FROM_EMAIL,
                        'subject'   => $email_data['template_subject']
                    ];

                    $emailData['body'] = $email_data['template_body'];
                    email($emailData);

                }

                
            }
            if($rsvp == 'accepted')
            {
                $user = Accepted_job::where('job_id','=',$job_id)->where('user_id','=',$agent_id)->first();
                $this->db->set('updated_at',date('Y-m-d H:i:s'));
                $this->db->where('id', $job_id);
                $this->db->update('jobs');
                $user->estatus = 1;
                $user->save();
                $msg = 'Agency Approved';
                $response = ['status'=>1, 'msg'=> $msg];

                $User1 = User::find(get_user('id'));
                $user_notification = array(
                            "notification_text" => 'You have been approved as an agency for the following TalentGram: '.ucfirst($job->title),
                            "notification_url" => 'searches/job_detail/'.$job->id.'/any/new000',
                            "user_id" => $agent_id,
                            "status" => 0,
                            "cn_status" => 0
                        );

                $notificationData = User_notifications::create($user_notification);

                $email_variable['JOBNAME'] = ucfirst($job->title);
                $email_data = email_template(20,$email_variable);

                if($email_data){
                    $emailData = [
                        'to'        => $agencyemail,
                        'from'      => WEBSITE_FROM_EMAIL,
                        'subject'   => $email_data['template_subject']
                    ];

                    $emailData['body'] = $email_data['template_body'];
                    email($emailData);
                }

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


    function close_job($job_id, $onlyHire = 0){
        $this->data['job'] = Job::find($job_id);
        $this->data['candidates'] = $this->data['job']->candidates()->orderBy('client_accepted','desc')->get();
        $this->data['agencies'] = $this->data['job']->accepted_by_agencies()->with('user_profile')->get();
        //$this->data['accepted_agencies'] = $this->data['job']->accepted_by_agency()->get()->toArray();
        $user = User::find(get_user('id'));
        $this->data['agencies_ratings'] = $user->ratings()->lists('rating','agency_id');
        $this->data['favourite_agencies'] = $user->favourite_agencies()->lists('agency_id');
        $this->data['candidates_list'] = $this->data['job']->candidates()->where("client_accepted",1)->lists("name", 'id');
        $this->data['page_name'] = ($onlyHire) ? 'Hire Candidates' : 'Close Job';
        $this->load->blade('jobs/close_job',$this->data);
    }

    function close($id){
        $this->hire_candidates();
        $job = Job::find($id);
        $job->closed = 1;
        $job->save();

        //Send email to all agency that added that candidate
        $arruserjobaccepted = $job->accepted_by_agencies()->get()->toArray();

        //Get the agencies with review info
        $review_array = $this->input->post('agency_rating');
        $reviewed_agencies = ''; 

        if(!empty($arruserjobaccepted)){

            foreach ($arruserjobaccepted as $jobdetails) {
                $tempuserarr[] = $jobdetails['pivot']['user_id'];
            }
            $tempuserarr = array_unique($tempuserarr);

            foreach ($tempuserarr as $userid) {

                $userhiredforjob = Candidate::where([
                    'user_id' => $userid,
                    'job_id' => $id,

                ])->get()->toArray();

                if(empty($userhiredforjob)){
                    $userprofile = User_profile::find(get_user('id'));
                    $email_variable['COMPANYNAME'] = ucwords($userprofile->company_name);
                    $email_variable['JOBTITLE'] = ucfirst($job->title);

                    $email_data=email_template(8,$email_variable);
                    if(isset($email_data)){
                        $useremail = User::find($userid);
                        $emailData = [
                            'to' => $useremail->email,
                            'bcc' => ADMIN_EMAIL,
                            'from' => WEBSITE_FROM_EMAIL,
                            'subject' => $email_data['template_subject']
                        ];

                        $emailData['body'] = $email_data['template_body'];
                    }

                    email($emailData);
                
                    //Set the notification
                    $user_notification = array(
                            "notification_text" => ucwords($userprofile->company_name).' has closed the '.ucfirst($job->title).' TalentGram',
                            "notification_url" => 'searches/job_detail/'.$id.'/new000/any',
                            "user_id" => $userid,
                            "status" => 0,
                            "cn_status" => 0
                        );

                    $notificationData = User_notifications::create($user_notification);
                    
                }

                $review_details = $review_array[$userid];

                $review_userprofile = User_profile::find($userid);

                $reviewed_agencies = $reviewed_agencies. $review_userprofile->company_name.': '.$review_details['rating'].'<br>'.$review_details['review'].'<br><br>';
            }

            //Get hired candidates' details
            $hired_candidates_names = '';

            if($hired_candidates = $this->input->post('hired_candidates')){
                foreach ($hired_candidates as $hiredCandidate) {
                    $candidate = Candidate::find($hiredCandidate['candidate_id']);
                    $hired_candidates_names = $hired_candidates_names.$candidate->name.'<br>';
                }
            }
            else
            {
                $hired_candidates_names = '-';
            }
            $email_variable_cls['USERNAME'] = ucwords(get_user('first_name').' '.get_user('last_name'));
            $email_variable_cls['JOBNAME'] = ucfirst($job->title);
            $email_variable_cls['CANDIDATELIST'] = $hired_candidates_names;
            $email_variable_cls['AGENCYLIST'] = $reviewed_agencies;

            $email_data_cls=email_template(23,$email_variable_cls);
            if(isset($email_data_cls)){
                $emailData_cls = [
                    'to' => ADMIN_EMAIL,
                    'from' => WEBSITE_FROM_EMAIL,
                    'subject' => $email_data_cls['template_subject']
                ];

                $emailData_cls['body'] = $email_data_cls['template_body'];
            }

            email($emailData_cls);


        }      

        /*RP-273 Changed Messaged */
       // $this->set_flashMsg('success', 'You will no longer receive candidates for this Job. You can copy this Job as new in the future should you choose to reopen it.');
        $this->set_flashMsg('success', 'This job is now closed. You can copy this job as new at any time.');
        redirect(base_url('jobs/view_detail/'.$id));
    }

    function hire_candidates($jobID = false, $candidateId = false, $callFromDashBoard = false){
        
        $user_profile=User_profile::where(array('user_id'=>get_user('id')))->first(['company_name']);
        if($hired_candidates = $this->input->post('hired_candidates')){
            foreach ($hired_candidates as $hiredCandidate) {
                $hiredCandidate['added_by'] = get_user('id');
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
                    //Send email to agency that added that candidate
                    // Create Email variable
                    $email_variable['CANDIDATENAME'] = $candidate->name;
                    $email_variable['STARTDATE'] = date("n-j-Y", strtotime($hiredCandidate['start_date']));
                    $email_variable['BASESALARY'] = $hiredCandidate['base_salary'];
                    $email_variable['FIRSTNAME'] = get_user('first_name');
                    $email_variable['LASTNAME'] = get_user('last_name');

                    $useremail = $candidate->agency()->first()->email;

                    $emailData['body'] = "";
                    $userType = get_user('type');
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



                    //Set notification
                    $user_notification = array(
                        "notification_text" => 'Your candidate '.$candidate->name.' has been hired by '.$user_profile->company_name,
                        "notification_url" => 'searches/candidate_detail/'.$jobID.'/'.$candidateId.'/any/new000',
                        "user_id" => $candidateOwner,
                        "status" => 0,
                        "cn_status" => 0
                    );

                    $notificationData = User_notifications::create($user_notification);
                }

            }
        }
        $this->sync_ratings_and_favourites();
        if($callFromDashBoard){
            $this->set_flashMsg('success','Congratulations on hiring this candidate. A representative will follow up with you soon to verify the details.');
            redirect(base_url('dashboard'));
        }
        elseif($jobID && $candidateId){
            $this->set_flashMsg('success','Congratulations on hiring this candidate. A representative will follow up with you soon to verify the details.');
            redirect(base_url('jobs/candidate_detail/'.$jobID.'/'.$candidateId));
        }
        elseif($jobID){
            $this->set_flashMsg('success','Selected candidate(s) have been hired Successfully !!');
            redirect(base_url('jobs/view_detail/'.$jobID));
        }
    }

    function sync_ratings_and_favourites(){
        //Get the accepted users
        $accepted_users = $this->input->post('agency_rating');

        // Remove the records with 0
        if(count($this->input->post('agency_rating'))){
            foreach ($accepted_users as $key => $accepted_user) {
                if($accepted_user['rating'] == 0){
                   unset($accepted_users[$key]);
                }
            }
        }
        // Sync Agency Ratings  
        if(count($accepted_users))
            User::find(get_user('id'))->ratings()->sync($accepted_users);

        // Sync Favourite Agencies
        if(count($this->input->post('favourite_agencies')))
            User::find(get_user('id'))->favourite_agencies()->sync($this->input->post('favourite_agencies'));
    }

    function reopen_job($job_id){
        $job = Job::find($job_id)->toArray();
        unset($job['id'],$job['status'], $job['closed']);
        $job = Job::create($job);
        redirect(base_url('jobs/view_detail/'.$job->id));
    }

    function delete_job($job_id){
        if(Job::find($job_id)->user()->first()->id == get_user('id')){
            Job::destroy($job_id);
            $this->set_flashMsg('success','The Job has been successfully deleted');
        }else{
            $this->set_flashMsg('error','You do not have permission to delete this Job');
        }
        redirect(base_url('jobs'));
    }

    function edit_job($job_id){
        $job = Job::find($job_id);
        $job->load('user');
        if($job->user->id != get_user('id')){
            $this->set_flashMsg('warning','You are not authorised to change this Job Details.');
            redirect(base_url('jobs/view_detail/'.$job->id));
        }
        $this->data['job'] = $job;
        $this->data['jobDetailsFields'] = $this->get_values($job_id);
        //p($this->data['jobDetailsFields']);
        $this->load->blade('jobs/edit_job',$this->data);
    }

    function update_job($job_id){
        $job = Job::find($job_id);
        $job->load('user');
        if($job->user->id != get_user('id')){
            $this->set_flashMsg('warning','You are not authorised to change this Job Details.');
            redirect(base_url('jobs/view_detail/'.$job->id));
        }
        $jobDetails = $this->input->post();
        foreach ($this->input->post() as $fieldName => $value){
            if($fieldName == 'position_type')
                $job->position_type = implode(', ', $value);
            else
                $job->$fieldName = $value;
        }
        //if(!empty($jobDetails['notify_agencies']) && !empty($jobDetails['preferred_agencies'])){
         //   $job->notify_preferred = 1;
        //}else{
        //    $job->notify_preferred = 0;
        //}

        if(!isset($jobDetails["client_name_confidential"]))
        {
            $job->client_name_confidential = 0;
        }
        else
        {
            $job->client_name_confidential = 1;
        }

        unset($job->notify_agencies);
        unset($job->preferred_agencies);
        $job->save();

        /**
        if(!empty($jobDetails['notify_agencies']) && !empty($jobDetails['preferred_agencies'])){
            $jobPreferred = Job_preferred_agencies::where("job_id", $job_id);
            $jobPreferred->delete();

            foreach ($jobDetails['preferred_agencies'] as $preVal){
                $arrPreferred = array(
                    "job_id" => $job_id,
                    "agency_id" => $preVal
                );
                Job_preferred_agencies::create($arrPreferred);
            }
        }else{
            $jobPreferred = Job_preferred_agencies::where("job_id", $job_id);
            $jobPreferred->delete();
        }
        */
        $this->set_flashMsg('success', 'The Job has been updated');
        redirect(base_url('jobs/view_detail/'.$job->id));
    }

  
    //added by ashish
    public function job_descriptionfor_pdf($id){

        $jobs = Job::find($id);
        $dompdf = new DOMPDF();
        /*Select edited job description by Agency */
        $edit_agency_job  = Agency_job_description::where(array('job_id'=>$id,'agency_id'=>get_user('id')))->first();
        if(count($edit_agency_job)>0){
            $jobs  = ['type' =>'editedbyagency', 'title'=> $jobs->title, 'agency_edit_job'=>$edit_agency_job];
        }
        $html = $this->load->blade('jobdescription',['jobs'=>$jobs],true);
        $dompdf->loadHtml($html);
        
        $dompdf->render();
 
        // Get the generated PDF file contents
        $pdf = $dompdf->output();
 
        // Output the generated PDF to Browser
       $dompdf->stream('test.pdf',['Attachment'=>0]);

    } 

    //added by ashish
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
    
function delete_job_attachment($job_id,$a_id){
		$adocument = Job_documents::find($a_id);
        $removefile = unlink('./public/uploads/docs/'.$adocument->title);
        if($removefile)
        {
			$adocument->delete();
            $this->set_flashMsg('success','Your attachment is successfully removed!');     
        }
		else
		{
			$this->set_flashMsg('error','The attachment was not deleted!');
		}
		redirect(base_url('jobs/view_detail/'.$job_id));
    }

function  upload_job_attachment($job_id){
        $uploadData = upload_file('jobattachment', ['upload_path' => APPPATH.'../public/uploads/docs/', 'allowed_types' => 'pdf|xsl|doc|docx' ]);
        if($this->validateFields() && $uploadData['success']){
            $job_doc_Details['title']     = $uploadData['upload']->file_name;
            $job_doc_Details['file_path'] = $uploadData['upload']->upload_path;
            $job_doc_Details['job_id'] = $job_id;
			$job_doc_Details['created_at'] = date("Y-m-d H:i:s");
            Job_documents::create($job_doc_Details);
            $this->set_flashMsg('success','Your attachment is successfully uploaded');
            redirect(base_url('jobs/view_detail/'.$job_id));
        }else{
            $this->set_flashMsg('error','File type uploaded is not allowed');
            redirect(base_url('jobs/view_detail/'.$job_id));
        }
    }
	
function send_group_messege($jobId){
        $newMessage = $this->input->post('new_grp_msg'.$jobId);
		$fromUserId = get_user('id');
		$cr_date = date("Y-m-d H:i:s");
		$query = "SELECT user_id FROM accepted_jobs WHERE job_id='$jobId';";
		$acceptedUsers = $this->db->query($query)->result();
		
        $msg = 'Message Successfully Delivered';
        $response = ['status'=>1, 'msg'=> $msg];
        

        foreach($acceptedUsers as $acceptedUser)
		{
            $msg = [];
            $msg['from_user_id'] = $fromUserId;
            $msg['to_user_id'] = $acceptedUser->user_id;
            $msg['text'] = $newMessage;

            $msg = Message::create($msg);
		}
	   $this->set_flashMsg('success','Message Successfully Delivered');
      
       redirect(base_url());
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
	function clear_candidate_actions($jobID,$actinType)
	{
		$query = "UPDATE jobs SET $actinType ='0' WHERE id='$jobID';";
		$this->db->query($query);
		echo json_encode("success");
	}
}