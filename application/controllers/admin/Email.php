<?php
/**
 * Created by PhpStorm.
 * User: webwerks
 * Date: 11/11/16
 * Time: 5:26 PM
 */
class Email extends MY_Controller{

    /**
     * Create form fileds for Email Template
     */

    private $templateFields= [
        [
            'name' => 'template_name',
            'type' => 'text',
            'placeholder' => 'Template Name',
            'validation' => 'required'
        ],
        [
            'name' => 'template_subject',
            'type' => 'text',
            'placeholder' => 'Template Subject',
            'validation' => 'required'
        ],
        [
            'name' => 'template_body',
            'type' => 'textarea',
            'placeholder' => 'Template Body',
            'validation' => 'required',
        ],
        [
            'name' => 'status',
            'type' => 'select',
            'placeholder' => 'Status',
            'options' => ['inactive', 'active'],
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
     *  View list of Email Template
     */
    function index(){
        $this->data['email_templates'] = Email_templates::withTrashed()->get();
        $this->load->blade('admin.email.index',$this->data);
    }

    /***
     * view add from for Email Template
     */
    function create(){
        $this->data['templateFields'] = $this->get_email_template();
        $this->load->blade('admin.email.create',$this->data);
    }

    /**
     * Create Template
     */
    function addtemplate(){
        if($this->validateFields()){

            $formFields = $this->input->post();
            $formFields['template_name']=strtoupper($formFields['template_name']);
            Email_templates::create($formFields);
            redirect(admin_url('email'));
        }
        redirect(admin_url('email/'));
    }

    /***
     * edit Email Template
     */
    function edit($id){
        $template = Email_templates::find($id);
        if(isset($template)){
            $this->data['template'] = $template;
            $this->data['templateFields'] = $this->get_email_template();
            $this->data['templateFields'][0]['value'] = $template->template_name;
            $this->data['templateFields'][1]['value'] = $template->template_subject;
            $this->data['templateFields'][2]['value'] = $template->template_body;
            $this->data['templateFields'][3]['value'] = $template->status;
            $this->load->blade('admin.email.edit',$this->data);
        }

    }

    /**
     *  Edit Function
     */
    function update($id){
        $template = Email_templates::find($id);
        $formFields=$this->input->post();
        $template->template_name = $formFields['template_name'];
        $template->template_subject = $formFields['template_subject'];
        $template->template_body = $formFields['template_body'];
        $template->status = $formFields['status'];
        $template->save();
        redirect(admin_url('email'));
    }

    /***
     * delete from for plans
     */
    function delete($id){
        if(Email_templates::find($id)){
            Email_templates::destroy($id);
            $this->set_flashMsg('success','The Email Template has been successfully deleted');
        }else{
            $this->set_flashMsg('error','You do not have permission to delete this Email Template ');
        }
        redirect(admin_url('email'));
    }

    /***
     * view Email Template
     */
    function view($id){
        $this->data['email'] = Email_templates::find($id);
        $this->load->blade('admin.email.view',$this->data);
    }

    /**
     * get Email Template Field
     */
    private function get_email_template(){
        return $this->templateFields;
    }

    /**
     * Change Status of plans
     */

    function change_status($id){
        $template = Email_templates::find($id);
        if($template->status == '0'){
            $template->status = '1';
        }else{
            $template->status = '0';
        }
        $template->save();

        echo $template->status;
    }

    /**
     * Restore Templates
     */

    function restore($id = NULL){
        if($id == NULL )
            $id = $this->input->post('selectedRows');
        Email_templates::withTrashed()->where('id', $id)->restore();
        $this->set_flashMsg('success','The Email Template has been successfully Restored');
        redirect(admin_url('email'));
    }

    /*
     * Variable Guide Template
     */
    function variable_guide(){
        $this->load->blade('admin.email.guide');
    }

}