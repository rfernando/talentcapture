<?php


use Illuminate\Database\Eloquent\ModelNotFoundException;

class Job_categories extends MY_Controller{

    function __construct() {
        $this->adminUserRequired = TRUE;
        parent::__construct();
    }

    function index($pageName = 'industry') {
        if(!in_array($pageName,['industry', 'profession'])) redirect(admin_url('dashboard'));
        $className = ucfirst($pageName);
        $this->data[$pageName] = $className::withTrashed()->get();
        if($pageName == 'profession'){
            $this->data['industryOptions'] = Industry::all();
        }
        //$this->data['options'] = $this->buildTree($this->data['job_categories']->toArray());
        $this->load->blade("admin.job_categories.$pageName", $this->data);
    }


    function add(){
        $className = ucfirst($this->input->get('v'));
        $category = $className::create($this->input->post(strtolower($className)));
        if(strtolower($className) == 'profession')
            $category->industries()->attach($this->input->post('industries'));
        $this->set_flashMsg('success', 'Job Category was added successfully');
        redirect(admin_url('job_categories/'.strtolower($className)));
    }

    /**
     * @param null $id
     */
    function edit($id = NULL){
        if($id == NULL || !is_numeric($id)) redirect(admin_url('job_categories'));
        $className = ucfirst($this->input->get('v'));
        try{
            $category  = $className::findOrFail($id);
            $category->title = $this->input->post('title');
            $category->save();
            if(strtolower($className) == 'profession')
                $category->industries()->sync($this->input->post('industries'));
            $this->set_flashMsg('success', 'Job Category was added successfully');
        }catch (ModelNotFoundException $e){
            $this->set_flashMsg('error','The record you are trying to update does not exist');
        }
        redirect(admin_url('job_categories/'.$this->input->get('v')));
    }

    function delete($data = NULL){
        if($data == NULL )
            $data = $this->input->post('selectedRows');
        $className = ucfirst($this->input->get('v'));
        $jobCategory = $className::find($data);
        if(!$jobCategory->jobs()->count() && !$jobCategory->users()->count() && (strtolower($className) != 'profession' || !$jobCategory->industries()->count())){
            $className::destroy($data);
            $this->set_flashMsg('success','The Job Category has been successfully Deleted');
        }else{
            $this->set_flashMsg('error','The Job Category cannot be deleted as it is being used');
        }
        redirect(admin_url('job_categories/'.$this->input->get('v')));
    }


    function restore($data = NULL){
        if($data == NULL )
            $data = $this->input->post('selectedRows');
        $className = ucfirst($this->input->get('v'));
        $className::withTrashed()->where('id', $data)->restore();
        $this->set_flashMsg('success','The Job Category has been successfully Restored');
        redirect(admin_url('job_categories/'.$this->input->get('v')));
    }

}