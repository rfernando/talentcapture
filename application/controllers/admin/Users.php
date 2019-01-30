<?php

class Users extends MY_Controller{

    function __construct() {
        $this->adminUserRequired = TRUE;
        parent::__construct();
    }

    function index() {
        $this->data['users'] = User::withTrashed()->where('type','!=','admin')->orderBy('id','desc')->get();
        $this->load->blade('admin.users.index', $this->data);
    }

    function view($id = NULL){
        if($id == NULL || !is_numeric($id)) redirect(admin_url('users/index'));
        $this->data['user'] = User::find($id);

		$stats_job = ['jobs_posted', 'jobs_closed', 'candidates_accepted', 'candidates_declined', 'candidates_hired','agencies_accepted','agencies_declined'];
        $stats_so=array();
        if($this->data['user']->type == 'agency')
            $stats_so = ['so_accepted','so_rejected','so_candidates_accepted', 'so_candidates_declined', 'so_candidates_hired'];

        foreach ($stats_job as $stat){
            $methodName =  "get_$stat".'_count';
            $this->data['stats_job'][$stat] = $this->$methodName($this->data['user']->id);
        }
        if(isset($stats_so)){
            foreach ($stats_so as $stat){
                $methodName =  "get_$stat".'_count';
                $this->data['stats_so'][$stat] = $this->$methodName($this->data['user']->id);
            }
        }

        $stats = ['jobs_posted', 'jobs_closed', 'candidates_accepted', 'candidates_declined', 'candidates_hired'];
        if($this->data['user']->type == 'agency')
            $stats = array_merge($stats, ['so_accepted', 'so_rejected', 'so_candidates_hired']);
        foreach ($stats as $stat){
            $methodName =  "get_$stat".'_count';
            $this->data['stats'][$stat] = $this->$methodName($this->data['user']->id);
        }
        $this->data['user_profile'] = $this->data['user']->user_profile()->first();
		$today_dt=date('Y-m-d');
		$query="SELECT c.name, j.title, h.start_date, h.base_salary, j.warranty_period, j.placement_fee, j.split_percentage FROM hire_details AS h, candidates AS c, users AS u, jobs AS j WHERE h.candidate_id=c.id AND c.user_id='$id' AND u.type='agency' AND c.job_id=j.id AND j.user_id=u.id AND h.type='Hire' AND h.id IN (SELECT MAX(id) FROM hire_details GROUP BY candidate_id) AND h.start_date<'$today_dt';";
		$this->data['agency_placement_info'] = $this->db->query($query)->result();
		$query="SELECT c.name, j.title, h.start_date, h.base_salary, j.warranty_period, j.placement_fee, j.split_percentage FROM hire_details AS h, candidates AS c, users AS u, jobs AS j WHERE h.candidate_id=c.id AND c.user_id='$id' AND u.type='employer' AND c.job_id=j.id  AND j.user_id=u.id AND h.type='Hire' AND h.id IN (SELECT MAX(id) FROM hire_details GROUP BY candidate_id) AND h.start_date<'$today_dt';";
		$this->data['employer_placement_info'] = $this->db->query($query)->result();
		
        $this->load->blade('admin.users.view', $this->data);
    }

    function change_status($id= NULL){
        if($id == NULL || !is_numeric($id)) redirect(admin_url('users/index'));
        $user = User::find($id);
        $user->status = !$user->status;

        //Send email to agency/employer on approval from admin only
        if($user->status){

            if($user->type == "agency"){
                $email_data=email_template(11);

            }else{
                $email_data=email_template(12);
            }

            if(isset($email_data)){
                $useremail = $user->email;
                $emailData = [
                    'to' => $useremail,
                    'bcc' => ADMIN_EMAIL,
                    'from' => WEBSITE_FROM_EMAIL,
                    'subject' => $email_data['template_subject']
                ];
                $emailData['body'] = $email_data['template_body'];
                email($emailData);
            }

            // Add customer support message
            if($user->status == 1)
            {
                $welcomeMessageData =  array("from_user_id"=> 1, "to_user_id" => $id, "text" => 'Welcome to TalentCapture  support', "viewed" => 0);
                $welcomeMessage = Message::create($welcomeMessageData);

                //Create a zoom meeting account
                //createZoomUsers($id);
            }
        }
        $user->save();echo ($user->status) ? 1 : 0;
    }

    function change_type($id= NULL){
        if($id == NULL || !is_numeric($id)) redirect(admin_url('users/index'));
        $user = User::find($id);
        $user->type = ($user->type == 'employer') ? 'agency' : 'employer';
        $user->save();echo $user->type;
    }

    function delete($data = NULL){
        if($data == NULL )
            $data = $this->input->post('selectedRows');
        User::destroy($data);
        $this->set_flashMsg('success','The User(s) have been successfully Deleted');
        redirect(admin_url('users'));
    }


    function restore($data = NULL){
        if($data == NULL )
            $data = $this->input->post('selectedRows');
        User::withTrashed()->where('id', $data)->restore();
        $this->set_flashMsg('success','The User has been successfully Restored');
        redirect(admin_url('users'));
    }

}