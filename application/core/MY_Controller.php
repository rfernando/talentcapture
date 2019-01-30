<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Class MY_Controller
 *
 * Using this class to Extend CI_Controller Class
 * All Other Controllers will extend this class
 * instead of extending the CI_Controller Class
 */

class MY_Controller extends CI_Controller{

    /**
     * @var $data
     *
     * This will be a Global Variable for all the Controllers
     */
    protected  $data = [];

    /**
     * @var $user
     *
     * This is the Logged In User
     */
    protected  $user;

    /**
     * @var $admin_user
     *
     * This is the Logged In Admin User
     */
    protected $admin_user;

    /**
     * @var bool
     *
     * To make a Class methods accessible only when user is
     * loggedIn Set this Variable to TRUE
     */
    protected  $authUserRequired = FALSE;


    /**
     * @var bool
     *
     * To make a Class methods accessible only when Admin user is
     * loggedIn Set this Variable to TRUE
     */
    protected  $adminUserRequired = FALSE;

    /**
     * MY_Controller constructor.
     *
     * Check if user is authenticated to view a Page
     */
    public function __construct(){

        parent::__construct();
        $this->user = get_user();

        $this->admin_user = get_admin_user();

        // Check Authorised user Access
        if($this->authUserRequired == TRUE && !isset($this->user['id'])){
            $this->set_flashMsg('danger','Please Login to view this Page');
            redirect('login');
        }elseif($this->authUserRequired == TRUE && isset($this->user['id'])){
            $this->data['newMessages'] = $this->newMessages();
            //p($this->data['newMessages']['messages']->toArray());
        }

        // Check Admin user Access
        if($this->adminUserRequired == TRUE && !isset($this->admin_user['type']) && $this->admin_user['type'] == 'admin'){
            $this->set_flashMsg('danger','You are not Authorised to access this Page');
            redirect('login');
        }

    }

    // TODO : Make a Destructor to automatically load view for child Methods
    /*public function __destruct() {
        $className = get_called_class();
        $methodName = $this->getMethods();
        $this->load->view($className.'/'.$methodName);
    }*/

    /**
     * validateFields
     * @return bool
     *
     * Handle Server Side Validation
     */
    protected function validateFields(){
        return true;    // TODO : Add Common Validation Logic
    }

    /**
     * set_flashMsg
     * @param $type
     * @param $msg
     *
     * Set Flash Messages to be displayed on the next Page.
     */
    protected function set_flashMsg($type, $msg){
        $html = '<div class="alert alert-'.$type.'"><strong>';
//        $html .= ($type=='danger') ? 'Error' : ucfirst($type);
        $html .= '</strong>'.$msg.'</div>';
        $this->session->set_flashdata('msg',$html);
    }

    /**
     * get_profile_percentage
     *
     * Returns the Percentage of Profile Completeion of the User.
     */
    protected function get_profile_percentage() {
        $requiredFields = ['city', 'state_id', 'zipcode','linkedin_url','company_desc','company_name','company_website_url', 'role'];
        $totalFields = count($requiredFields);
        $user_profile = User_profile::find($this->user['id'])->toArray();
        $emptyFields = 0;
        foreach ($requiredFields as $v){
            if($user_profile[$v] == '')
                $emptyFields++;
        }
        return (($totalFields - $emptyFields) * 100)/$totalFields;
    }

    /**
     * check_profile_updated
     *
     * This will check if the user ahs updated his Profile
     */
    protected function check_profile_updated() {
        return (User::find($this->user['id'])->user_profile != NULL) ? true : false;
    }

    protected function buildTree(array $elements, $parentId = 0) {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }


    protected function generateOptions($array){
        $return = array();

        foreach ($array as $a){
            if($a->parent_id == NULL){
                $return[$a->title] = array();
            }else{
                $return[$a->parent()->first()->title][$a->id] = $a->title;
            }
        }
        return $return;
    }

    public function getProfessionOptions($industries  = NULL, $return = false){
        $industries = ($industries == NULL) ? $this->input->post('industries') : $industries;
        $professions  = $this->industriesDD($industries);
        //echo '<pre>'; print_r($professions); exit;
        foreach($professions as $subArray){
            foreach($subArray as $key => $val){
                if($return)
                    $newArray[$key] = $val;
                else
                    $newArray[$val] = ["id" => $key, "text"=>$val];
            }
        }
        if($return) {
            return $newArray;
        } else {
            array_unique($newArray, SORT_REGULAR);
            ksort($newArray);
            echo  json_encode($newArray,true);
        }

    }


    private function industriesDD($industries){
        $professions  = [];
        foreach ($industries as $i){
            $industry = Industry::find($i);
            $professions[$industry->title] = $industry->professions()->orderBy('title','asc')->lists('title','id');
        }
        return $professions;
    }

    /**
     * Returns the id of jobs that have already been accepted by 5 agencies
     *
     * @return array
     */
    protected function getJobsAcceptedByMax(){
        $jobsAcceptedByLimitReached = $this->db->select('job_id')->from('accepted_jobs')->where('estatus != 2')->group_by('job_id')->having('COUNT(user_id) >= 5')->get()->result_array();
        $returnArr = [];
        foreach ($jobsAcceptedByLimitReached as $content){
            if(isset($content['job_id']))
            {
                $job_id = $content['job_id'];

                $job = Job::find($job_id);

                if(isset($job) && $job->add_agency == 0)
                {
                    $returnArr[] =  $content['job_id'];
                }
            }
        }
        return $returnArr;
    }

    /**
     * Returns the new Messages for any user
     *
     * @return null
     */
    protected function newMessages(){
        $returnArr['messages'] =  Message::where('to_user_id', $this->user['id'])
            ->whereIn('id', function($q){
                $q->from('messages')
                    ->selectRaw('MAX(id)')
                    ->groupBy('from_user_id');
            })->orderBy('created_at', 'desc')->limit(5)->with('from_user')->get();

        $returnArr['all_messages'] =  Message::where('to_user_id', $this->user['id'])->where('from_user_id', '<>', $this->user['id'])->orderBy('created_at', 'desc')->limit(15)->with('from_user')->get();
        $returnArr['unread_all_messages']=Message::where(['to_user_id'=>$this->user['id'],'viewed'=>'0'])->where('from_user_id', '<>', $this->user['id'])->with('from_user')->get();

        $returnArr['unread'] = 0;
        foreach ($returnArr['messages'] as $msg){
            if(!$msg->viewed){
                $returnArr['unread']++;
            }
        }
        return $returnArr;
    }


    function get_jobs_posted_count($userId){
        return Job::withTrashed()->where('user_id', $userId)->count();
    }

    function get_jobs_closed_count($userId){
        return Job::withTrashed()->where(['user_id' => $userId, 'closed' => 1])->count();
    }

    private function get_jobs_list($userId){
        return Job::withTrashed()->where('user_id', $userId)->lists('id');
    }
	

    function get_candidates_accepted_count($userId){
        $userJobsList = $this->get_jobs_list($userId);
        return (count($userJobsList)) ? Candidate::withTrashed()->whereIn('job_id', $userJobsList)->where('client_accepted', 1)->count() : 0;
    }

    function get_candidates_declined_count($userId){
        $userJobsList = $this->get_jobs_list($userId);
        return (count($userJobsList)) ? Candidate::withTrashed()->whereIn('job_id', $userJobsList)->where('client_accepted', -1)->count() : 0;
    }
	
	

    function get_candidates_hired_count($userId){
        $userJobsList = $this->get_jobs_list($userId);
        return (count($userJobsList)) ? Candidate::withTrashed()->whereIn('job_id', $userJobsList)->where('hired', 1)->count() : 0;
    }

	function get_agencies_accepted_count($userId){
        $userJobsList = $this->get_jobs_list($userId);
		return (count($userJobsList)) ? Accepted_job::whereIn('job_id', $userJobsList)->count() : 0;
    }
	function get_agencies_declined_count($userId){
        $userJobsList = $this->get_jobs_list($userId);
		return (count($userJobsList)) ? Rejected_job::whereIn('job_id', $userJobsList)->count() : 0;
    }
	
    function get_so_accepted_count($userId){
        return User::find($userId)->accepted_jobs()->count();
    }


    function get_so_rejected_count($userId){
        return User::find($userId)->rejected_jobs()->count();
    }

    function get_so_candidates_accepted_count($userId){
        return  Candidate::withTrashed()->where('user_id',$userId)->where('client_accepted', 1)->count();
    }

    function get_so_candidates_declined_count($userId){
        return Candidate::withTrashed()->where('user_id',$userId)->where('client_accepted', -1)->count();
    }

    function get_so_candidates_hired_count($userId){
        return Candidate::where(['user_id'=>$userId, 'hired' => 1 ])->count();
    }
	
	function get_any_field_single_row($tbl=NULL,$col=NULL,$cond_fld=NULL,$cond_val=NULL){
			$tbl = ($tbl == NULL) ? $this->input->post('tbl') : $tbl;
			$col = ($col == NULL) ? $this->input->post('col') : $col;
			$cond_fld = ($cond_fld == NULL) ? $this->input->post('cond_fld') : $cond_fld;
			$cond_val = ($cond_val == NULL) ? $this->input->post('cond_val') : $cond_val;
			if ($cond_val!="")
			{
				$reslt = $tbl::where([$cond_fld=>$cond_val])->lists($col);
				echo  json_encode($reslt[0],true);
			}
			else
			{
				echo  json_encode("",true);
			}
    }

}
