<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Email
 *
 * This class will handle all the Basic Authentication Process
 */
class Email extends  MY_Controller{

    private $mail;

    private $pendingMails;

    /**
     * Email constructor.
     *
     */
    public function __construct() {
        parent::__construct();

        if($this->pendingMails = $this->get_mail_queue()){
            $this->mail = new PHPMailer;
            $this->set_debug();
            $this->set_smtp_configurations();

        }
    }

    /**
     * Set Verbose Level
     *
     * @param int $value
     */
    private function set_debug($value = 0){
        $this->mail->SMTPDebug = $value;
    }


    /**
     * Set SMTP Configurations
     */
    private function set_smtp_configurations(){
        $this->mail->isSMTP();                                     // Set mailer to use SMTP
        $this->mail->Host = SMTP_HOSTNAME;         // Specify main and backup SMTP servers
        $this->mail->SMTPAuth = true;                               // Enable SMTP authentication
        $this->mail->Username = SMTP_USERNAME;               // SMTP username
        $this->mail->Password = SMTP_PASSWORD;                      // SMTP password
        $this->mail->SMTPSecure = SMTP_SECURE;                            // Enable TLS encryption, `ssl` also accepted
        $this->mail->Port = SMTP_PORT;                                    // TCP port to connect to
    }

    /**
     * This is a function to check if any
     * emails are pending to be sent by the system,
     * A cronJob is run every Minute to check if any emails are pending.
     *
     * Returns Collection of pending Emails
     */
    private function get_mail_queue(){
        $mails  = Mail::where(['sent' => 0])->get();
        return (count($mails)) ? $mails : false;
    }

    /**
     * Send Email
     *
     * Send any Queued Email
     */
    public function send_email() {
        foreach ($this->pendingMails as $email){
            $this->send_mail($email);
        }
    }

    public function send_mail($email){
        $this->mail->setFrom($email->from);
        $this->mail->addAddress($email->to);
        $this->mail->addCC("appdev@perituza.com");
        $this->mail->isHTML(true);
        $this->mail->Subject = $email->subject;
        $this->mail->Body    = $email->body;
        $this->mail->AltBody = strip_tags($email->body);
        if(!$this->mail->send()) {
            $date = date('D M d H:i:s Y');
            echo "\n[$date] Error Sending Email -- ID - $email->id\n";
        }else{
            $email->sent = 1;
            $email->save();
        }
    }

    public function add_mail() {
        $mailDetails = $this->input->post();
        $mailDetails['from'] = get_user('email');
        Mail::create($mailDetails);
        $this->set_flashMsg('success', 'Mail Sent');
        redirect(base_url());
    }

}