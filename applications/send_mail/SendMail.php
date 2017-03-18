<?php
    namespace fw_Gunicorn\applications\send_mail;


    use fw_Gunicorn\applications\send_mail\library\PHPMailer;

    class SendMail{
        private $mailer;

        public function __construct(){
            if(!defined('SERVER_SMTP') || !defined('USER_SMTP') || !defined('PASS_SMTP') || !defined('PORT_SMTP') ||
                !defined('TYPE_SECURE_SMTP')){
                die('To use the application of sending electronic mail must define the following constants in the file 
                settings.php: SERVER_SMTP, PORT_SMTP, TYPE_SECURE_SMTP, USER_SMTP, PASS_SMTP');
            }

            $this->mailer = new PHPMailer();
            $this->configurePHPMAiler();
        }
        private function configurePHPMAiler(){
            $this->mailer->isSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $this->mailer->SMTPDebug = 0;

            $this->mailer->Debugoutput = 'html';

            $this->mailer->Host = SERVER_SMTP;

            $this->mailer->Port = PORT_SMTP;

            $this->mailer->SMTPSecure = TYPE_SECURE_SMTP;

            $this->mailer->SMTPAuth = true;

            $this->mailer->Username = USER_SMTP;

            $this->mailer->Password = PASS_SMTP;

            if(defined('REPLY_TO'))
                $this->mailer->addReplyTo();
        }
        public function send(Array $Address, $subject, $message, Array $cc = array()){
            foreach ($Address as $value){
                $this->mailer->addAddress($value[0], $value[1]);
                $this->mailer->Subject = $subject;
                $this->mailer->msgHTML($message);

                if (!$this->mailer->send()) {
                    echo "Mailer Error: " . $this->mailer->ErrorInfo;
                } else {
                    $this->mailer->clearAllRecipients();
                    $this->mailer->clearAddresses();
                }
            }
        }
    }