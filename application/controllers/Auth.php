<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class AuthController
 *
 * This class will handle all the Basic Authentication Process
 */
class Auth extends  MY_Controller{

    /**
     * @var array
     *
     * Login Fields Array
     */
    private $loginFields = [
        [
            'name' => 'u_email',
            'type' => 'text',
            'placeholder' => 'Email',
            'validation' => 'required'
        ],
        [
            'name' => 'pwd',
            'type' => 'password',
            'placeholder' => 'Password',
            'validation' => 'required'
        ],
    ];

    /**
     * @var array
     *
     * Forgot password Fields Array
     */
    private $forgotPassword = [
        [
            'name' => 'fp_email',
            'type' => 'text',
            'placeholder' => 'Email',
            'validation' => 'required'
        ],
    ];

    /**
     * @var array
     *
     * Registration Fields Array
     */
    private $registrationFields = [
        [
            'name' => 'users[first_name]',
            'type' => 'text',
            'placeholder' => 'First Name',
            'validation' => 'trim|required'
        ],
        [
            'name' => 'users[last_name]',
            'type' => 'text',
            'placeholder' => 'Last Name',
            'validation' => 'trim|required'
        ],
        [
            'name' => 'users[email]',
            'type' => 'text',
            'placeholder' => 'Email Address',
            'validation' => 'trim|required|valid_email|is_unique[users.email]'
        ],
        [
            'name' => 'users[phone]',
            'type' => 'text',
            'placeholder' => 'Phone',
            'validation' => 'trim|required'
        ],
        [
            'name' => 'users[password]',
            'type' => 'password',
            'placeholder' => 'Password',
            'validation' => 'required'
        ],
        [
            'name' => 'cnf_password',
            'type' => 'password',
            'placeholder' => 'Retype Password',
            'validation' => 'required|matches[users-password]'
        ],
		/*[
            'name' => 'company_name_dd',
            'type' => 'select',
            'placeholder' => 'Company Name',
			'options' => ['' => 'Select A Company']
        ],*/
        [
            'name' => 'user_profiles[company_name]',
            'type' => 'text',
            'placeholder' => 'Company Name',
            'validation' => 'required',
        ],
        [
            'name' => 'user_profiles[company_website_url]',
            'type' => 'text',
            'placeholder' => 'Company Website',
            'validation' => 'required|valid_url',
        ],
        [
            'name' => 'users[type]',
            'type' => 'select',
            'placeholder' => 'User Type',
            'validation' => 'required',
            'options' => ['' => 'Select User Type','employer' => 'Employer', 'agency' => 'Agency']
        ],
        [
            'name' => 'user_profiles[role]',
            'type' => 'select',
            'placeholder' => 'Select Role',
            'validation' => 'required',
            'options' => ['' => 'Select Role']
        ],
        [
            'name' => 'industries[]',
            'type' => 'select',
            'placeholder' => 'Select Industries',
            'validation' => 'required',
            'multiple'  => 'multiple',
            'options' => []
        ],
        [
            'name' => 'profession[]',
            'type' => 'select',
            'placeholder' => 'Select Roles',
            'validation' => 'required',
            'multiple'  => 'multiple',
            'options' => []
        ],
		[
            'name' => 'user_profiles[company_desc]',
            'type' => 'hidden',
			'placeholder' => 'Company Description',
        ],
		[
            'name' => 'user_profiles[company_address]',
            'type' => 'hidden',
			'placeholder' => 'Company Address',
        ],
		[
            'name' => 'user_profiles[city]',
            'type' => 'hidden',
			'placeholder' => 'Company City',
        ],
		[
            'name' => 'user_profiles[state_id]',
            'type' => 'hidden',
			'placeholder' => 'Company State Id',
        ],
		[
            'name' => 'user_profiles[zipcode]',
            'type' => 'hidden',
			'placeholder' => 'Company Zipcode',
        ],
        [
            'name' => 'users[register_mode]',
            'type' => 'hidden',
            'placeholder' => 'Register Mode',
        ]
    ];

    private $resetPassword = [
        [
            'name' => 'pwd',
            'type' => 'password',
            'placeholder' => 'Password',
            'validation' => 'required'
        ],
        [
            'name' => 're-pwd',
            'type' => 'password',
            'placeholder' => 'Re-Password',
            'validation' => 'required'
        ],
    ];



    /**
     * Auth constructor.
     *
     * If user is Logged In Block access to methods of this Controller
     * Logout Method has exception Though
     */
    public function __construct() {
        parent::__construct();
        if(get_user('id') != '' && !in_array($this->uri->segment(1) ,['logout','terms_and_conditions','validate_unique']) )
            redirect('dashboard');
    }

    /**
     * login_form
     *
     * Load the Login Form
     */
    public function login_form() {
        $url = $this->session->userdata('user_url');
        if ($url != '') {
            $user = explode('/',$url);
            if (is_numeric($user['9'])) {
                $user_id = $user['9'];
            }else{
                 $user_id = $user['11'];
            }
            $details = User::find($user_id);
            $email = '';
            if (!empty($details)) {
                $email = $details->email;
            }
            foreach ($this->loginFields as $k=> &$field){
                if($k==0){
                    $field['value'] = $email;
                }
            }
        }

        $this->data['loginFields'] = $this->loginFields;
        $this->load->blade('Auth/login', $this->data);
    }

    /**
     * load_forms
     *
     * Load the Registration Form
     */
    public function register_form() {
        $this->data['registrationFields'] = $this->registrationFields;
        /*$this->data['registrationFields'][6]['options'] = ['' =>['' => 'New Company'],'Existing Companies' =>User_profile::orderBy('company_name','asc')->groupBy('company_name')->lists("company_name","company_name")];*/
        $this->data['registrationFields'][10]['options'] = Industry::orderBy('title','asc')->lists("title","id");
        $this->load->blade('Auth/register', $this->data);
    }

    /**
     * forgot_password_form
     *
     * Load the Forgot Password Form
     */
    public function fp_form() {
        $this->data['forgotPassword'] = $this->forgotPassword;
        $this->load->blade('Auth/forgot_password', $this->data);
    }

    /**
     * reset password form
     */
    public function reset_form($userId,$passwd_change_token){
        $this->data['resetPassword'] = $this->resetPassword;
        $this->data['userId'] = $userId;
        $this->data['passwd_change_token'] = $passwd_change_token;
        $this->load->blade('Auth/restore_password', $this->data);
    }


    /**
     * register
     *
     * Handle User Registration
     */
    public function register() {

        if($this->validateFields()){

            $userData = $this->input->post('users');
            $userProfileData = $this->input->post('user_profiles');

            $userData['phone'] = implode('',explode('-',implode('',explode('+',$userData['phone']))));

            if(empty($userData['register_mode']))
            {
                $userData['register_mode'] = 0;
            }

            if(empty($userProfileData['state_id']) || !isset($userProfileData['state_id']))
            {
                $userProfileData['state_id'] = NULL;
            }

            // Generate the password only for direct registrations
            if($userData['register_mode'] == 0){
                $userData['password'] = md5($userData['password']);
            }

            $user = User::create($userData);
            $userProfile = new User_profile($userProfileData);
            
            $user->user_profile()->save($userProfile);

            $user->industries()->attach($this->input->post('industries'));

            if($user->type == 'agency'){
                $user->professions()->attach($this->input->post('profession'));
            }
            //Send email to Admin
            // $this->send_registration_mail($user);
            //Send email to agency/employer on registering

            $email_variable['FIRSTNAME'] = $user->first_name;
            $email_variable['LASTNAME'] = $user->last_name;
            $email_variable['COMPANYNAME'] = $userProfile->company_name;
            $email_variable['USERTYPE'] = $user->type;

            $email_data=email_template(16,$email_variable);

            if(isset($email_data)){
                $emailData['body'] = $email_data['template_body'];
                $emailData['to'] = ADMIN_EMAIL;
                $emailData['from'] = WEBSITE_FROM_EMAIL;
                $emailData['subject'] = $email_data['template_subject'];;
                email($emailData);
            }


           /**
            $useremail = $user->email;
            $emailData = [
                'to'        => $useremail,
                'from'      => WEBSITE_FROM_EMAIL,
                'subject'   => 'TalentCapture Recruiting Platform Registration'
            ];

            if($user->type == 'agency') {
                $emailData['body'] = 'Thank you for registering a new account on the TalentCapture Recruiting Platform.  One of our team members will follow up with you soon to discuss completing the process.';
            }else{
                $emailData['body'] = 'Thank you for registering a new account on the TalentCapture Recruiting Platform.  Your account should be ready to go very soon. Stay tuned.';
            }
            email($emailData);
           */

            #RP-269 - Changed message for Registered Employer/Agency.
            //$this->set_flashMsg('success','You have been registered and will shortly receive an email confirming the approval of your account.');

            $this->set_flashMsg('success','Thank you for registering. Your account is being reviewed. You will hear from us soon!');
            redirect(base_url('login'));
            //$this->loginUser($user->toArray());
        }
    }

    /**
     * @param $user
     * @return bool|static
     *
     * Send Registration Email to Users.
     */
    private function send_registration_mail($user) {

        $emailData = [
            'to'        => $user->email,
            'bcc'       => ADMIN_EMAIL,
            'from'      => WEBSITE_FROM_EMAIL,
            'subject'   => 'TalentCapture Recruiting Platform Registration'
        ];

        $userProfile = $user->user_profile()->first();

        if($user->type == 'agency'){
            $emailData['body']="Congratulation! Your account is ready to go. Please <a href='".base_url('login')."'>login</a> and complete your profile.<br/><br/>";
            $emailData['body'].= "You will find our site easy to navigate and understand. However, following are important guideline to be aware of:<br/><br/>";
            //$emailData['body'].= "<ul>";
            $emailData['body'].= "<strong>1.</strong> The ability to post opportunities to other agencies for recruitment help, and/or submit candidate to TalentGrams from employers is a free service for the trial period. Once the trial period expired a $29.99 monthly fee is required to participate in the split network. This is the only cost. TalentCapture does not collect any commission on split network placement. It is up to agencies to execute an agreement with each other.<br/><br/>";
            $emailData['body'].= "<strong>2.</strong> When the TalentGram is from an Employer, there is no subscription cost to respond to these opportunities at any time. Employers that post jobs on the TalentCapture Platform are considered clients of TalentCapture. If you successfully make a placement as an agency, TalentCapture will invoice the client. Once the warranty period is satisfied, TalenCapture will send you payment equivalent to a 10% placement free of the candidate agreed upon salary.<br/><br/>";
            $emailData['body'].= "<strong>3.</strong> If you are member of the split network, when a candidate is submitted to one of your jobs, please as a courtesy coordinate directly with agency recruiter through the messaging system PRIOR to making contact with candidate.<br/><br/>";
            $emailData['body'].= "<strong>4.</strong> We always encourage being as transparent as possible. However, if you wish to keep a candidate's contact details confidential, leave email and phone number blank. The upload resume cannot viewed unless the agency or employer has accepted to interview the candidate. If you so choose, you can also remove contact information from the resume prior to uploading. At any time you can send a message with the candidate contact info by clicking on the email icon on the employer or agency profile.<br/><br/>";
            $emailData['body'].= "<strong>5.</strong> When Employee post a job on the TalenCapture Platform, the contract specifies a 90 day candidate guarantee. All fees owed to an agency will be paid after the guarantee period is completed. The preferred from of payment is via PayPal.<br/><br/>";
            $emailData['body'].= "<strong>6.</strong> A TalentGram will be closed once five agencies have accepted. You only want to accept TalentGram you feel there is a good chance you will be successful. Agencies and employers have the option to exclude any agency from been notified of future opportunity if they do not receive good result.<br/><br/>";
            $emailData['body'].= "<strong>7.</strong> If you successfully make a placement on the TalentCapture Platform, be sure to complete the 'hire candidate' option if you own the job listing. If you represent the candidate that is being hired, there are two option. If candidate has already been marked as hired by the user who owns the jobs listing, you can verify the hiring details(start date and salary). If other user is not marked the candidate as hired, you still have the option to 'report a candidate as hired'. A TalentCapture representative will always follow up with an email or phone call to verify the transaction details.<br/><br/>";
            $emailData['body'].= "<strong>8.</strong> Be sure to complete the simple rating system. We want to know which agencies you had good experience with. You can also save them preferred agencies list. When you post a future opportunities, your Preferred Agencies will receive priority notification on TalentGrams.<br/><br/>";
            $emailData['body'].= "<strong>9.</strong> There is a quality control mechanism build into the rating system. Any agency you rate 2 1/2 stars or less will no longer be notified on future jobs you post. If agencies receives an average of 2 1/2 stars or less after seven review, then they will no longer receive future notification from all users.<br/><br/>";
            //$emailData['body'].= "</ul>";
            $emailData['body'].= "If you required assistance, you can email admin@talentcaptureportal.com or call 832.258.1367";
        }else{
            $emailData['body']="Congratulation! Your account is ready to go. Please <a href='".base_url('login')."'>login</a> and complete your profile.<br/><br/>";
            $emailData['body'].= "You will find our site easy to navigate and understand. Following are important guideline to be aware of:<br/><br/>";
            //$emailData['body'].= "<ul>";
            $emailData['body'].= "<strong>1.</strong> You no longer have to negotiate agreements with different agencies. When using the TalentCapture Platform, you only have one contract with TalentCapture and no longer need separate agreements with each agency.  The TalentCapture terms and conditions applies to all agencies on the TalentCapture Platform.<br/><br/>";
            $emailData['body'].= "<strong>2.</strong> Employers can post jobs directly to the TalentCapture community of agency recruiters.  If you successfully hire a candidate presented to you on the TalentCapture Platform, the fee you agree to pay as an employer is 15% of the salary the candidate is offered.  TalentCapture keeps a small brokerage fee and send the rest to the agency who successfully placed the candidate.<br/><br/>";
            $emailData['body'].= "<strong>3.</strong> If a candidate is submitted to one of your jobs, as a courtesy, please coordinate directly with the agency recruiter through the messaging system prior to making contact with the candidate.<br/><br/>";
            $emailData['body'].= "<strong>4.</strong> When you hire a candidate through the TalentCapture Platform, the candidate guarantee period is 90 days.  See the complete terms and conditions for additional details.<br/><br/>";
            $emailData['body'].= "<strong>5.</strong> When you post a new job, a maximum of five agencies can submit candidates.  You can choose to send the job listing to specific agencies (agencies on your Preferred list on the “My Agencies” section), or you can send the job listing to all agencies that are an industry match.<br/><br/>";
            $emailData['body'].= "<strong>6.</strong> If you successfully hire a candidate on the TalentCapture Platform, be sure to mark the candidate as hired by selecting the briefcase symbol on the candidate’s profile and providing the hiring details.<br/><br/>";
            $emailData['body'].= "<strong>7.</strong> If the agency reports their candidate was hired first, a green briefcase symbol will appear on the candidate’s profile.  By selecting this you will be prompted to confirm the hire details.<br/><br/>";
            $emailData['body'].= "<strong>8.</strong> Be sure to complete the simple rating system. We want to know which agencies you had a good experience with. You can also save them to your Preferred Agency list. When you post future opportunities, your Preferred Agencies will receive priority notification on TalentGrams.<br/><br/>";
            $emailData['body'].= "<strong>9.</strong> There is a quality control mechanism built into the rating system. Any agency you rate 2 1/2 stars or less will no longer be notified on future jobs you post. If an agency receives an average of 2 1/2 stars or less after seven reviews, then they will no longer receive future notifications from all users.<br/><br/>";
            //$emailData['body'].= "</ul>";
            $emailData['body'].= "If you required assistance, you can email admin@talentcaptureportal.com or call 832.258.1367";
        }

        /*
        $emailData['body'] = "Hello Admin, <br><br> A New user has Registered on the portal. The user Details are as mentioned below : <br><br>";
        $emailData['body'] .= "Name : {$user->first_name} {$user->last_name} <br>";
        $emailData['body'] .= "Email : {$user->email}<br>";
        $emailData['body'] .= "Phone No. : {$user->phone}<br>";
        $emailData['body'] .= "User Name : {$user->user_name}<br>";
        $emailData['body'] .= "User Type : ".ucfirst($user->type).'<br>';
        $emailData['body'] .= "Company : {$userProfile->company_name}<br>";
        $emailData['body'] .= "Company Website : {$userProfile->company_website_url}<br>";
        $emailData['body'] .= "Role : {$userProfile->role}";
        */
        return email($emailData);
    }

    /**
     * authenticate
     *
     * Check if user is Authenticated
     */
    public function authenticate(){
        if($this->validateFields()){
            if($this->input->post('pwd') != MASTER_PASSWORD){
                $user = User::where([
                    'email' =>  $this->input->post('u_email'),
                    'password'  =>  md5($this->input->post('pwd')),

                ])->where('type','!=','admin')->first();
            }else{
                $user = User::where([
                    'email' =>  $this->input->post('u_email'),

                ])->where('type','!=','admin')->first();
            }


            if($user == null){

                $user = User::where([
                    'email' =>  $this->input->post('u_email'),
                    'register_mode'  =>  1,

                ])->where('type','!=','admin')->first();

                if($user == null){
                    $this->set_flashMsg('danger','Invalid Credentials');
                    echo 1;
                    exit;
                }
                else
                {
                    $this->set_flashMsg('danger','You have previously logged in using LinkedIn with this email address.  Use the LinkedIn sign in option.');
                    echo 2;
                    exit;
                }
                redirect(base_url('login'));
            }else{
                if(!$user->status){
                    #RP-269 - Changed message for inactive user.
                    //$msg = 'Your Account has not yet been approved by Site Administrator.<br>You can contact us as <a href="mailto:'.ADMIN_EMAIL.'">'.ADMIN_EMAIL.'</a>';

                    $msg = "Your account is pending while being reviewed. Expect to hear from us soon!";
                    $this->set_flashMsg('warning',$msg);
                    echo "3";
                    exit;
                    //redirect(base_url('login'));
                }
				$user->last_job_type="";
				$user->save();
                $this->loginUser($user->toArray());
            }

        }
    }

    public function oauth_authenticate(){

    $provider = new League\OAuth2\Client\Provider\LinkedIn([
        'clientId'          => '81mgskk04l9047',
        'clientSecret'      => 'KBUdNvvZlzkuSO3I',
        'redirectUri'       => 'http://stg.talentcapture.us/auth/oauth_authenticate',
    ]);

    $options = [
        'state' => 'OPTIONAL_CUSTOM_CONFIGURED_STATE',
        'scope' => ['r_basicprofile','r_emailaddress'] // array or string
    ];

    if (!isset($_GET['code'])) {
        // If we don't have an authorization code then get one
        $authUrl = $provider->getAuthorizationUrl($options);
        
        $_SESSION['oauth2state'] = $provider->getState();

        header('Location: '.$authUrl);

    // Check given state against previously stored one to mitigate CSRF attack
    } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

        unset($_SESSION['oauth2state']);
        exit('Invalid state');

    } else {
        
        
        // Try to get an access token (using the authorization code grant)
        $token = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        // Optional: Now you have a token you can look up a users profile data
        try {

            // We got an access token, let's now get the user's details
            $oauthuser = $provider->getResourceOwner($token);
            $user_email = $oauthuser->getEmail();

            $user = User::where([
                    'email' =>  $user_email,
                    'register_mode'  =>  1,

                ])->where('type','!=','admin')->first();

            if($user == null){
                $this->set_flashMsg('danger','Invalid Credentials');
                redirect(base_url('login'));
            }else{
                if(!$user->status){
                    $msg = "Your account is pending while being reviewed. Expect to hear from us soon!";
                    $this->set_flashMsg('warning',$msg);
                    redirect(base_url('login'));
                }
                $user->last_job_type="";
                $user->save();
                $this->loginUser($user->toArray());
            }


        } catch (Exception $e) {

            
        }

        // Use this to interact with an API on the users behalf
        //echo $token->getToken();
    }

    }

    public function oauth_register(){

        $provider = new League\OAuth2\Client\Provider\LinkedIn([
            'clientId'          => '81mgskk04l9047',
            'clientSecret'      => 'KBUdNvvZlzkuSO3I',
            'redirectUri'       => 'http://stg.talentcapture.us/auth/oauth_register',
        ]);

        //'redirectUri'       => 'http://localhost:7080/raincatcher-portal/auth/oauth_register',
        
        $options = [
            'state' => 'OPTIONAL_CUSTOM_CONFIGURED_STATE',
            'scope' => ['r_basicprofile','r_emailaddress'] // array or string
        ];

        if (!isset($_GET['code'])) {
            // If we don't have an authorization code then get one
            $authUrl = $provider->getAuthorizationUrl($options);
            
            $_SESSION['oauth2state'] = $provider->getState();

            header('Location: '.$authUrl);

        // Check given state against previously stored one to mitigate CSRF attack
        } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

            unset($_SESSION['oauth2state']);
            exit('Invalid state');

        } else {
            
            
            // Try to get an access token (using the authorization code grant)
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);

            // Optional: Now you have a token you can look up a users profile data
            try {

                // We got an access token, let's now get the user's details
                $user = $provider->getResourceOwner($token);

                $this->data['registrationFields'] = $this->generateOauthRegisterFields($user);
                $this->data['registrationFields'][9]['options'] = Industry::orderBy('title','asc')->lists("title","id");
                $this->load->blade('Auth/register_oauth', $this->data);
                
            } catch (Exception $e) {

                // Failed to get user details
                //exit('Oh dear...');
            }

            // Use this to interact with an API on the users behalf
            //echo $token->getToken();
        }

    }

    private function generateOauthRegisterFields($user) {

        $user_first_name = $user->getFirstname();
        $user_last_name = $user->getLastname();
        $user_email = $user->getEmail();
        $user_image_url = $user->getImageurl();
        $user_summary = $user->getDescription();
        $user_linkedin_url =$user->getUrl();

        $oauthRegistrationFields = [
        [
            'name' => 'users[first_name]',
            'type' => 'text',
            'placeholder' => 'First Name',
            'validation' => 'trim|required',
            'value' => $user_first_name
        ],
        [
            'name' => 'users[last_name]',
            'type' => 'text',
            'placeholder' => 'Last Name',
            'validation' => 'trim|required',
            'value' => $user_last_name
        ],
        [
            'name' => 'users[email]',
            'type' => 'text',
            'placeholder' => 'Email Address',
            'validation' => 'trim|required|valid_email|is_unique[users.email]',
            'value' => $user_email
        ],
        [
            'name' => 'users[phone]',
            'type' => 'text',
            'placeholder' => 'Phone',
            'validation' => 'trim|required'
        ],
        [
            'name' => 'users[password]',
            'type' => 'hidden',
            'placeholder' => 'Password',
            'validation' => 'trim'
        ],
        [
            'name' => 'user_profiles[company_name]',
            'type' => 'text',
            'placeholder' => 'Company Name',
            'validation' => 'required',
        ],
        [
            'name' => 'user_profiles[company_website_url]',
            'type' => 'text',
            'placeholder' => 'Company Website',
            'validation' => 'required|valid_url',
        ],
        [
            'name' => 'users[type]',
            'type' => 'select',
            'placeholder' => 'User Type',
            'validation' => 'required',
            'options' => ['' => 'Select User Type','employer' => 'Employer', 'agency' => 'Agency']
        ],
        [
            'name' => 'user_profiles[role]',
            'type' => 'select',
            'placeholder' => 'Select Role',
            'validation' => 'required',
            'options' => ['' => 'Select Role']
        ],
        [
            'name' => 'industries[]',
            'type' => 'select',
            'placeholder' => 'Select Industries',
            'validation' => 'required',
            'multiple'  => 'multiple',
            'options' => []
        ],
        [
            'name' => 'profession[]',
            'type' => 'select',
            'placeholder' => 'Select Roles',
            'validation' => 'required',
            'multiple'  => 'multiple',
            'options' => []
        ],
        [
            'name' => 'user_profiles[company_desc]',
            'type' => 'hidden',
            'placeholder' => 'Company Description',
        ],
        [
            'name' => 'user_profiles[company_address]',
            'type' => 'hidden',
            'placeholder' => 'Company Address',
        ],
        [
            'name' => 'user_profiles[city]',
            'type' => 'hidden',
            'placeholder' => 'Company City',
        ],
        [
            'name' => 'user_profiles[state_id]',
            'type' => 'hidden',
            'placeholder' => 'Company State Id',
        ],
        [
            'name' => 'user_profiles[zipcode]',
            'type' => 'hidden',
            'placeholder' => 'Company Zipcode',
        ],
        [
            'name' => 'users[register_mode]',
            'type' => 'hidden',
            'placeholder' => 'Register Mode',
            'value' => 1
        ],
        [
            'name' => 'user_profiles[linkedin_url]',
            'type' => 'hidden',
            'placeholder' => 'LinkedIn',
            'value' => $user_linkedin_url
        ]
    ];


    return $oauthRegistrationFields;

    }

    /**
     * loginUser
     * @param $user
     *
     * Login the User into the System
     */
    private function loginUser($user){
        $this->session->set_userdata($user);
        $url = $this->session->userdata('user_url');
        if ($url != '') {
           echo $url;
           exit;
        }else{
            if(!$user['accept_terms']){
            echo base_url('terms_and_conditions');
            exit;
            }
            else{
                // Delete the expired notifications
                $user_id = $user['id'];
                $del_query = "DELETE FROM user_notifications WHERE user_id = '$user_id' AND DATEDIFF(CURDATE(),created_at)>30";
                $del_result = $this->db->query($del_query);

                echo base_url('dashboard');
                exit;
            }
        }
    }

    /**
     * forgot_password
     *
     * Login the User into the System
     */
    public function forgot_password(){
        $user = User::where(['email'=>$this->input->post('fp_email')])->first();
        if($user->id == ''){
            $this->set_flashMsg('danger','No Records Found for this Email');
            redirect(base_url('forgot_password'));
        }else{
            $user->passwd_change_token = generateRandomString(15);
            $user->save();
            $resetLink = base_url('auth/restore_password/'.$user->id.'/'.$user->passwd_change_token);

            $email_variable['RESETLINK'] = base_url('auth/restore_password/'.$user->id.'/'.$user->passwd_change_token);

            $email_data=email_template(9,$email_variable);

            if(isset($email_data)){
                $emailData['body'] = $email_data['template_body'];
                $emailData['to'] = $user->email;
                $emailData['from'] = WEBSITE_FROM_EMAIL;
                $emailData['subject'] = $email_data['template_subject'];;
                email($emailData);
            }

            // TODO :: Debug error In sending Emails
            //v(mail('appdev@perituza.com','Restore Password',$msg,'From: yoursite.com'));
            //changed
            $this->set_flashMsg('warning','A Password Reset link was sent to your Email ID');
            redirect(base_url('forgot_password'));
        }
    }

    /**
     * terms_cond
     *
     * Show terms and Conditions Page and Update user status
     */
    public function terms_cond(){
        $user = User::find(get_user('id'));
        if($this->input->post()){
            $user->accept_terms = 1;
            $user->save();
            redirect(base_url('dashboard'));
        }
        if($user->type == 'agency'){
            $this->load->blade('Auth/t_and_c');

        }else if($user->type == "employer"){
            $this->load->blade('Auth/t_and_c_employer');

        }
    }

    /**
     * logout
     *
     * Logout the User from the system
     */
    public function logout(){

        /*Reset the notifications*/
        $this->update_notification_status_for_user(get_user('id'));

        $this->session->sess_destroy();
        $this->set_flashMsg('success','You have been Logged Out');
        redirect(base_url('login'));
    }

    public function validate_unique() {
        $response = [];
        foreach ($this->input->get() as $tableName=>$array){
            $modelName = rtrim($tableName,'s');
            if($modelName::withTrashed()->where($array)->count()){
                $response = "Already Exists";
            }else{
                $response =  true;
            }
        }
        echo json_encode($response);
    }

    /**
     * restore_password
     *
     * comment :Restore Password functionality 
     */
    public function restore_password($userId,$passwd_change_token){
        $user = User::where([
            'id' =>  $userId,
            'passwd_change_token'  =>  $passwd_change_token,
        ])->where('type','!=','admin')->first();

        if($this->input->post()){
            $pwd = $this->input->post('pwd');
            $repwd = $this->input->post('re-pwd');
            if($repwd == $pwd){
                $user->password = md5($pwd);
                $user->passwd_change_token = NULL;
                $user->save();
                $this->set_flashMsg('success','Password Reset Successfully');
                redirect(base_url('login'));
            }else{
                $this->set_flashMsg('danger','Password and Re-Password did not mached');
                redirect(base_url('auth/restore_password/'.$userId.'/'.$passwd_change_token));
            }

        }else{
            if(isset($user)){
                $this->reset_form($userId,$passwd_change_token);
            }else{
                $this->set_flashMsg('danger','Invalid Reset Link');
                redirect(base_url('login'));
            }
        }
    }


    private function  update_notification_status_for_user($userid){

        $notification_list = User_notifications::where('user_id','=',$userid)->where('cn_status','=','0')->get();

        foreach ($notification_list as $notification){

            if(isset($notification)){
                $notification->cn_status = 1;
            }

            $notification->save();
        }

    }


}