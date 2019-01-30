<?php
/**
 * Created by PhpStorm.
 * User: webwerks
 * Date: 11/11/16
 * Time: 5:26 PM
 */
class Blogs extends MY_Controller{

    /**
     * Create form fileds for Blogs Template
     */

    private $blogFields = [
        [
            'name' => 'blog_title',
            'type' => 'text',
            'placeholder' => 'Blog Title',
            'validation' => 'required'
        ],
        [
            'name' => 'blog_desc',
            'type' => 'textarea',
            'placeholder' => 'Blog Description',
            'validation' => 'required'
        ],
        [
            'name' => 'status',
            'type' => 'select',
            'placeholder' => 'Status',
            'options' => ['inactive', 'active'],
            'validation' => 'required'
        ],
        [
            'name' => 'view_by',
            'type' => 'select',
            'placeholder' => 'View By',
            'options' => ['Both' => "Both", 'Agency' => "Agency",'Employer' => "Employer"],
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
     *  View list of Blog posts
     */
    function index(){
        $this->data['admin_blogs'] = Admin_blogs::withTrashed()->orderBy('created_at', 'desc')->get();
        $this->load->blade('admin.blog.index',$this->data);
    }

    /***
     * view add from for Email Template
     */
    function create(){
        $this->data['blogFields'] = $this->get_admin_blog();
        $this->load->blade('admin.blog.create',$this->data);
    }

    /**
     * Create Template
     */
    function addblog(){
        if($this->validateFields()){

            $formFields = $this->input->post();
           
            Admin_blogs::create($formFields);
            redirect(admin_url('blogs'));
        }
        redirect(admin_url('blogs'));
    }

    /***
     * edit Email Template
     */
    function edit($id){
        $blog = Admin_blogs::find($id);
        if(isset($blog)){
            $this->data['blog'] = $blog;
            $this->data['blogFields'] = $this->get_admin_blog();
            $this->data['blogFields'][0]['value'] = $blog->blog_title;
            $this->data['blogFields'][1]['value'] = $blog->blog_desc;
            $this->data['blogFields'][2]['value'] = $blog->status;
            $this->data['blogFields'][3]['value'] = $blog->view_by;
            $this->load->blade('admin.blog.edit',$this->data);
        }

    }

    /**
     *  Edit Function
     */
    function update($id){
        $blog = Admin_blogs::find($id);
        $formFields=$this->input->post();
        $blog->blog_title = $formFields['blog_title'];
        $blog->blog_desc = $formFields['blog_desc'];
        $blog->status = $formFields['status'];
        $blog->view_by = $formFields['view_by'];

        $blog->save();
        redirect(admin_url('blogs'));
    }

    /***
     * delete from for plans
     */
    function delete($id){
        if(Admin_blogs::find($id)){
            Admin_blogs::destroy($id);
            $this->set_flashMsg('success','The Blog post has been successfully deleted');
        }else{
            $this->set_flashMsg('error','You do not have permission to delete this Blog ');
        }
        redirect(admin_url('blogs'));
    }

    /***
     * view Admin blog
     */
    function view($id){
        $this->data['blog'] = Admin_blogs::find($id);
        $this->load->blade('admin.blog.view',$this->data);
    }

    /**
     * get Blog Template Field
     */
    private function get_admin_blog(){
        return $this->blogFields;
    }

    /**
     * Change Status of plans
     */

    function change_status($id){
        $blog = Admin_blogs::find($id);
        if($blog->status == '0'){
            $blog->status = '1';
        }else{
            $blog->status = '0';
        }
        $blog->save();

        echo $blog->status;
    }

    /**
     * Restore Templates
     */

    function restore($id = NULL){
        if($id == NULL )
            $id = $this->input->post('selectedRows');
        Admin_blogs::withTrashed()->where('id', $id)->restore();
        $this->set_flashMsg('success','The Blog has been successfully Restored');
        redirect(admin_url('blogs'));
    }

}