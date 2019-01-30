<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;


class Employer extends MY_Controller{

    public function __construct() {
        $this->authUserRequired = TRUE;
        parent::__construct();

        $this->data['agencies'] = User::find(get_user('id'))->favourite_agencies()->with('user_profile')->get();

        if($this->get_profile_percentage() < 100)
            redirect('profile/update');
    }

    public function detail($agency_id) {
        $this->data['agency'] = User::with('user_profile')->find($agency_id);
        $this->data['agency']->load('agency_ratings', 'user_profile');
        $avgRating = 0;
		$rating_count=0;

		//Change reviews to start averaging after one review	
		if(count($this->data['agency']->agency_ratings)>0){
            foreach ($this->data['agency']->agency_ratings as $rating)
                {
					if($rating->pivot->rat_status==1)
						{
							$avgRating += $rating->pivot->rating;
							$rating_count++;
						}
				}
			
            if($rating_count>=1){

                        $avgRating = $avgRating/$rating_count;
                   }else{
                         $avgRating =0;
                   }
        }
        $this->data['avgRating'] = $avgRating;

        $userFavAgenciesList = User::find(get_user('id'))->favourite_agencies()->lists('id');
        $this->data['isFavourite'] = in_array($agency_id, $userFavAgenciesList);

        $userType = User::find($agency_id)->type;

        if($userType == 'agency')
        {
            $this->data['page'] = 'favourites._agency_detail';
            $this->load->blade('favourites/agencies', $this->data);
        }
        else
        {
            $this->data['page'] = 'favourites._emp_detail';
            $this->load->blade('favourites/emp', $this->data);
        }
    }

    
    

}