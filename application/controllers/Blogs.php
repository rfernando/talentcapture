<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Blogs
 *
 * This class will handle all the Basic Authentication Process
 */
class Blogs extends  MY_Controller{

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
            'options' => ['Both', 'Agency','Employer'],
            'validation' => 'required'
        ],
    ];

    /**
     * Blogs constructor.
     *
     */
    public function __construct() {
        $this->authUserRequired = TRUE;
        parent::__construct();
    }

    public function index(){

        $userType = get_user('type');

        if($userType == 'agency')
        {
            $this->data['blogs'] = Admin_blogs::where('view_by', '!=' ,'Employer')->where('status','=',1)->orderBy('created_at','desc')->get();
        }
        else
        {
            $this->data['blogs'] = Admin_blogs::where('view_by', '!=' ,'Agency')->where('status','=',1)->orderBy('created_at','desc')->get();
        }
        
        $this->load->blade('blog/index',$this->data);
    }


    function view_detail($id = null){
        if($id == null || !is_numeric($id))
            redirect(base_url('dashboard'));

        $this->data['blog'] = Admin_blogs::find($id);
        $this->load->blade('blog/view_detail',$this->data);
    }


    

}