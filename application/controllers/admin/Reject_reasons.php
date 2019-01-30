<?php

class Reject_reasons extends MY_Controller{

    function __construct() {
        $this->adminUserRequired = TRUE;
        parent::__construct();
    }

    function index() {

        $this->data['reject_reasons'] = My_reject_reasons::withTrashed()->orderBy('reject_option')->get();
        $this->load->blade("admin.reject_reasons.reject_reason", $this->data);
    }


    function add(){
        
        $reject_option = My_reject_reasons::create($this->input->post());
        
        $this->set_flashMsg('success', 'Reject Reason was added successfully');
        redirect(admin_url('reject_reasons'));
    }

    /**
     * @param null $id
     */
    function edit($id = NULL){
        if($id == NULL || !is_numeric($id)) redirect(admin_url('reject_reasons'));
        
        try{
            $reject_option  = My_reject_reasons::findOrFail($id);
            $reject_option->reject_option = $this->input->post('reject_option');
            $reject_option->save();
            $this->set_flashMsg('success', 'Reject Reason was updated successfully');
        }catch (ModelNotFoundException $e){
            $this->set_flashMsg('error','The record you are trying to update does not exist');
        }
        redirect(admin_url('reject_reasons'));
    }

    function delete($data = NULL){
        if($data == NULL )
            $data = $this->input->post('selectedRows');
            My_reject_reasons::destroy($data);
            $this->set_flashMsg('success','The Reject Reason has been successfully deleted');
            redirect(admin_url('reject_reasons'));
    }

    function restore($data = NULL){
        if($data == NULL )
            $data = $this->input->post('selectedRows');
        
        My_reject_reasons::withTrashed()->where('id', $data)->restore();
        $this->set_flashMsg('success','The Reject Reason has been successfully restored');

        redirect(admin_url('reject_reasons'));
    }

}