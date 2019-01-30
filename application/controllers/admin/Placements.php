<?php

class placements extends MY_Controller{

    function __construct() {
        $this->adminUserRequired = TRUE;
        parent::__construct();
    }

    function index(){
		$query = "SELECT au.id, CONCAT(au.first_name, au.last_name,' <SMALL>@ ', ap.company_name,'</SMALL>') AS agencyName FROM  users AS au, user_profiles AS ap WHERE au.id=ap.user_id AND au.type='agency';";
		
		$this->data['agencies'] = $this->db->query($query)->result();
		
		
        $this->load->blade('admin.placements.index', $this->data);
    }
	
	
	function load_talentg($uid)
	{
		$this->data['agency_usr']=$uid;
		
		$query = "SELECT au.id, CONCAT(au.first_name, au.last_name,' <SMALL>@ ', ap.company_name,'</SMALL>') AS agencyName FROM  users AS au, user_profiles AS ap WHERE au.id=ap.user_id AND au.type='agency';";
		
		$this->data['agencies'] = $this->db->query($query)->result();
		
		
		$query = "SELECT DISTINCT(j.id), j.title,h.hire_cancelled FROM hire_details AS h, candidates AS c, users AS u, jobs AS j WHERE h.candidate_id=c.id AND c.user_id='$uid' AND h.added_by=u.id AND c.job_id=j.id AND h.type='Hire' AND h.id IN (SELECT MAX(id) FROM hire_details WHERE type='Hire' GROUP BY candidate_id);";
		
		$this->data['agency_tlntg_info'] = $this->db->query($query)->result();
		
        $this->load->blade('admin.placements.index', $this->data);
		
		
	}
	
	function load_talentg_dtls($jid,$uid)
	{
		$this->data['agency_tlntg']=$jid;
		$this->data['agency_usr']=$uid;
		
		$query = "SELECT DISTINCT(j.id), j.title FROM hire_details AS h, candidates AS c, users AS u, jobs AS j WHERE h.candidate_id=c.id AND c.user_id='$uid' AND h.added_by=u.id AND c.job_id=j.id AND h.type='Hire' AND h.id IN (SELECT MAX(id) FROM hire_details WHERE type='Hire' GROUP BY candidate_id);";
		
		$this->data['agency_tlntg_info'] = $this->db->query($query)->result();
		
		$query = "SELECT au.id, CONCAT(au.first_name, au.last_name,' <SMALL>@ ', ap.company_name,'</SMALL>') AS agencyName FROM  users AS au, user_profiles AS ap WHERE au.id=ap.user_id AND au.type='agency';";
		
		$this->data['agencies'] = $this->db->query($query)->result();
		
		
		
		$query="SELECT c.id, c.name, j.title, h.start_date, h.base_salary, j.warranty_period, j.placement_fee, j.split_percentage, u.type,j.id AS jobId ,h.hire_cancelled, h.id AS hire_id FROM hire_details AS h, candidates AS c, users AS u, jobs AS j WHERE h.candidate_id=c.id AND c.user_id='$uid' AND c.job_id=j.id AND j.user_id=u.id AND h.type='Hire' AND h.id IN (SELECT MAX(id) FROM hire_details  WHERE type='Hire' GROUP BY candidate_id) AND j.id='$jid';";
		$this->data['agency_tlntg_dtls_info'] = $this->db->query($query)->result();
		
		
        $this->load->blade('admin.placements.index', $this->data);
		
		
	}	
	
    function change_status($id= NULL,$id2= NULL){
        if($id == NULL ||$id2 == NULL || !is_numeric($id)|| !is_numeric($id2)) 
		{
			redirect(admin_url('reviews/index'));
		}

		$query = "SELECT * FROM rating WHERE user_id='$id' AND agency_id='$id2';";
		$results=$this->db->query($query)->result();
           
		if($results[0]->rat_status == 1)
		{
			$stat =0;
            
		}
		else
		{
			$stat=1;
		}
		$query = "UPDATE rating SET rat_status='$stat' WHERE user_id='$id' AND agency_id='$id2';";
		$this->db->query($query);
		$response = ['status'=>1];
        echo json_encode($response); die();
    }

    function cancel_reported_hire($id, $candidate_id,$jid,$uid){

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


        $email_variable['CANCELLEDUSER'] = 'Admin user';
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

        //Sending the email for the agency recruiter
        if(isset($email_data)){
            $emailDataAgency = [
                'to'        => $agency_user->email,
                'from'      => WEBSITE_FROM_EMAIL,
                'subject'   => $email_data['template_subject']
            ];

            $emailDataAgency['body'] = $email_data['template_body'];
            email($emailDataAgency);
        } 


        // Set the notification
        $user_notification = array(
                    "notification_text" => 'Admin user Cancelled Hire Details for '.$candidate->name,
                    "notification_url" => 'jobs/view_detail/'.$job->id,
                    "user_id" => $job->user_id,
                    "status" => 0,
                    "cn_status" => 0
                );

        $notificationData = User_notifications::create($user_notification);


        $user_notification_agency = array(
                    "notification_text" => 'Admin user Cancelled Hire Details for '.$candidate->name,
                    "notification_url" => 'searches/job_detail/'.$job->id.'/any/new000',
                    "user_id" => $candidate->user_id,
                    "status" => 0,
                    "cn_status" => 0
                );
        $notificationData = User_notifications::create($user_notification);

        $this->set_flashMsg('success','Placement details updated successfully.');
        redirect(admin_url('placements/load_talentg_dtls/'.$jid.'/'.$uid));
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