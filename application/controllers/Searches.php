<?php

class Searches extends MY_Controller{

    /**
     * @var array
     *
     * Candidate Filed Array
     */
    private $candidateFields = [
        [
            'name' => 'candidates[name]',
            'type' => 'text',
            'placeholder' => 'Name',
            'validation' => 'required'
        ],
        [
            'name' => 'candidates[email]',
            'type' => 'text',
            'placeholder' => 'Email',
            'validation' => 'valid_email|candidate_email_unique'
        ],
        [
            'name' => 'candidates[phone]',
            'type' => 'text',
            'placeholder' => 'Phone',
            'validation' => 'trim'
        ],
        [
            'name' => 'candidates[linkedin_url]',
            'type' => 'text',
            'placeholder' => 'Required',
            'validation' => 'required|valid_url',
            'for_diff-label' => 'social-icon-linked-in'
        ],
        [
            'name' => 'candidates[facebook_url]',
            'type' => 'text',
            'placeholder' => 'Not Required',                                                        
            'validation' => 'valid_url',
            'for_diff-label'    => 'social-icon-facebook'
        ],
        [
            'name' => 'candidates[city]',
            'type' => 'text',
            'placeholder' => 'City',
            'validation' => 'required'
        ],
        [
            'name' => 'candidates[state_id]',
            'type' => 'select',
            'placeholder' => 'State',
            'validation' => 'required',
        ],
        [
            'name' => 'candidates[salary]',
            'type' => 'text',                                                       // For RP- 804 adding new salary and residency field on add candidate page.
            'placeholder' => 'Desired Salary',
            'validation' => '',
        ],
        [
            'name' => 'candidates[residency]',
            'type' => 'select',
            'placeholder' => 'Residency',
            'validation' => 'required',
        ],
        [
            'name' => 'candidates[will_relocate]',
            'type' => 'select',
            'placeholder' => 'Will Relocate',
            'options' => ['No'=>"No", 'Yes'=>"Yes"],
            'validation' => 'required'
        ],
        [
            'name' => 'candidates[employment_history]',
            'type' => 'textarea',
            'placeholder' => "Type a summary, or copy and paste employment history here. DO NOT include candidate's name or contact details if you want to keep your candidate's contact information confidential until the candidate has been accepted. If the candidate is rejected, the contact details will not be released.",
            'validation' => 'required',
        ],
        [
            'name' => 'candidates[notes]',
            'type' => 'textarea',
            'rows' => 3,
            'placeholder' => 'If the hiring authority has provided interview questions to use for your interviews, enter the answers from the interview here.',
            'for_diff-label' => 'Interview Notes',
            'validation' => 'trim'
        ],
        [
            'name' => 'resume',
            'type' => 'file',
            'accept' => 'application/pdf,application/vnd.ms-excel',
            'placeholder' => 'Upload Resume',
            'validation' => 'required'
        ]
    ];

    private $candidateuploadFields = [
        [
            'name' => 'resume',
            'type' => 'file',
            'accept' => 'application/pdf,application/vnd.ms-excel',
            'placeholder' => 'Upload',
            'validation' => 'required'
        ]
    ];

    private $loggedInUser;

    /**
     * Constructor
     *
     * @param       
     * @return      
     */

    function __construct() {

        $this->authUserRequired = TRUE;
        parent::__construct();
		$this->loggedInUser = User::find(get_user('id'));
		
		//$query = "SELECT jobs.id,jobs.title FROM jobs WHERE jobs.id IN (SELECT accepted_jobs.job_id FROM accepted_jobs WHERE accepted_jobs.user_id = '$this->loggedInUser') AND jobs.closed='0' AND jobs.user_id IN (SELECT users.id FROM users WHERE users.type='employer')";
			//$result = $this->db->query($query)->get();
		
		
        //$this->data['saved_jobs'] = $result;
		
		//$query = "SELECT jobs.id,jobs.title FROM jobs WHERE jobs.id IN (SELECT accepted_jobs.job_id FROM accepted_jobs WHERE accepted_jobs.user_id = '$this->loggedInUser') AND jobs.closed='1' AND jobs.user_id IN (SELECT users.id FROM users WHERE users.type='employer')";
			//$result = $this->db->query($query)->get();
        
		//$this->data['closed_jobs'] =$result;
		
		$this->data['saved_jobs'] = User::find(get_user('id'))->accepted_jobs()->where(['closed'=>0])->orderBy('accepted_jobs.created_at','desc')->get();
		$this->data['closed_jobs'] = User::find(get_user('id'))->accepted_jobs()->where(['closed'=>1])->orderBy('accepted_jobs.created_at','desc')->get();
		//echo($this->data['jobOwner_type']);
        
        $userIndustrySpecialityAreas = $this->loggedInUser->industries()->lists('id');
        if((count($userIndustrySpecialityAreas))){
            $jobs = Job::active()->open()->where('user_id', '!=', get_user('id'))->whereIn('industry_id', $userIndustrySpecialityAreas);
            if($this->loggedInUser->type == 'agency'){
                $userProfessionSpecialityAreas = $this->loggedInUser->professions();
                if($userProfessionSpecialityAreas->count())
                    $jobs = $jobs->whereIn('profession_id', $userProfessionSpecialityAreas->lists('id'));
            }
            $exclude =  array_merge($this->loggedInUser->accepted_jobs()->lists('id'), $this->loggedInUser->rejected_jobs()->lists('id'), $this->getJobsAcceptedByMax());
            $this->data['newJobsAlert'] = (count($exclude)) ? $jobs->whereNotIn('id',$exclude)->get() : $jobs->get();

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


            $this->data['agency'] = User::with('user_profile')->find(get_user('id'));
            $this->data['agency']->load('agency_ratings', 'user_profile');
            $rejected_rater = array();
            foreach ($this->data['agency']->agency_ratings as $rating) {
                if($rating->pivot->rating <= 2.5){
                    $rejected_rater[] = $rating->pivot->user_id;
                }
            }
            $this->data['rejected_rater'] = $rejected_rater;
        }else{
            $this->data['newJobsAlert']  = [];
        }
    }

    /**
     * Index
     *
     * @param        
     * @return  array
     */
    function index(){
        $this->data['subscription'] = check_subscription();
        $this->load->blade('agency.searches', $this->data);
		
    }

    function job_detail($job_id,$job_type = "new000",$job_type2 = "new000"){
		//fixing archived jobs dropdown dissapearing issue
		$loged_user_id=get_user('id');
		$query = "SELECT estatus FROM accepted_jobs WHERE user_id='$loged_user_id' AND job_id = '$job_id';";
		$talentgram_type_result = $this->db->query($query)->result();
		$job = Job::find($job_id);
		if (count($talentgram_type_result)>0)
		{
			$talentgram_type = $talentgram_type_result[0]->estatus;
			
			if ($talentgram_type=='1')
			{
				if ($job->closed=='1')
				{
					$this->data['jobOwner_type_closed'] =$job_type2;
					$this->data['jobOwner_type'] ="none";
				}
				else
				{
					$this->data['jobOwner_type'] =$job_type;
					$this->data['jobOwner_type_closed'] ="none";
				}
			}
			else
			{
				$this->data['jobOwner_type_closed'] =$job_type2;
				$this->data['jobOwner_type'] ="none";
			}
		}
		else
		{
			$this->data['jobOwner_type'] =$job_type;
			$this->data['jobOwner_type_closed'] ="none";
		}
		
		
        //commenting code
		$this->data['My_job_id'] =$job_id;
        $this->data['subscription'] = check_subscription();

        $this->data['job'] = Job::find($job_id)->load('user');
        
//        $this->data['hasHireDetails'] = $this->data['candidate']->hire_details()->where(['type'=>'Request Payment'])->count();
        $this->data['candidates'] = $this->data['job']->candidates()->where('user_id', get_user('id'))->orderBy('updated_at','desc')->get();

        

        $this->data['is_message_enable'] = $this->isMessageEnable(get_user('id'),$job->user->id);

        foreach ($this->data['candidates'] as $key => $candidateVal){
            $hasHireDetails = Candidate::find($candidateVal->id)->hire_details()->where(['type'=>'Request Payment'])->count();
            $this->data['candidates'][$key]['hasHireDetails'] = $hasHireDetails;
        }
		//echo($this->data['job']);
		
	//below section is commented regarding RP-738 this was ent to update the last viewed job type (Agency type or Employer type)	
	//this feature needs to be disabled since it affect the requirements of RP-738
//		$query = "SELECT users.type FROM users, jobs WHERE users.id=jobs.user_id AND jobs.id = '$job_id';";
//		$result123 = $this->db->query($query)->result();
//		
//		$save_type = $result123[0]->type;
//		
//		
//		$query = "UPDATE users SET last_job_type ='$save_type' WHERE id='".$this->loggedInUser->id."';";
//		$this->db->query($query);
		/* Select job description which edited by Agency */
		$edit_agency_job  = Agency_job_description::where(array('job_id'=>$job_id,'agency_id'=>get_user('id')))->first();
		$this->data['agency_edit_job_and_type']  = ['user_type'=> $job_type,'agency_edit_job'=>$edit_agency_job];
        $this->load->blade('agency.job_detail', $this->data);
    }
	
	function job_type_detail($job_type,$j_id){
//echo($this->loggedInUser);
		$this->data['jobOwner_type'] =$job_type;
		
		if ($job_type=="any")
		{
			$query = "SELECT jobs.id,jobs.title FROM jobs WHERE jobs.id IN (SELECT accepted_jobs.job_id FROM accepted_jobs WHERE accepted_jobs.user_id = '".$this->loggedInUser->id."' AND estatus='1') AND jobs.closed='0' AND jobs.user_id IN (SELECT users.id FROM users WHERE users.type='agency' OR users.type='employer')";
			$result123 = $this->db->query($query)->result();
		}
        elseif ($job_type=="emp")
		{
			$query = "SELECT jobs.id,jobs.title FROM jobs WHERE jobs.id IN (SELECT accepted_jobs.job_id FROM accepted_jobs WHERE accepted_jobs.user_id = '".$this->loggedInUser->id."' AND estatus='1') AND jobs.closed='0' AND jobs.user_id IN (SELECT users.id FROM users WHERE users.type='employer')";
			$result123 = $this->db->query($query)->result();
		}
		elseif ($job_type=="agn")
		{
			$query = "SELECT jobs.id,jobs.title FROM jobs WHERE jobs.id IN (SELECT accepted_jobs.job_id FROM accepted_jobs WHERE accepted_jobs.user_id = '".$this->loggedInUser->id."' AND estatus='1') AND jobs.closed='0' AND jobs.user_id IN (SELECT users.id FROM users WHERE users.type='agency')";
			$result123 = $this->db->query($query)->result();
		}
		else
		{
			$result123=[];
		}
		
		if(count($result123)>0)
		{
			echo '<option value="new000">Select A TalentGram</option>';
			foreach($result123 as $row)
			{
				//print_r($result123);
				if ($row->id==$j_id)
				{
					echo '<option value="'.$row->id.'" selected >'.$row->title.'</option>';
				}
				else
				{
					echo '<option value="'.$row->id.'">'.$row->title.'</option>';
				}
				
			}
		}
		else
		{
			echo '<option value="new000">None</option>';
		}
    }
	
	
	function job_type_detail_closed($job_type,$j_id){
//echo($this->loggedInUser);
		$this->data['jobOwner_type_closed'] =$job_type;

		if ($job_type=="any")
		{
			$query = "SELECT jobs.id,jobs.title FROM jobs WHERE ((jobs.closed='1' AND jobs.id IN (SELECT accepted_jobs.job_id FROM accepted_jobs WHERE accepted_jobs.user_id = '".$this->loggedInUser->id."')) OR (jobs.closed='0' AND jobs.id IN (SELECT accepted_jobs.job_id FROM accepted_jobs WHERE accepted_jobs.user_id = '".$this->loggedInUser->id."' AND estatus<'0'))) AND jobs.user_id IN (SELECT users.id FROM users WHERE users.type='agency' OR users.type='employer')";
			$result123 = $this->db->query($query)->result();
		}
        elseif ($job_type=="emp")
		{
			$query = "SELECT jobs.id,jobs.title FROM jobs WHERE ((jobs.closed='1' AND jobs.id IN (SELECT accepted_jobs.job_id FROM accepted_jobs WHERE accepted_jobs.user_id = '".$this->loggedInUser->id."')) OR (jobs.closed='0' AND jobs.id IN (SELECT accepted_jobs.job_id FROM accepted_jobs WHERE accepted_jobs.user_id = '".$this->loggedInUser->id."' AND estatus<'0'))) AND jobs.user_id IN (SELECT users.id FROM users WHERE users.type='employer')";
			$result123 = $this->db->query($query)->result();
		}
		elseif ($job_type=="agn")
		{
			$query = "SELECT jobs.id,jobs.title FROM jobs WHERE ((jobs.closed='1' AND jobs.id IN (SELECT accepted_jobs.job_id FROM accepted_jobs WHERE accepted_jobs.user_id = '".$this->loggedInUser->id."')) OR (jobs.closed='0' AND jobs.id IN (SELECT accepted_jobs.job_id FROM accepted_jobs WHERE accepted_jobs.user_id = '".$this->loggedInUser->id."' AND estatus<'0'))) AND jobs.user_id IN (SELECT users.id FROM users WHERE users.type='agency')";
			$result123 = $this->db->query($query)->result();
		}
		else
		{
			$result123=[];
		}
		
		if(count($result123)>0)
		{
			echo '<option value="new000">Select A TalentGram</option>';
			foreach($result123 as $row)
			{
				//print_r($result123);
				if ($row->id==$j_id)
				{
					echo '<option value="'.$row->id.'" selected >'.$row->title.'</option>';
				}
				else
				{
					echo '<option value="'.$row->id.'">'.$row->title.'</option>';
				}
			}
		}
		else
		{
			echo '<option value="new000">None</option>';
		}
    }
	

    function close_talentgrams($job_id, $onlyHire = 0){
        //commenting code
        $this->data['subscription'] = check_subscription();

        $this->data['job'] = Job::find($job_id)->load('user');
        $this->data['candidates'] = $this->data['job']->candidates()->where('user_id', get_user('id'))->orderBy('client_accepted','desc')->get();
        $this->data['accepted_by_loggedIn_user'] = $this->loggedInUser->accepted_jobs()->where(['job_id' => $job_id])->count();
        foreach ($this->data['candidates'] as $key => $candidateVal){
            $hasHireDetails = Candidate::find($candidateVal->id)->hire_details()->where(['type'=>'Request Payment'])->count();
            $this->data['candidates'][$key]['hasHireDetails'] = $hasHireDetails;
        }
//      
        $this->data['agencies'] = $this->data['job']->accepted_by_agencies()->with('user_profile')->get();
        //$this->data['accepted_agencies'] = $this->data['job']->accepted_by_agency()->get()->toArray();
        $user = User::find(get_user('id'));
        $this->data['agencies_ratings'] = $user->ratings()->lists('rating','agency_id');
        $this->data['favourite_agencies'] = $user->favourite_agencies()->lists('agency_id');
        $this->data['candidates_list'] = $this->data['job']->candidates()->where("client_accepted",1)->lists("name", 'id');
        $this->data['page_name'] = ($onlyHire) ? 'Hire Candidates' : 'Close Job';
        $this->load->blade('agency/close_talentgrams',$this->data);
    }

    function close($id,$val)
    {
        /*$job = Job::find($id);
		
        $job->closed = 1;
		
        $job->save();*/
		
		$user = Accepted_job::where('job_id','=',$id)->where('user_id','=',get_user('id'))->first();
		$user->estatus = $val;
		$user->save();
		
        $this->set_flashMsg('success', 'This TalentGram is now archived.');
        redirect(base_url('searches/job_detail/'.$id.'/new000/any'));
    }
function close_dash($id,$val)
    {
		$user = Accepted_job::where('job_id','=',$id)->where('user_id','=',get_user('id'))->first();
		$user->estatus = $val;
		$user->save();
		
	//below section is commented regarding RP-738 this was ent to update the last viewed job type (Agency type or Employer type)	
	//this feature needs to be disabled since it affect the requirements of RP-738	
//		$query = "SELECT users.type FROM users, jobs WHERE users.id=jobs.user_id AND jobs.id = '$id';";
//		$result123 = $this->db->query($query)->result();
//		
//		$save_type = $result123[0]->type;
//		
//		
//		$query = "UPDATE users SET last_job_type ='$save_type' WHERE id='".get_user('id')."';";
//		$this->db->query($query);
		
		
		
		$result['status'] = 1;
        $result['msg'] = "TalentGram Archived";
        echo json_encode($result); die();
    }
	function  upload_attachment($job_id,$c_id,$type1,$type2){
		$uploadData = upload_file('resume', ['upload_path' => APPPATH.'../public/uploads/docs/', 'allowed_types' => 'pdf|xsl|doc|docx' ]);
		$candidateDetails = $this->input->post('candidates');
		if($this->validateFields() && $uploadData['success'])
		{
			$candidateDetails['title']     = $uploadData['upload']->file_name;
            $candidateDetails['file_path'] = $uploadData['upload']->upload_path;
            $candidateDetails['candidate_id'] = $c_id;
			$candidateDetails['created_at'] = date('Y-m-d H:i:s');
			$candidateDetails['updated_at'] = date('Y-m-d H:i:s');
            $candidate = Candidate_documents::create($candidateDetails);
			/*$candidate = Candidate::find($c_id);
			$candidate->resume = $uploadData['upload']->file_name;
			$candidate->save();*/
			$this->set_flashMsg('success','Your attachment is successfully uploaded');  
		}
		else
		{
			$this->set_flashMsg('error','File type uploaded is not allowed');
		}
		redirect(base_url('searches/candidate_detail/'.$job_id.'/'.$c_id.'/'.$type1.'/'.$type2));
    }

    /**
     * Job Owner Details
     *
     * @param  int $job_id, int $user_id
     * @return  array
     */
    function job_owner_details($job_id, $user_id){
        $this->data['user'] = User::find($user_id);
        $this->data['usertype'] = $this->data['user']->type;
        $this->data['user']->load('user_profile');
        $this->data['job'] = Job::find($job_id)->load('user');
        $this->data['candidates'] = $this->data['job']->candidates()->where('user_id', get_user('id'))->orderBy('client_accepted','desc')->get();
        $userFavAgenciesList = User::find(get_user('id'))->favourite_agencies()->lists('id');
        $this->data['isFavourite'] = in_array($user_id, $userFavAgenciesList);
        $this->data['hasAcceptedCandidate'] = $this->data['job']->candidates()->where(['user_id' => get_user('id'), 'client_accepted' => 1 ])->count();
        $this->data['subscription']['dstatus'] = 'active';
        $this->data['subscription']['type'] = '';
        $this->data['subscription']['msg'] = ''; 
        //p($this->data['hasAcceptedCandidate']);
        $this->load->blade('agency.job_owner_details', $this->data);
    }

    /**
     * Add Candidate
     *
     * @param  int $job_id
     * @return  array
     */
    function add_candidate($job_id,$job_type = "any",$job_type2 = "new000"){
		$this->data['jobOwner_type'] =$job_type;
		$this->data['jobOwner_type_closed'] =$job_type2;
		$this->data['My_job_id'] =$job_id;
        $this->data['job'] = Job::find($job_id)->load('user');
        $ques = $this->data['job']->question;                             // RP-805 adding the job candidate interview question to add new candidate's interview notes
        foreach ($this->candidateFields as $k=> &$field){
            if($k==6){
                $field['options'] = State::lists('name', 'id');
            }
            if ($k==8) {
                $field['options'] = array('U.S. Citizen'=>'U.S. Citizen','Permanent Resident'=>'Permanent Resident','Employment Authorization Document'=>'Employment Authorization Document','Work Visa'=>'Work Visa');             // RP-804 Option value for the residency
            }

            if ($k==11) {
                if (!empty($ques)) {
                    $field['value'] = html_entity_decode(strip_tags($ques));        // RP-805 adding the job candidate interview question to add new candidate's interview notes
                }
            }
        }
        $this->data['candidates'] = $this->data['job']->candidates()->where('user_id', get_user('id'))->orderBy('client_accepted','desc')->get();
        $this->data['candidateFields'] = $this->candidateFields;
        $this->data['subscription']['dstatus'] = 'active';
        $this->data['subscription']['type'] = '';
        $this->data['subscription']['msg'] = '';  
        $this->load->blade('agency.add_candidates', $this->data);
    }

    /**
     * Save Candidate
     *
     * @param  array post data
     * @return  array
     */
    function save_candidate(){

        $up_file = $_FILES['resume']['name'];
        $config['upload_path']          = APPPATH.'../public/uploads/docs/';
        $config['allowed_types']        = 'gif|jpg|png|pdf|xls|xlsx|doc|docx';
        $config['max_size']             = 10000;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        $uploadData = upload_file('resume', $config);

        $candidateDetails = $this->input->post('candidates');

        if($this->validateFields() && $uploadData['success']){
            $candidateDetails['user_id'] = get_user('id');
            $candidateDetails['resume']  = $uploadData['upload']->file_name;
            $candidate = Candidate::create($candidateDetails);
			
			//$this->update_candidate_actions($candidate->job_id,"candidates_pending");
			
			// required fix for RP-643 starts
			/*$candidateDetails['title']     = $uploadData['upload']->file_name;
            $candidateDetails['file_path'] = $uploadData['upload']->upload_path;
            $candidateDetails['candidate_id'] = $candidate->id;
			$candidateDetails['created_at'] = date('Y-m-d H:i:s');
			$candidateDetails['updated_at'] = date('Y-m-d H:i:s');
            Candidate_documents::create($candidateDetails);*/
			// required fix for RP-643 ends
			
            if(!empty($candidate->notes))
            {
                Recruiter_notes::create(['feedback'=>$candidate->notes, 'user_id'=>get_user('id'), 'candidate_id'=>$candidate->id]);
            }
            
            //Send email to user who created this job
            $job = Job::find($candidateDetails['job_id']);
            $useremail = $job->user()->first()->email;
            $userprofile = User_profile::find(get_user('id'));

            $email_variable['JOBTITLE'] = $job->title;

            $email_data=email_template(14,$email_variable);

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

            //Email the CV to repository
            $email_data_res=email_template(22);

            if(isset($email_data_res)){
                $emailData_res = [
                    'to'        => RESUME_EMAIL,
                    'bcc'       => ADMIN_EMAIL,
                    'from'      => WEBSITE_FROM_EMAIL,
                    'subject'   => $email_data_res['template_subject'],
                    'resume'    => realpath($config['upload_path'].$candidateDetails['resume']),
                ];
                $emailData_res['body'] = $email_data_res['template_body'];
                email_resume($emailData_res);
            }

            //Old Message
//            $emailData['body'] =  ucwords($userprofile->company_name)." has submitted a candidate for your open position: ".$job->title.".<br><br>";



            $this->set_flashMsg('success','This candidate has been submitted to the TalentGram for consideration.');
            redirect(base_url('searches/candidate_detail/'.$candidateDetails['job_id'].'/'.$candidate->id.'/any/any'));
        }else{
            $this->set_flashMsg('error','File type uploaded is not allowed');
            redirect(base_url('searches/add_candidate/'.$candidateDetails['job_id'].'/any/new000'));
        }
    }

	/**
     * Candidate Detail
     *
     * @param  int $job_id, int $candidate_id
     * @return  array
     */
	function update_candidate_employment_history($job_id, $candidate_id,$jb_type1= "any",$jb_type2= "new000"){
		$empmnt_history = $this->input->post('candidates-employment_history');
		$query = "UPDATE candidates SET employment_history ='$empmnt_history' WHERE id='".$candidate_id."';";
		$this->db->query($query);
        redirect(base_url('searches/candidate_detail/'.$job_id.'/'.$candidate_id.'/'.$jb_type1.'/'.$jb_type2));
    }
	
    /**
     * Candidate Detail
     *
     * @param  int $job_id, int $candidate_id
     * @return  array
     */
    function candidate_detail($job_id, $candidate_id,$jb_type1= "any",$jb_type2= "new000"){
		
		$this->data['jobOwner_type'] =$jb_type1;
		$this->data['jobOwner_type_closed'] =$jb_type2;
		$this->data['My_job_id'] =$job_id;
		//$this->data['candidatesdocuments']  = Candidate_documents::where(['candidate_id' => $candidate_id])->get();
        $this->data['subscription'] = check_subscription();
        if($candidate_id == null || !is_numeric($candidate_id))
            redirect(base_url('jobs'));
        $this->data['job'] = Job::find($job_id);
        $this->data['candidates'] = $this->data['job']->candidates()->where('user_id', get_user('id'))->orderBy('updated_at','desc')->get();
		$this->data['candidate'] = Candidate::with('hire_details')->find($candidate_id);
        $this->data['hasHireDetails'] = $this->data['candidate']->hire_details()->where('type','=','Request Payment')->where('hire_cancelled','!=',1)->count();
        $this->data['candidateuploadFields'] = $this->candidateuploadFields;
        $this->data['conversation_data'] = Candidate_mail_messaging::where('candidate_id','=',$candidate_id)->where('job_id','=',$job_id)->orderby('id','DESC')->get();     // getting all record for the candidate messaging system for RP-801
//        p($this->data['hasHireDetails']);
        foreach ($this->data['candidates'] as $key => $candidateVal){
            $hasHireDetails = Candidate::find($candidateVal->id)->hire_details()->where('type','=','Request Payment')->where('hire_cancelled','!=',1)->count();
            $this->data['candidates'][$key]['hasHireDetails'] = $hasHireDetails;
        }

        /*Reject Reason options list*/
        $this->data['reject_reasons'] = My_reject_reasons::withTrashed()->orderBy('reject_option')->get();
        
        $this->load->blade('agency/view_candidate_detail',$this->data);
    }

    /**
     * Add Note
     *
     * @param  int $candidate_id
     * @return  array
     */
    function add_note($candidateID){
        $note = Recruiter_notes::create(['feedback'=>$this->input->post('recruiter_note'), 'user_id'=>get_user('id'), 'candidate_id'=>$candidateID]);
        $note->load('added_by');
        $result['status'] = 1;
        $result['msg'] = $this->load->blade('partials._note',compact('note'),true);

        

        //Sending email to Agency who added the job
        $candidate = Candidate::find($candidateID);
        $jobuserid = $candidate->applied_job()->first()->user_id;

        //If the job owner sends the message
        if($jobuserid == get_user('id'))
        {
            $user = User::find($jobuserid);
        }
        else
        {
            $user = User::find($candidate->user_id);
        }

        /*'subject'   => ucwords(get_user('first_name'))." ".ucwords(get_user('last_name'))." has added interview feedback";*/
    
        $job_id = $candidate->job_id;
        $candidate_name = $candidate->name;
        $job_details = Job::find($job_id);
        $job_title = $job_details->title;

        $email_variable['JOBTITLE'] =  $job_title;
        $email_variable['CANDIDATENAME'] =  $candidate_name;
        $email_variable['RECRUITERNOTE'] =  $this->input->post('recruiter_note');

        $email_data=email_template(15,$email_variable);

        if($email_data){
            $emailData = [
                'to'        => $user->email,
                'bcc'       => ADMIN_EMAIL,
                'from'      => WEBSITE_FROM_EMAIL,
                'subject'   => $email_data['template_subject']
            ];
            $emailData['body'] = $email_data['template_body'];
            email($emailData);
        }

        //If the job owner sends the message
        if($jobuserid != get_user('id'))
        {
            $receiving_user = User::find($jobuserid);
            $notification_url = 'searches/candidate_detail/'.$job_id .'/'.$candidate->id.'/any/new000';
        }
        else
        {
            $receiving_user = User::find($candidate->user_id);
            $notification_url = 'jobs/candidate_detail/'.$job_id .'/'.$candidate->id.'/any/new000';
        }


        // Set the notification
        $UserProfile = User_profile::find(get_user('id'));

        $user_notification = array(
                    "notification_text" => 'Interview Notes have been added by '. $UserProfile->company_name,
                    "notification_url" => $notification_url,
                    "user_id" => $receiving_user->id,
                    "status" => 0,
                    "cn_status" => 0
                );

        $notificationData = User_notifications::create($user_notification);

        echo json_encode($result); die();
    }
	
// delete resumes

    function delete_candidate_resume($job_id,$c_id,$resume){
        $candidate = Candidate::find($c_id);
        $removefile = unlink('./public/uploads/docs/'.$resume);
        if($removefile)
        {
            $candidate->resume = '';
            $candidate->save();
            $this->set_flashMsg('success','Your attachment is successfully removed'); 
            redirect(base_url('searches/candidate_detail/'.$job_id.'/'.$c_id.'/any/new000'));      
        }
    }
	
    function delete_resume($job_id,$c_id,$type1,$type2,$docid){
        $candidate = Candidate::find($c_id);
		$cdocument = Candidate_documents::find($docid);
        $removefile = unlink('./public/uploads/docs/'.$cdocument->title);
        if($removefile)
        {
			$cdocument->delete();
			if($nwecdocument = Candidate_documents::where(['candidate_id' => $c_id])->orderBy("created_at","DESC")->first())
			{
				$candidate->resume = $nwecdocument->title;
			}
			else
			{
				$candidate->resume = '';
			}
            $candidate->save();
            $this->set_flashMsg('success','Your attachment is successfully removed!');     
        }
		else
		{
			$this->set_flashMsg('error','The attachment was not deleted!');
		}
		redirect(base_url('searches/candidate_detail/'.$job_id.'/'.$c_id.'/'.$type1.'/'.$type2));
    }

    /**
     * Remove Interest
     *
     * @param  int $job_id
     * @return  
     */
    function remove_interest($job_id){
        $job = Job::find($job_id);
        $job->accepted_by_agencies()->detach(get_user('id'));
        $this->set_flashMsg('success', 'You have been unsubscribed from this Job');
            redirect(base_url('searches'));
    }

    /**
     * Request Payment
     *
     * @param  int $jobId
     * @return  
     */
    function request_payment($jobId){
        $hireDetails = $this->input->post('hired_candidates');
        $candidate_id=key($hireDetails);
        // Take data to send a mail
        $job = Job::find($jobId)->load('user');
        $candidate = Candidate::find($candidate_id);
        $user_profile = User_profile::find(get_user('id'));
        $user = User::find($job->user_id);
        // Check numbers of records in hire detail table
        $hired_details= Hire_detail::where("candidate_id",$candidate_id)->get()->toArray();
        //Update candidate table for recent modification
        $updated_date=date("Y-m-d H:i:s");
        $query_candidate = "UPDATE candidates SET updated_at='".$updated_date."' WHERE id='".$candidate_id."'";
        $this->db->query($query_candidate);

        foreach ($hireDetails as $detail){
            $detail['added_by'] = get_user('id');
            $detail['type'] = 'Request Payment';
            Hire_detail::create($detail);
        }

        // Send for Confirme a hire details
        //$email_variable['COMPANYNAME'] = ucfirst($user_profile->company_name);
        //$email_variable['CANDIDATENAME'] = ucfirst($candidate->name);
        //$email_variable['JOBTITLE'] = ucfirst($job->title);
        $email_variable['SALARY'] = $this->input->post('hired_candidates')[$candidate_id]['base_salary'];
        $email_variable['STARTDATE'] = date("n-j-Y",strtotime($this->input->post('hired_candidates')[$candidate_id]['start_date']));

        if(empty($hired_details)){
            $email_variable['COMPANYNAME'] = ucfirst($user_profile->company_name);
            $email_variable['CANDIDATENAME'] = ucfirst($candidate->name);
            $email_variable['JOBTITLE'] = ucfirst($job->title);
            
            $email_data=email_template(13,$email_variable);
            if(isset($email_data)){
                $mailData = [
                    "to" => $user->email,
                    'bcc' => ADMIN_EMAIL,
                    "from" =>  WEBSITE_FROM_EMAIL,
                    "subject" => $email_data['template_subject'],
                ];
                $mailData['body'] = $email_data['template_body'];
                email($mailData);
            }
        }else{

            $email_data=email_template(17,$email_variable);
            if(isset($email_data)){
                $mailData = [
                    'to' => ADMIN_EMAIL,
                    "from" =>  WEBSITE_FROM_EMAIL,
                    "subject" => $email_data['template_subject'],
                ];
                $mailData['body'] = $email_data['template_body'];
                email($mailData);
            }

            $email_variable['COMPANYNAME'] = ucfirst($user_profile->company_name);
            $email_variable['CANDIDATENAME'] = ucfirst($candidate->name);
            $email_variable['JOBTITLE'] = ucfirst($job->title);

            $email_data13=email_template(13,$email_variable);
            if(isset($email_data13)){
                $mailData13 = [
                    "to" => $user->email,
                    'bcc' => ADMIN_EMAIL,
                    "from" =>  WEBSITE_FROM_EMAIL,
                    "subject" => $email_data13['template_subject'],
                ];
                $mailData13['body'] = $email_data13['template_body'];
                email($mailData13);
            }
        }

		//RP-627
		
		if ($user->type=="agency")
		{
			$displayMsg = Site_messages::where('type','=','candidate_payment_request_agn')->first();
		}
		elseif($user->type=="employer")
		{
			$displayMsg = Site_messages::where('type','=','candidate_payment_request_emp')->first();
		}
		
		
        $this->set_flashMsg('success',$displayMsg->msg);
        redirect(base_url('searches/job_detail/'.$jobId));
    }

    /**
     * Subscription
     *
     * @param  
     * @return  
     */
    public function subscription(){
        $this->load->blade('agency/plans');
    }


    private function isMessageEnable($fromUserId, $toUserId)
    {
        //Get the from user
        $fromUser = User::find($fromUserId);
        $fromUserType = $fromUser->type;

        //Employer should have ability to message any user
        if($fromUserType == 'employer')
        {
            return 1;
        }
        else
        {
            //Get the to user
            $toUser = User::find($toUserId);
            $toUserType = $toUser->type;

            //When trying to send message to admin
            if($toUserType == 'admin')
            {
                return 1;
            }//When trying to send message to an agency
            else if($toUserType == 'agency')
            {
                // Check whether the user has rated by to person for less than 3 stars
                $rating_query = "SELECT min(rating) as minrating FROM rating WHERE user_id = '$toUserId' AND agency_id = '$fromUserId';";
                $rating_result = $this->db->query($rating_query)->result();

                if(isset($rating_result) && count($rating_result)> 0)
                {
                    if(!empty($rating_result[0]->minrating) && $rating_result[0]->minrating<3)
                    {
                        return 0;
                    }
                    else
                    {
                        return 1;
                    }
                }
                else
                {
                    return 1;
                }
            }
            //When trying to send message to an employer
            else{
                //Get the previously sent messages count
                $messageCount = Message::with(['from_user'])->where([
                    'from_user_id' =>  $fromUserId,
                    'to_user_id' => $toUserId ])->count();

                //Check whether the user being accepted for any job
                $accepted_query = "SELECT COUNT('accepted_job_id') AS acceptedcount FROM accepted_jobs WHERE user_id = '$fromUserId' AND estatus = 1 AND job_id IN (SELECT id from jobs where user_id = '$toUserId');";
                $accepted_result = $this->db->query($accepted_query)->result();

                $acceptedJobCount = $accepted_result[0]->acceptedcount;

                // Check whether the agency is in the prefered list of the employer
                $preffer_query = "SELECT count(user_id) as prefuser FROM preferred_agencies WHERE user_id = '$toUserId' AND agency_id = '$fromUserId';";
                $preffer_result = $this->db->query($preffer_query)->result();

                $prefCount = $preffer_result[0]->prefuser;

                // If user previously received messages or accepted for a job on in To User's prefered list
                if($messageCount> 0 || $acceptedJobCount> 0 || $prefCount>0)
                {
                    // Check whether the user has rated by to person for less than 3 stars
                    $rating_query = "SELECT min(rating) as minrating FROM rating WHERE user_id = '$toUserId' AND agency_id = '$fromUserId';";
                    $rating_result = $this->db->query($rating_query)->result();

                    if(isset($rating_result) && count($rating_result)> 0)
                    {
                        if(!empty($rating_result[0]->minrating) && $rating_result[0]->minrating<3)
                        {
                            return 0;
                        }
                        else
                        {
                            return 1;
                        }
                    }
                    else
                    {
                        return 1;
                    }
                }
                else
                {
                    return 2;
                }

            }
        }
    }

    function update_job_description_by_agency($job_id,$job_type)
    {
    	$inputdata=$this->input->post();
    	$updated_date=date('Y-m-d H:i:s');
    	if(isset($inputdata['agency_job_description_id']) && $inputdata['agency_job_description_id']!='')
    	{
    		$query_job_des = "UPDATE agency_job_description SET job_description='".$inputdata['agency_job_description']."', updated_at='".$updated_date."' WHERE id='".$inputdata['agency_job_description_id']."'";
        	$this->db->query($query_job_des);
        	redirect(base_url('searches/job_detail/'.$job_id.'/'.$job_type));
    	} else {
    		$insertdata['agency_id']        = get_user('id');
    		$insertdata['job_id']           = $job_id;
    		$insertdata['job_description']  = $inputdata['agency_job_description'];
    		$insertdata['added_by']         = get_user('id');
    		$addeddata = Agency_job_description::create($insertdata);
    		redirect(base_url('searches/job_detail/'.$job_id.'/'.$job_type));
    	}
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

}