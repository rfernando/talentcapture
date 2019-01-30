<?php

class Reviews extends MY_Controller{

    function __construct() {
        $this->adminUserRequired = TRUE;
        parent::__construct();
    }

    function index(){
		$query = "SELECT r.*, CONCAT(uu.first_name, uu.last_name,' <SMALL>@ ', up.company_name,'</SMALL>') AS userName, CONCAT(au.first_name, au.last_name,' <SMALL>@ ', ap.company_name,'</SMALL>') AS agencyName FROM rating AS r, users AS uu, user_profiles AS up,  users AS au, user_profiles AS ap WHERE r.user_id=uu.id AND uu.id=up.user_id AND r.agency_id=au.id AND au.id=ap.user_id ORDER BY r.created_at DESC;";
		$this->data['reviews'] = $this->db->query($query)->result();
        $this->load->blade('admin.reviews.index', $this->data);
    }
	
    function change_status($id= NULL,$id2= NULL){
        if($id == NULL ||$id2 == NULL || !is_numeric($id)|| !is_numeric($id2)) 
		{
			redirect(admin_url('reviews/index'));
		}

		$query = "SELECT * FROM rating WHERE user_id='$id' AND agency_id='$id2';";
		$results=$this->db->query($query)->result();
           
		if($results[0]->rat_status == 1)
		{
			$stat =0;
            
		}
		else
		{
			$stat=1;
		}
		$query = "UPDATE rating SET rat_status='$stat' WHERE user_id='$id' AND agency_id='$id2';";
		$this->db->query($query);
		$response = ['status'=>1];
        echo json_encode($response); die();
    }
}