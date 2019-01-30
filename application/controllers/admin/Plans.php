<?php
/**
 * Created by PhpStorm.
 * User: webwerks
 * Date: 11/11/16
 * Time: 5:26 PM
 */
class Plans extends MY_Controller{

    /**
     * Create form fileds for free trail
     */
    private $freeTrialField= [
        [
            'name' => 'no_of_days',
            'type' => 'text',
            'placeholder' => 'Free Trail Period (in Days)',
            'validation' => 'required',
            'min' => '1'
        ],
    ];

    private $plansFields= [
        [
            'name' => 'plan_name',
            'type' => 'text',
            'placeholder' => 'Plan Name',
            'validation' => 'required'
        ],
        [
            'name' => 'no_of_days',
            'type' => 'text',
            'placeholder' => 'Plan Period (in Days)',
            'validation' => 'required',
            'min' => '1',
            'onkeypress' => 'validatenumber(event)'
        ],
        [
            'name' => 'amount',
            'type' => 'text',
            'placeholder' => 'Amount (in $)',
            'validation' => 'required',
            'min' => '1',
        ],
        [
            'name' => 'description',
            'type' => 'textarea',
            'placeholder' => 'Description',
        ],
        [
            'name' => 'status',
            'type' => 'select',
            'placeholder' => 'Status',
            'options' => ['inactive', 'active'],
            'validation' => 'required'
        ],
        [
            'name' => 'plan_id',
            'type' => 'select',
            'placeholder' => 'Plan',
            'options' => ['1'=>'Split Fee Network : $75.00 USD - monthly', '2'=>'Split Fee Network + Dashboard Advertising : $150.00 USD - monthly'],
            'validation' => 'required'
        ],
    ];

    /**
     * Plans constructor.
     */
    function __construct() {
        $this->adminUserRequired = TRUE;
        parent::__construct();
    }

    /**
     *  View list of plans
     */
    function index(){
        $this->data['subscription_plans'] = Subscription_plans::withTrashed()->get();
        $this->load->blade('admin.plans.index',$this->data);
    }

    /***
     * view add from for plans
     */
    function create(){
        $this->data['plansFields'] = $this->get_values_plans();

        $this->load->blade('admin.plans.create',$this->data);
    }

    /**
     * add plans
     */
    function addplans(){
        if($this->validateFields()){
            $formFields = $this->input->post();
            Subscription_plans::create($formFields);
            redirect(admin_url('plans'));
        }
        redirect(admin_url('plans/'));
    }

    /***
     * edit from for plans
     */
    function edit($id){
        $plan = Subscription_plans::find($id);
        if(isset($plan)){
            $this->data['plan'] = $plan;
            $this->data['plansFields'] = $this->get_values_plans();
            $this->data['plansFields'][0]['value'] = $plan->plan_name;
            $this->data['plansFields'][1]['value'] = $plan->no_of_days;
            $this->data['plansFields'][2]['value'] = $plan->amount;
            $this->data['plansFields'][3]['value'] = $plan->description;
            $this->data['plansFields'][4]['value'] = $plan->status;
            $this->load->blade('admin.plans.edit',$this->data);
        }

    }

    /**
     *  Edit Function
     */
    function update($id){
        $plan = Subscription_plans::find($id);
        $formFields=$this->input->post();
        $plan->plan_name = $formFields['plan_name'];
        $plan->no_of_days = $formFields['no_of_days'];
        $plan->amount = $formFields['amount'];
        $plan->description = $formFields['description'];
        $plan->status = $formFields['status'];
        $plan->save();
        redirect(admin_url('plans'));
    }

    /***
     * delete from for plans
     */
    function delete($id){
        if(Subscription_plans::find($id)){
            Subscription_plans::destroy($id);
            $this->set_flashMsg('success','The Subscription Plan has been successfully deleted');
        }else{
            $this->set_flashMsg('error','You do not have permission to delete this Subscription Plan ');
        }
        redirect(admin_url('plans'));
    }

    /***
     * view plan
     */
    function view($id){
        $this->data['plan'] = Subscription_plans::find($id);
        $this->load->blade('admin.plans.view',$this->data);
    }

    /**
     * view and add free trial
     */
    function freetrial(){
        // $freetrial = Free_trial::withTrashed()->get();
        // $this->data['freeTrialField'] = $this->get_values();
        // if(isset($freetrial[0]) && (!empty($freetrial[0]))){
        //     $this->data['freetrialId']=$freetrial[0]->id;
        //     $this->data['freeTrialField'][0]['value']=$freetrial[0]->no_of_days;
        // }else{
        //     $this->data['freetrialId']='';
        // }

        $this->data['traildetails'] = Free_trial::all();
        $this->data['agencyOptions'] = User::where('type','=','agency')->get();
        $this->load->blade('admin.plans.freetrial',$this->data);
    }

    /***
     * add or update free trial
     */
    function addfreetrial(){
        // if($this->validateFields()){
        //     $formFields = $this->input->post();
        //     if(empty($formFields['id'])){
        //         Free_trial::create($formFields);
        //     }else{
        //         $free_trial = Free_trial::find($formFields['id']);
        //         $free_trial->no_of_days = $formFields['no_of_days'];
        //         $free_trial->save();
        //     }
        //     redirect(admin_url('plans/freetrial'));
        // }
        
        $formFields = $this->input->post();

        if(!empty($formFields['no_of_days'])){
            $checkuser = Free_trial::where('agency_id','=',$formFields['agency_id'])->count();
            if($checkuser > 0)
            {
                $this->set_flashMsg('error','User Record Already Exist'); 
            }
            else
            {
                Free_trial::create($formFields);
                //added for update user table to set trial
                $user = User::find($formFields['agency_id']);
                $user->is_trial  = '1';
                $user->plan_id = $this->input->post('plan_id');
                $user->save();
                //end
                $this->set_flashMsg('success','The Record has been successfully added');
            }
        }
        redirect(admin_url('plans/freetrial'));

    }

    function deletetrial($id){
        if(Free_trial::find($id)){
            Free_trial::destroy($id);
            $this->set_flashMsg('success','The Record has been successfully deleted');
        }else{
            $this->set_flashMsg('error','You do not have permission to delete this Record');
        }
        redirect(admin_url('plans/freetrial'));
    }

    function edittrial($id = NULL){
        if($this->input->post())
        {
            $category  = Free_trial::where('agency_id','=',$this->input->post('agency_id'))->first();
            $category->no_of_days = $this->input->post('no_of_days');
            $category->plan_id = $this->input->post('plan_id');
            $category->save();
            //added for update user table to set trial
			if ($id!=0)
			{
				$user = User::find($this->input->post('agency_id'));
				$user->is_trial  = '1';
                $user->plan_id = $this->input->post('plan_id');
				$user->save();
			}
            //end
            $this->set_flashMsg('success', 'Record updated successfully');
            redirect(admin_url('plans/freetrial'));
        }
        else
        {
            redirect(admin_url('plans/freetrial'));
        }
    }

    /***
     * get from filed
     */
    private function get_values(){
        return $this->freeTrialField;
    }

    /**
     * get Plans Field
     */
    private function get_values_plans(){
        return $this->plansFields;
    }

    /**
     * Change Status of plans
     */

    function change_status($id){
        $plan = Subscription_plans::find($id);
        if($plan->status == '0'){
            $plan->status = '1';
        }else{
            $plan->status = '0';
        }
        $plan->save();

        echo $plan->status;
    }

    /**
     * Restore plans
     */

    function restore($id = NULL){
        if($id == NULL )
        $id = $this->input->post('selectedRows');
        Subscription_plans::withTrashed()->where('id', $id)->restore();
        $this->set_flashMsg('success','The Subscrip[tion Plans has been successfully Restored');
        redirect(admin_url('plans'));
    }

}