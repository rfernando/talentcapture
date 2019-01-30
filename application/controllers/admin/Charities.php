<?php

class Charities extends MY_Controller{

    function __construct() {
        $this->adminUserRequired = TRUE;
        parent::__construct();
    }

    function index() {

        $this->data['charities'] = My_charities::withTrashed()->orderBy('title')->get();

        $this->load->blade("admin.charities.charity", $this->data);
    }


    function add(){
        
        $charity = My_charities::create($this->input->post());
        
        $this->set_flashMsg('success', 'Charity was added successfully');
        redirect(admin_url('charities'));
    }

    /**
     * @param null $id
     */
    function edit($id = NULL){
        if($id == NULL || !is_numeric($id)) redirect(admin_url('charities'));
        
        try{
            $charity  = My_charities::findOrFail($id);
            $charity->title = $this->input->post('title');
            $charity->save();
            $this->set_flashMsg('success', 'Charity was updated successfully');
        }catch (ModelNotFoundException $e){
            $this->set_flashMsg('error','The record you are trying to update does not exist');
        }
        redirect(admin_url('charities'));
    }

    function delete($data = NULL){
        if($data == NULL )
            $data = $this->input->post('selectedRows');

        $charity = My_charities::find($data);

        //print_r($charity->users()->count());exit;
        $assigned_users_count = $charity->users()->count();


        if(!$charity->users()->count()){
            My_charities::destroy($data);
            $this->set_flashMsg('success','The charity has been successfully deleted');
        }else{
            $this->set_flashMsg('error','The charity cannot be deleted as it is being used');
        }

        redirect(admin_url('charities'));
    }


    function restore($data = NULL){
        if($data == NULL )
            $data = $this->input->post('selectedRows');
        
        My_charities::withTrashed()->where('id', $data)->restore();
        $this->set_flashMsg('success','The charity has been successfully restored');

        redirect(admin_url('charities'));
    }

}