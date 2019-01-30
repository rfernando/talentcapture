<?php

class Auth extends MY_Controller{


    function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->blade('admin.login');
    }

    public function authenticate(){
        $user = User::where([
            'email'     =>      $this->input->post('email'),
            'password'  =>      md5($this->input->post('password')),
            'type'      =>      'admin',
        ])->first();

        if($user == null){
            $this->set_flashMsg('danger','Invalid Credentials');
            redirect(admin_url('login'));
        }else{
            $this->loginUser($user->toArray());
        }
    }

    private function loginUser($user){
        $this->session->set_userdata('admin',$user);
        redirect('admin/dashboard');
    }

    public function logout(){
        $this->session->sess_destroy();
        $this->set_flashMsg('success','You have been Logged Out');
        redirect(admin_url('login'));
    }
}
