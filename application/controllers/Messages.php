<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Message
 *
 * This class will handle all the Basic Authentication Process
 */
class Messages extends  MY_Controller{

    private $loggedInUserId;


    public function __construct() {
        $this->authUserRequired = TRUE;
        parent::__construct();
        $this->loggedInUserId = get_user('id');
        $this->data['contacts'] = $this->getContacts($this->loggedInUserId);
    }

    public function index() {
        $this->data['toUserID'] = key($this->data['contacts']);
        redirect('messages/chat/'.$this->data['toUserID'].'');
        //$this->load->blade('messages/index',$this->data);
    }

    public function chat($toUserID = NULL, $searchText = '' , $candidateid = ''){
        if(is_null($toUserID))
            redirect('messages');
        Message::where(['from_user_id'=>$toUserID, 'to_user_id' => get_user('id')])->update(['viewed'=>1]);
        $this->data['contacts'] = $this->getContacts($this->loggedInUserId,$searchText);
        if ($this->uri->segment(4) != '') {
           $this->data['candidateid'] = $this->uri->segment(4);
        }else{
            $this->data['candidateid'] = 0;
        }

        if ($this->uri->segment(5) != '') {
           $this->data['job_id'] = $this->uri->segment(5);
        }else{
            $this->data['job_id'] = 0;
        }

        $this->data['messages'] = Message::with(['from_user'])->where([
            'from_user_id' =>  $this->loggedInUserId,
            'to_user_id' => $toUserID ])->orWhere([
            'from_user_id' =>  $toUserID,
            'to_user_id' =>  $this->loggedInUserId])->orderBy('created_at','desc')->get();

        $this->data['toUserID'] = $toUserID;
        $this->data['toUser'] = User::find($toUserID);
        $this->data['type'] = User::find($this->loggedInUserId)->type;
        //print_r(count($this->data['messages']));exit;
        //Check whether the Send buttons needs to be enabled or not
        $isEnable = $this->isSendEnable($this->loggedInUserId,$toUserID);
        $this->data['enableSend'] = $isEnable;
        $this->load->blade('messages/index',$this->data);
    }

    public function send() {
        $msg = $this->input->post();
        $msg['from_user_id'] = get_user('id');
        // if previous message is special then copy this for condidate Feedback
        if(isset($msg['type']) && $msg['type']=='1') 
        {
            $candidate_feedback= Candidate_feedback::create(['feedback'=>$this->input->post('text'), 'user_id'=>get_user('id'), 'candidate_id'=>$this->input->post('candidate_id')]);
        }

        /*if (isset($msg['candidateDisc']) && $msg['candidateDisc']=='1') {
            $candidate_feedback = Candidate_feedback::create(['feedback' =>$this->input->post('text'), 'user_id'=>get_user('id'),'candidate_id'=>$this->input->post('candidate_id')]);
            $msg['type'] = '1';
           
        }
        */

        $msg['job_id'] = $this->input->post('job_id');
        $msg = Message::create($msg);
        $msg->load('from_user');

        $to = User::find($this->input->post('to_user_id'));

        $email_variable['FIRSTNAME'] = ucfirst($msg['from_user']->first_name);
        $email_variable['LASTNAME'] = ucfirst($msg['from_user']->last_name);
        $email_variable['TEXT'] = $msg['text'];

        $email_data=email_template(18,$email_variable);

        if(isset($email_data)){
            $email_data = array(
                'to' => $to['email'],
                //'to' =>'appdev@perituza.com',
                //'from' => $msg['from_user']->email,
                'from' => WEBSITE_FROM_EMAIL,
                'subject' => $email_data['template_subject'],
                'body' => $email_data['template_body']
            );
            email_on_message($email_data);
            $response = $this->load->blade('messages/_msg', ['msg' => $msg], true);
            if ($this->input->is_ajax_request()) {
                echo json_encode($response);
                email_on_message($email_data);
            } else {
                redirect('messages/chat/' . $this->input->post('to_user_id'));
            }
        }
    }

        /*fetching message template for select id*/
    public function get_message_template()
    {
        $id = $this->input->post('id');
        if (!empty($id)) {
            $this->data['template'] = Message_templates::find($id);
             echo json_encode($this->data['template']);
             exit;
        }else{
            echo 1;
            exit;
        }

    }

    private function getContacts($userId,$searchText = ''){
        $messages = Message::with(['to_user','from_user'])->where(['from_user_id' => $userId])->orWhere(['to_user_id' => $userId])->orderBy('id', 'DESC')->get();

        //return $messages;
        $contacts = [];
        if(count($messages)){
            foreach ($messages as $msg){
                $contactID = ($msg->to_user_id == get_user('id')) ? 'from_user_id' : 'to_user_id';
                $unread = (!isset($contacts[$msg->$contactID]['unread'])) ? 0 : $contacts[$msg->$contactID]['unread'];
                $contacts[$msg->$contactID] = $msg->toArray();
                $contacts[$msg->$contactID]['unread'] = $unread;


                if($msg->viewed != 1 && $msg->to_user_id == get_user('id')){
                    $contacts[$msg->$contactID]['unread']++;
                }
            }
        }
        return $contacts;
    }

    private function isSendEnable($fromUserId, $toUserId){
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

    public function search($searchStr) {

        $usersList =  User::leftJoin('user_profiles', function($ljoin){
            $ljoin->on('users.id', '=', 'user_profiles.user_id');
        })->where(function($query) use ($searchStr){
            return $query->orWhere('users.first_name','LIKE',"%$searchStr%")->orWhere('users.last_name','LIKE',"%$searchStr%");
        })->get();

        $this->load->blade('messages/_search_result', ['usersList' => $usersList]);
    }

}