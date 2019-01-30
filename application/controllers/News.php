<?php

class News extends MY_Controller{

    private $candidateApplyFields = [
        [
            'name' => 'candidates_apply[name]',
            'type' => 'text',
            'placeholder' => 'Name',
            'validation' => 'required'
        ],
        [
            'name' => 'candidates_apply[email]',
            'type' => 'text',
            'placeholder' => 'Email',
            'validation' => 'valid_email|required'
        ],
        [
            'name' => 'candidates_apply[phone]',
            'type' => 'text',
            'placeholder' => 'Phone',
            'validation' => 'trim'
        ],
        [
            'name' => 'candidates_apply[linkedin_url]',
            'type' => 'text',
            'placeholder' => 'Linkedin',
            'validation' => 'valid_url|required',
        ],
        [
            'name' => 'resume',
            'type' => 'file',
            'accept' => 'application/pdf,application/vnd.ms-excel',
            'placeholder' => 'Upload Resume',
            'validation' => 'trim'
        ],
        [
            'name' => 'candidates_apply[message]',
            'type' => 'text',
            'placeholder' => 'Message',
            'validation' => 'trim'
        ]
    ];

    
    /**
     * Constructor
     *
     * @param       
     * @return      
     */

    function __construct() {
        parent::__construct();
    }

    public function jobdescription($job_id,$shared_user_id = 0,$email_link=''){
        $this->data['job'] = Job::find($job_id)->load('user');
        $this->data['shared_user_id'] = $shared_user_id;
        $this->data['email_link'] = $email_link;  // adding email-link for the RP-790
        /*If this job is edited by $shared_user_id */
        $this->data['edit_agency_job_desc'] = Agency_job_description::where(array('job_id'=>$job_id,'agency_id'=>$shared_user_id))->first();
        $job = Job::find($job_id);
		$this->config->set_item('pageTitle', $job->title);
        $this->load->blade('agency.jobdescription', $this->data);
    }

    public function job_application($job_id,$shared_user_id = 0){
        
        $this->data['job'] = Job::find($job_id)->load('user');
        $this->data['candidateApplyFields'] = $this->candidateApplyFields ;
        $this->data['shared_user_id'] = $shared_user_id;
        $this->load->blade('agency.apply_by_candidates', $this->data);
    }

    public function email_job_appication($job_id,$shared_user_id = 0){

        $up_file = $_FILES['resume']['name'];
        $config['upload_path']          = APPPATH.'../public/uploads/docs/';
        $config['allowed_types']        = 'gif|jpg|png|pdf|xls|xlsx|doc|docx';
        $config['max_size']             = 10000;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;

        if(isset($up_file)){
            $uploadData = upload_file('resume', $config);
        }

        $candidateAppDetails = $this->input->post('candidates_apply');
        $candidateAppDetails['phone'] = implode('',explode('-',implode('',explode('+',$candidateAppDetails['phone']))));


        $shareduser = User::find($shared_user_id);

        if($this->validateFields() && ($up_file == '' || $uploadData['success'])){

            $email_variable['TALENTGRAMNAME'] = Job::find($job_id)->title;
            $email_variable['CANDIDATENAME'] = $candidateAppDetails['name'];
            $email_variable['CANDIDATEEMAIL'] = $candidateAppDetails['email'];
            $email_variable['CANDIDATEPHONE'] = $candidateAppDetails['phone'];
            $email_variable['CANDIDATELINKEDIN'] = $candidateAppDetails['linkedin_url'];
            $email_variable['CANDIDATEMESSAGE'] = $candidateAppDetails['message'];;

            $email_data_res=email_template(25,$email_variable);
            $candidateAppDetails['resume']  = $uploadData['upload']->file_name;

            if(isset($email_data_res)){
                $emailData_res = [
                    'to'        => $shareduser->email,
                    'bcc'       => ADMIN_EMAIL,
                    'from'      => WEBSITE_FROM_EMAIL,
                    'subject'   => $email_data_res['template_subject'],
                    'resume'    => realpath($config['upload_path'].$candidateAppDetails['resume']),
                ];
                $emailData_res['body'] = $email_data_res['template_body'];
                email_resume($emailData_res);
            }

            $this->set_flashMsg('success','You have successfully submitted your application.');
            //echo "<script>alert('Your details are submitted for consideration.');</script>";
            redirect(base_url('news/jobdescription/'.$job_id.'/'.$shared_user_id));
        }else{
            $this->set_flashMsg('error','File type uploaded is not allowed.');
            //echo "<script>alert('File type uploaded is not allowed.');</script>";
            redirect(base_url('news/job_application/'.$job_id.'/'.$shared_user_id));
        }
    }

}