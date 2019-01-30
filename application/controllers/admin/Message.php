<?php
/**
 * Created by PhpStorm.
 * User: webwerks
 * Date: 11/11/16
 * Time: 5:26 PM
 */
class Message extends MY_Controller{

    /**
     * Create form fileds for message Template
     */

    private $messageFields= [
        [
            'name' => 'type',
            'type' => 'text',
            'placeholder' => 'Type',
            'validation' => 'required',
            'readonly'=>true
        ],
        [
            'name' => 'msg',
            'type' => 'text',
            'placeholder' => 'Message',
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
     *  View list of message Template
     */
    function index(){
        $this->data['message_templates'] = Site_messages::all();
        $this->load->blade('admin.sitemessages.index',$this->data);
    }

    /***
     * edit message Template
     */
    function edit($id){
        $template = Site_messages::find($id);
        if(isset($template)){
            $this->data['template'] = $template;
            $this->data['messageFields'] = $this->get_message_template();
            $this->data['messageFields'][0]['value'] = $template->type;
            $this->data['messageFields'][1]['value'] = $template->msg;
            $this->load->blade('admin.sitemessages.edit',$this->data);
        }
    }

    /**
     *  Edit Function
     */
    function update($id){
        $template = Site_messages::find($id);
        $formFields=$this->input->post();
        $template->type = $formFields['type'];
        $template->msg = $formFields['msg'];
        $template->save();
        $this->set_flashMsg('success','The Message Template has been successfully updated');
        redirect(admin_url('message'));
    }


    /**
     * get message Template Field
     */
    private function get_message_template(){
        return $this->messageFields;
    }

}