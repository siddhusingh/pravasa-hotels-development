<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;
// // Load Composer's autoloader
// require 'vendor/autoload.php';


class MY_Controller extends CI_Controller {

    protected $login_url;

    public function __construct(){
        parent::__construct();
        
        /*set default timezone*/
        date_default_timezone_set('Asia/Kolkata');

    }


    // public function login_function(){

    //     include_once APPPATH . "libraries/vendor/autoload.php";

    //     $google_client = new Google_Client();

    //     $google_client->setClientId('408172146279-o7rar8l615eublu8qqtig0o5nti7mk8l.apps.googleusercontent.com');
    //     $google_client->setClientSecret('cDbcvEQ-m1z8eaD2NAEqsbb8');

    //     $google_client->setRedirectUri('http://dev.atzean.com/iqroots/home/login_function');

    //     $google_client->addScope('email');
    //     $google_client->addScope('profile');


    //     if(isset($_GET['code'])){

    //         $token = $google_client->fetchAccessTokenWithAuthCode($_GET['code']);

    //         if(!isset($token['error'])){

    //             $google_client->setAccessToken($token['access_token']);
    //             $google_client->SetTokenExpireIn($token['expires_in']);
    //             $this->session->set_userdata('access_token',$token['access_token']);

    //             $google_service = new Google_Service_Oauth2($google_client);

    //             $data = $google_service->userinfo->get();

    //             $current_datetime = date('Y-m-d H:i:s');

    //             /*if student already exist*/
    //             if($registered_data = $this->Comman_model->get_single_record('student',['email'=>$data['email']])){

    //                 // already register
    //                 $user_data = array(
    //                     'f_name' => $data['given_name'],
    //                     'l_name' => $data['family_name'],
    //                     'email' => $data['email'],
    //                     'profile' => $data['picture'],
    //                     'updated_at' => $current_datetime,
    //                 );

    //                 $this->Comman_model->UpdateRecord('student',$user_data,['email'=>$data['email']]);

    //                 $session_data = [
    //                     'student_id' => $registered_data->id,
    //                     'logged_in' => true,
    //                 ];

    //                 $this->session->set_userdata('student_session',$session_data);
                 
    //             }else{

    //                 // new user

    //                 /*get ref amount updated by admin*/
    //                 $refAmount = $this->Comman_model->get_single_record('referral_tbl',['id'=>1])->referral_amount;

    //                 /*if student comes from referral then*/
    //                 if(!empty($this->session->userdata('refStudentId'))){


    //                     /*for referral of student update wallat*/
    //                     $refStudentId  = $this->session->userdata('refStudentId');


    //                     $user_data = array(

    //                         'f_name' => $data['given_name'],

    //                         'l_name' => $data['family_name'],

    //                         'email' => $data['email'],

    //                         'profile' => $data['picture'],

    //                         'created_at' => $current_datetime,

    //                     );


    //                     /*start code for update wallet table*/

    //                     /*check reff id already exist or not*/
    //                     $isStudentAlreadyRef = $this->Comman_model->get_single_record('wallet_tbl',['ref_student_id'=>$refStudentId]);


    //                     /*check referral id already exist in wallet*/
    //                     if (!empty($isStudentAlreadyRef)) {

    //                         /*then update amount*/
    //                         $updateRefArr = [
    //                                         'wallet_amount'=>$isStudentAlreadyRef->wallet_amount+$refAmount
    //                                     ];             

    //                         $updateResp = $this->Comman_model->UpdateRecord('wallet_tbl',$updateRefArr,['ref_student_id'=>$refStudentId]);


    //                         /*redirect on home page if updation record failed*/
    //                         if (!isset($updateResp)) {

    //                             /*redirect on failed*/
    //                             return redirect(base_url());

    //                         }else{

    //                             /*destroy session on success udpdate*/
    //                             $this->session->unset_userdata('refStudentId');

    //                         }



    //                     }else if(empty($isStudentAlreadyRef)){

    //                         /*else insert new record normally*/
    //                         $insertRefArr = [
    //                                         'wallet_amount'=>$isStudentAlreadyRef->wallet_amount+$refAmount,
    //                                         'ref_student_id'=>$refStudentId
    //                                      ]; 

    //                         $insertResp = $this->Comman_model->insertData('wallet_tbl',$insertRefArr);
                            
    //                         /*redirect on home page if insertion new record failed*/
    //                         if (!isset($insertResp)) {

    //                             /*redirect on failed*/
    //                             return redirect(base_url());
                                
    //                         }else{

    //                             /*destroy session on success udpdate*/
    //                             $this->session->unset_userdata('refStudentId');
 
    //                         }


    //                     }

    //                     /*end code for update insert in wallet table*/


    //                 }else if($this->session->userdata('refIns_id')){



    //                     $user_data = array(

    //                         'f_name' => $data['given_name'],

    //                         'l_name' => $data['family_name'],

    //                         'email' => $data['email'],

    //                         'profile' => $data['picture'],

    //                         'created_at' => $current_datetime,

    //                     );
 
    //                     /*start code for udpate insert in wallet tabel*/


    //                 /*for referral of instructor update wallat*/
    //                 $refIns_id  = $this->session->userdata('refIns_id');

                  

    //                 /*start code for wallet table to update or insert new record*/

    //                     $isInsAlreadyRef = $this->Comman_model->get_single_record('wallet_tbl',['ref_ins_id'=>$refIns_id]);


    //                     /*if referral id already exist in wallet tbl*/
    //                     if (!empty($isInsAlreadyRef)) {

    //                         /*then update*/
                            
    //                         $updateArr = [
    //                                         'wallet_amount'=>$isInsAlreadyRef->wallet_amount+$refAmount,
    //                                     ];                         



    //                         $updateResp = $this->Comman_model->UpdateRecord('wallet_tbl',$updateArr,['ref_ins_id'=>$refIns_id]);


    //                         /*if record not update*/
    //                         if (!isset($updateResp)) {

    //                             return redirect(base_url());

    //                         }


    //                     /*else proceed to save new record*/    
    //                     }else if(empty($isInsAlreadyRef)){

    //                         /*else insert new record normally*/
                         

    //                         $isInsAlreadyRef = $this->Comman_model->get_single_record('wallet_tbl',['ref_ins_id'=>$refIns_id]);

    //                         $insertRefArr = [
    //                                         'wallet_amount'=>$isInsAlreadyRef->wallet_amount+$refAmount,
    //                                         'ref_ins_id'=>$refIns_id
    //                                      ]; 

    //                         $insertResp = $this->Comman_model->insertData('wallet_tbl',$insertRefArr);

    //                         /*if insertion in wallet tbl failed*/
    //                         if (!isset($insertResp)) {

    //                             return redirect(base_url());
                                
    //                         }else{

    //                             /*destroy session on success udpdate*/
    //                             $this->session->unset_userdata('refStudentId');

    //                         }

    //                     }
                


    //                     /*end code for udpate insert in wallet tabel*/



    //                 }else{
                        
    //                     /*else proceed to normal*/
    //                     $user_data = array(

    //                         'f_name' => $data['given_name'],
    //                         'l_name' => $data['family_name'],
    //                         'email' => $data['email'],
    //                         'profile' => $data['picture'],
    //                         'created_at' => $current_datetime,

    //                     );


    //                 }


    //                 $insertResponse = $this->Comman_model->insertData('student',$user_data);

    //                 if(!empty($insertResponse)) {

    //                     $session_data = [
    //                         'student_id' => $insertResponse,
    //                         'logged_in' => true,
    //                     ];

    //                     $this->session->set_userdata('student_session',$session_data);

    //                     redirect('student');

    //                 }


    //             }


    //             }

    //         }
      

    //     if(!$this->session->userdata('access_token')){

    //       $this->session->set_userdata('login_google_url',$google_client->createAuthUrl());
           
    //     }

        
    //     // print_r($this->session->userdata('student_session'));
    //     // die();

    //     if($this->session->userdata('student_session')){ 

    //         redirect('student');
    //     }

    //     if(!empty($_GET['error'])){

    //         return redirect('/');
    //     }



    // }


    // /*facebook login*/

    // public function fblogin()
    // {   
    //     $data['user'] = array();
    //     if ($this->facebook->is_authenticated()) {
    //         // User logged in, get user details
    //         $user = $this->facebook->request('get', '/me?fields=id,picture,email,first_name,last_name,gender,middle_name,name');

    //         if (!isset($user['error'])) {
    //           $data = $user;
               

    //             $current_datetime = date('Y-m-d H:i:s');

    //             if($registered_data = $this->Comman_model->get_single_record('student',['id'=>$data['id']])){
    //                 // already register
    //                   $user_data = array(
    //                     'f_name' => $data['first_name']." ".$data['middle_name'],
    //                     'l_name' => $data['last_name'],
    //                     // 'email' => $data['email'],
    //                     'profile' => $data['picture']['data']['url'],
    //                     'updated_at' => $current_datetime,
    //                 );

    //                 $this->Comman_model->UpdateRecord('student',$user_data,['id'=>$data['id']]);


    //                 $session_data = [
    //                     'student_id' => $registered_data->id,
    //                     'logged_in' => true,
    //                 ];

    //                 $this->session->set_userdata('student_session',$session_data);

    //             }else{


    //                 /*if student comes from referral then*/
    //                 if(!empty($this->session->userdata('refStudentId'))){

    //                     $refStudentId = $this->session->userdata('refStudentId');

    //                     $user_data = array(

    //                         'f_name' => $data['first_name']." ".$data['middle_name'],

    //                         'l_name' => $data['last_name'],

    //                         // 'email' => $data['email'],

    //                         'profile' => $data['picture']['data']['url'],

    //                         'ref_student_id' => $refStudentId,

    //                         'created_at' => $current_datetime,

    //                     );



    //                 }else if($this->session->userdata('refIns_id')){

    //                     $refInsId = $this->session->userdata('refIns_id');

    //                     $user_data = array(

    //                         'f_name' => $data['first_name']." ".$data['middle_name'],

    //                         'l_name' => $data['last_name'],

    //                         // 'email' => $data['email'],

    //                         'profile' => $data['picture']['data']['url'],

    //                         'ref_ins_id' => $refInsId,

    //                         'created_at' => $current_datetime,

    //                     );


    //                 }else{
                        
    //                     /*else proceed to normal*/

    //                     $user_data = array(

    //                         'f_name' => $data['first_name']." ".$data['middle_name'],

    //                         'l_name' => $data['last_name'],

    //                         // 'email' => $data['email'],

    //                         'profile' => $data['picture']['data']['url'],

    //                         'created_at' => $current_datetime,

    //                     );

    //                 }

    //                 /*insert data into database*/
    //                 $inserted_id = $this->Comman_model->insertData('student',$user_data);

    //                 /*ready a session array*/
    //                 $session_data = [
    //                     'student_id' => $inserted_id,
    //                     'logged_in' => true,
    //                 ];

    //                 /*set a student session*/
    //                 $this->session->set_userdata('student_session',$session_data);
 
    //              }


                
    //             if($this->session->userdata('student_session')){ 
    //                 /*if student session set*/ 

    //                 redirect('student');

    //             }else{
    //                 /*else redirect on home page*/

    //                 // $data['login_button_url'] = $this->login_url;
    //                 // $this->load->view('Login_with_google/login');

    //                 redirect('home');
    //             }

    //         }

    //     }else {
            
    //         redirect('home');

    //     }
    // }


    /*generates random number with lenght*/
    function randomWithLength($length){

        $number = '';
        for ($i = 0; $i < $length; $i++){
            $number .= rand(0,9);
        }

        return (int)$number;

    }


    public function sendMobileSms($params) {
        $mobileNumber= $params['to']; /*Separate mobile no with commas */
        $message= $params['message']; /* message */
        $senderId= "abcd"; /* Sender ID */
        $serverUrl="msg.msgclub.net";
        $authKey= ""; /* Authentication key (get from sms service provider)*/
        $route="1";
        $this->sendsmsGET($mobileNumber,$senderId,$route,$message,$serverUrl,$authKey);
    }

    public function sendsmsGET($mobileNumber,$senderId,$routeId,$message,$serverUrl,$authKey)
    {
        $route = "default";
        $getData = 'mobileNos='.$mobileNumber.'&message='.urlencode($message).'&senderId='.$senderId.'&routeId='.$routeId;
        /* API URL */
        $url="http://".$serverUrl."/rest/services/sendSMS/sendGroupSms?AUTH_KEY=".$authKey."&".$getData;
        /* init the resource */
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ));
        /* get response */
        $output = curl_exec($ch);
        /* Print error if any */
        if(curl_errno($ch))
        {
         echo 'error:' . curl_error($ch);
        }
        curl_close($ch);
        return $output;
    }


    public function do_encrypt($simple_string){

        if(isset($simple_string)){

        // Store the cipher method
        $ciphering = "AES-128-CTR";
          
        // Use OpenSSl Encryption method
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
          
        // Non-NULL Initialization Vector for encryption
        $encryption_iv = '1234567891011121';
          
        // Store the encryption key
        $encryption_key = "MyEncryptionKey@123";
          
        // Use openssl_encrypt() function to encrypt the data
        $encryption = openssl_encrypt($simple_string, $ciphering,$encryption_key, $options, $encryption_iv);
          
        return  $encryption; 
        }else{
            redirect(base_url());
        }
    }

    public function do_decrypt($simple_string){

        if(isset($simple_string)){

        // Store the cipher method
        $ciphering = "AES-128-CTR";
          
        // Use OpenSSl Encryption method
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
          
        // Non-NULL Initialization Vector for encryption
        $decryption_iv = '1234567891011121';
          
        // Store the encryption key
        $decryption_key = "MyEncryptionKey@123";
          
        // Use openssl_decrypt() function to encrypt the data
        $decryption = openssl_decrypt($simple_string, $ciphering,$decryption_key, $options, $decryption_iv);
          
        return  $decryption; 
        }else{
            redirect(base_url());
        }
    }


    public function send_email($to,$message,$subject,$from_title=''){
        
        
        if($to && $message && $subject){
            
            $config['protocol'] = 'mail';
            $config['smtp_host'] = 'dev.atzean.com'; 
            $config['smtp_port'] = '456';
            $config['smtp_user'] = 'info@dev.atzean.com';
            $config['smtp_pass'] = 'OVMQ.[],?DTz';
            $config['mailtype'] = 'html';
            $config['charset'] = 'utf-8';
            $config['smtp_crypto'] = 'tls';
            $config['wordwrap'] = TRUE;
            $config['newline'] = "\r\n";

            // $config=array(
            //     'charset'=>'utf-8',
            //     'wordwrap'=> TRUE,
            //     'mailtype' => 'html',
            //     'priority'=>1,
            // );

            $this->email->initialize($config);
            $this->email->to($to);
            
            if($from_title){
                $this->email->from("FostreBright", $from_title);                
            }else{
                $this->email->from("FostreBright");     
            }
            
            $this->email->subject($subject);
            $this->email->message($message);
            
            if($this->email->send())
            {
                return true;
            }else{
                return true;
            }
            
        }    
    }
    
    
     
  public function downloadFile($fileName=NULL,$filePath=NULL) {   
   if ($fileName) {  
    if (file_exists ( $filePath )) {
     $data = file_get_contents ( $filePath );
     force_download ( $fileName, $data );
    } else {
     redirect ( base_url () );
    }
   }
   } 
    


    /*=====delete data =====*/

    function deleteData($table,$where)
    {
     
        if(isset($table) && !empty($where)){

            if($this->Comman_model->Deletedata($table,$where))
            {
                return 1;
            }else{

                return 0;
            }    
        }

    }


    /*check exist*/
    public function checkExist($table,$where,$column){

       $responseArr = $this->Comman_model->checkExistInDb($table,$where,$column);

       if(!empty($responseArr)){

        return $responseArr->$column;

       }else{
        
        return false;

       }

    }


    // public function sendEmailPhp(){

    //     ob_start();
    //     $email_available = $this->Dbmodel->select_where('company',['company_email'=>$_POST['email_id'],'id!='=>$_POST['id'],'is_delete'=>0]);
    //     if(empty($email_available)){
    //         $username_available = $this->Dbmodel->select_where('company',['username'=>$_POST['username'],'id!='=>$_POST['id'],'is_delete'=>0]);
        
    //         if(empty($username_available)){
    //                 $data['password']=$_POST['password'];
    //                 $data['username'] = $_POST['username'];
    //                 $msg = $this->load->view('email_template/invite_mail',$data, true);
    //                 //$msg=$this->convert_to_utf8($msg);
    //                 mb_convert_encoding($msg, 'UTF-8');
    //                 $to= $_POST['email_id'];
    //                 $subject="Horizon new user";
    //                 $from = "shwetapdeshmukh@gmail.com";
                    
    //                 $mail = new PHPMailer(true);
    //                 $mail->SMTPDebug = 2;
    //                 $mail->isSMTP();                         
    //                 $mail->Host = "smtp.gmail.com";
    //                 $mail->SMTPAuth = true;                          
    //                 $mail->Username = "shwetapdeshmukh1@gmail.com";                 
    //                 $mail->Password = "7620024257";                      
    //                 $mail->SMTPSecure = 'tls';                           
    //                 $mail->Port = 587;                                   
                    
    //                 $mail->From = "shwetapdeshmukh1@gmail.com";
    //                 $mail->FromName = "Horizon Contract";
    //                 $mail->setFrom("shwetapdeshmukh1@gmail.com", "Horizon Contract");

    //                 $mail->addAddress($to);                 
    //                 $mail->isHTML(true);                    
    //                 $mail->Subject = $subject;
    //                 $mail->Body = $msg;
                    
    //                 if($mail->Send()){
    //                     echo json_encode(['status'=>true,'msg'=>'Invite mail send']);
    //                     ob_end_flush();
    //                 }
    //         }
    //         else{
    //             echo json_encode(['msg'=>'Username already exist']);
    //         }
    //     }
    //     else{
    //         echo json_encode(['msg'=>'Email already exist']);
    //     }


    // }



}
