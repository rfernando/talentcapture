<?php

// JWT PHP Library https://github.com/firebase/php-jwt
use \Firebase\JWT\JWT;

class Meetings extends MY_Controller{

	function __construct() {
        $this->authUserRequired = TRUE;
        parent::__construct();
    }

	//function to generate JWT
	function generateJWT () {
		//Zoom API credentials from https://developer.zoom.us/me/
		$key = 'h5LltYdcTJitRGSdspCjBw';
		$secret = 'psbHEcsiSe1HS0uxz2o1IKiEove8eiNqEG8W';
		$token = array(
			"iss" => $key,
	        // The benefit of JWT is expiry tokens, we'll set this one to expire in 1 minute
			"exp" => time() + 60
		);

		return JWT::encode($token, $secret);
	}


	function createUsers ($userId) {

		echo "<pre>";
		print_r('createUsers'. '<br>');
		exit;

	    //list users endpoint GET https://api.zoom.us/v2/users
		$ch = curl_init('https://api.zoom.us/v2/users');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  
	    // add token to the authorization header
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization: Bearer ' . $this->generateJWT()
		));

		$userData = '{
		    "action": "custCreate",
		    "user_info": {
		        "email": "perituza.appdev@gmail.com",
		        "type": 1,
		        "first_name": "App",
		        "last_name": "Dev",
		        "password": "AppDev@2018"
		    }
		}';

		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $userData);

		$response = curl_exec($ch);
		
		$response = json_decode($response);
		
		return $response;
	}

	function getUsers ($userId) {
	    //list users endpoint GET https://api.zoom.us/v2/users
		$ch = curl_init('https://api.zoom.us/v2/users/jeff.oliver@rainmakercr.com');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  
	    // add token to the authorization header
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Bearer ' . $this->generateJWT()
		));

		$response = curl_exec($ch);
		
		
		echo "<pre>";
		print_r('$response - start 1'. '<br>');
	    print_r($response);
	    print_r('<br>'.'$response - end 1');

	    $response = json_decode($response);
		
		return $response;
	}

	function getMeeting() {
	    //list users endpoint GET https://api.zoom.us/v2/users
		$ch = curl_init('https://api.zoom.us/v2/users/jeff.oliver@rainmakercr.com/meetings');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  
	    // add token to the authorization header
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Bearer ' . $this->generateJWT()
		));
		$response = curl_exec($ch);
		$response = json_decode($response);
		
		return $response;
	}


	function createMeeting ($userId, $meetingDetails) {

		$url = 'https://api.zoom.us/v2/users/'.$userId.'/meetings';

		/*$url = 'https://api.zoom.us/v2/users/jeff.oliver@rainmakercr.com/meetings';*/

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  
	    // add token to the authorization header
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization: Bearer ' . $this->generateJWT()
		));

		$start_date = $meetingDetails['meeting_start_date'];
		$start_time = $meetingDetails['meeting_start_time'];

		$end_date = $meetingDetails['meeting_end_date'];
        $end_time = $meetingDetails['meeting_end_time'];

		$start_date_time = $start_date .' '.$start_time.':00';
		$end_date_time = $end_date .' '.$end_time.':00';


		$start_date_time_value = strtotime($start_date_time);
		$end_date_time_value = strtotime($end_date_time);

		$meeting_duration =  round(abs($end_date_time_value - $start_date_time_value) / 60,2);


		$time_zone = substr($meetingDetails['metting_time_zone'],3);
		$time_value = substr($meetingDetails['metting_time_zone'],0,2);

		$time_value = "00";

		$new_start_date_time = date("Y-m-d H:i:s",strtotime($start_date_time) - 60 * 60 * intval($time_value));

		$start_meeting_date_time = date("Y-m-d",strtotime($start_date_time) - 60 * 60 * intval($time_value)) .'T'.date("H:i:s",strtotime($start_date_time) - 60 * 60 * intval($time_value)).':00Z';

		$userData = '{
		    "topic": "'.$meetingDetails['meeting_title'].'",
		    "type": 2,
		    "start_time": "'.$start_meeting_date_time.'",
            "duration": '.$meeting_duration.',
		    "timezone": "'.$time_zone.'"
		}';


		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $userData);

		$response = curl_exec($ch);

		$response = json_decode($response);

		return $response;
	}

	function send_meeting_invitations($jobId){
		/*Get the meeting details added no the screen*/
        $meetingDetails = $this->input->post();

        /*Job owners email address*/
		$fromUserEmail = get_user('email');

		/*To create the meeting and get the details*/
		$meeting = $this->createMeeting($fromUserEmail,$meetingDetails);

		/*Get the users who accepted the job*/
		$queryAcceptedUsers = "SELECT user_id FROM accepted_jobs WHERE estatus = 1 AND job_id='$jobId';";
		$resultAcceptedUsers = $this->db->query($queryAcceptedUsers)->result();

		$job = Job::findOrFail($jobId);

		$start_date = $meetingDetails['meeting_start_date'];
		$start_time = $meetingDetails['meeting_start_time'];

		$end_date = $meetingDetails['meeting_end_date'];
        $end_time = $meetingDetails['meeting_end_time'];

        $start_date = date("Y-m-d",strtotime($start_date));
        $end_date = date("Y-m-d",strtotime($end_date));

		$start_date_time = $start_date .' '.$start_time.':00';
		$end_date_time = $end_date .' '.$end_time.':00';

		$start_date_time_value = strtotime($start_date_time);
        $end_date_time_value = strtotime($end_date_time);

		$meeting_duration =  round(abs($end_date_time_value - $start_date_time_value) / 60,2);

		$time_zone = substr($meetingDetails['metting_time_zone'],3);
		$time_value = substr($meetingDetails['metting_time_zone'],0,2);

		$start_meeting_date_time = $start_date.'T'.$start_time.':00';
		$end_meeting_date_time = $end_date.'T'.$end_time.':00';	

        $email_variable['JOBOWNER'] = ucwords(get_user('first_name').' '.get_user('last_name'));
        $email_variable['JOBNAME'] = ucfirst($job->title);
        $email_variable['MEETINGTITLE'] = ucfirst($meetingDetails['meeting_title']	);
        $email_variable['MEETINGLOCATION'] = ucfirst($meetingDetails['meeting_location']);
        $email_variable['MEETINGTIME'] = $start_date_time.' ('.$time_zone.')';
        $email_variable['MEETINGMESSAGE'] = $meetingDetails['meeting_message'];
        $email_variable['MEETINGURL'] = $meeting->{'join_url'};
        $email_variable['MEETINGID'] = $meeting->{'id'};
        
        $email_data = email_template(27,$email_variable);


		/*Send email invitation for each user*/
		foreach($resultAcceptedUsers as $acceptedUser)
		{
			$agency_user = User::find($acceptedUser->user_id);
			$agency_email = $agency_user->email;
			
			$attendee = new Google_Service_Calendar_EventAttendee();
	        $attendee->setEmail($agency_email);
	        $attendee_arr[]= $attendee;
            
		}

		// Add from user
		$creator = new Google_Service_Calendar_EventAttendee();
	    $creator->setEmail($fromUserEmail);
		$attendee_arr[]= $creator;

		$client = $this->getClient();

		$service = new Google_Service_Calendar($client);

		$email_desc = strip_tags($email_data['template_body']);
		$email_desc = str_replace('Click Here','<a href="'.$meeting->{'join_url'}.'">Click Here</a>',$email_desc);

		$event = new Google_Service_Calendar_Event(array(
		  'summary' => ucfirst($meetingDetails['meeting_title']),
		  'location' => ucfirst($meetingDetails['meeting_location']),
		  'description' => $email_desc,
		  'start' => array(
		    'dateTime' => $start_meeting_date_time,
		    'timeZone' => $time_zone,
		  ),
		  'end' => array(
		    'dateTime' => $end_meeting_date_time,
		    'timeZone' => $time_zone,
		  ),
		  
		  'attendees' => $attendee_arr,
		  'reminders' => array(
		    'useDefault' => TRUE
		  ),
		  'guestsCanSeeOtherGuests'=> false,
		));

		$calendarId = 'primary';

		$optParams = Array(
        	'sendNotifications' => true,
		);

		$event = $service->events->insert($calendarId, $event,$optParams);

		return redirect(base_url('dashboard?schedule_success=1'));
	}

	//----------------




	//-----------------

	function sendInv()
	{
		$client = $this->getClient();

		$service = new Google_Service_Calendar($client);

		$event = new Google_Service_Calendar_Event(array(
		  'summary' => 'Google I/O 2015',
		  'location' => '800 Howard St., San Francisco, CA 94103',
		  'description' => '<p>Title: MEETINGTITLE </p><p>Location: $MEETINGLOCATION$ </p>',
		  'start' => array(
		    'dateTime' => '2018-05-28T09:00:00-07:00',
		    'timeZone' => 'America/Los_Angeles',
		  ),
		  'end' => array(
		    'dateTime' => '2018-05-28T17:00:00-07:00',
		    'timeZone' => 'America/Los_Angeles',
		  ),
		  'attendees' => array(
		    array('email' => 'lakmal.silva@perituza.com'),
		    array('email' => 'perituza.appdev@gmail.com'),
		  ),
		  'reminders' => array(
		    'useDefault' => TRUE
		  ),
		));

		$calendarId = 'primary';

		$optParams = Array(
        	'sendNotifications' => true,
		);

		$event = $service->events->insert($calendarId, $event,$optParams);
		printf('Event created: %s\n', $event->htmlLink);
	}


	/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
	function getClient()
	{
	    $client = new Google_Client();
	    $client->setApplicationName('Talent Capture');
	    $client->setScopes(Google_Service_Calendar::CALENDAR);
	    $client->setAuthConfig('client_secret.json');
	    $client->setAccessType('offline');

	    // Load previously authorized credentials from a file.
	    $credentialsPath = $this->expandHomeDirectory('credentials.json');
	    if (file_exists($credentialsPath)) {
	        $accessToken = json_decode(file_get_contents($credentialsPath), true);
	    } else {
	        // Request authorization from the user.
	        $authUrl = $client->createAuthUrl();
	        printf("Open the following link in your browser:\n%s\n", $authUrl);
	        print 'Enter verification code: ';
	        $authCode = "4/AAC56EIRDududyPrnpoHxoyPeFoj4pVbXcSkDm8ugOyo7yS8pX_qUc4";

	        // Exchange authorization code for an access token.
	        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

	        // Store the credentials to disk.
	        if (!file_exists(dirname($credentialsPath))) {
	            mkdir(dirname($credentialsPath), 0700, true);
	        }
	        file_put_contents($credentialsPath, json_encode($accessToken));
	        printf("Credentials saved to %s\n", $credentialsPath);
	    }
	    $client->setAccessToken($accessToken);

	    // Refresh the token if it's expired.
	    if ($client->isAccessTokenExpired()) {
	        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
	        file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
	    }
	    return $client;
	}

	/**
	 * Expands the home directory alias '~' to the full path.
	 * @param string $path the path to expand.
	 * @return string the expanded path.
	 */
	function expandHomeDirectory($path)
	{
	    $homeDirectory = getenv('HOME');
	    if (empty($homeDirectory)) {
	        $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
	    }
	    return str_replace('~', realpath($homeDirectory), $path);
	}

}
