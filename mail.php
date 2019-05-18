<?php
session_start();
if(isset($_POST['mail'])){
    // antiflood controle
    if (!empty($_SESSION['antiflood']))
    {
        $seconde = 20; // 20 seconds anti-flooding
        $tijd = time() - $_SESSION['antiflood'];
        if($tijd < $seconde)
            $antiflood = 1;
    }

    // EDIT THE 2 LINES BELOW AS REQUIRED
    $email_to = "iactivem@iactivem.com.ua";
    $email_subject = "OAPAPA";      

    // validation expected data exists
    if(	!isset($_POST['name']) ||
		!isset($_POST['email']) ||
        !isset($_POST['phone']) ||

        !isset($_POST['message'])) {
		header("Location: index.html#error");
    }

    $first_name = $_POST['name']; // required
    $email_from = $_POST['email']; // required
    $telephone = $_POST['phone']; // not required

    $comments = $_POST['message']; // required

    $error_message = "";
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
  if(!preg_match($email_exp,$email_from)) {
    $error_message = "error";
  }
    $string_exp = "/^[A-Za-z .'-]+$/";
  if(!preg_match($string_exp,$first_name)) {
    $error_message = "error";
  }
  if(strlen($comments) < 2) {
    $error_message = "error";
  }
  if(strlen($error_message) > 0) {
    header("Location: index.html#error");
  }

    function clean_string($string) {
      $bad = array("content-type","bcc:","to:","cc:","href");
      return str_replace($bad,"",$string);
    }

	$datum = date('d/m/Y H:i:s');

	$email_message = "===================================================\n";
	$email_message .= "From" . $_SERVER['HTTP_HOST'] . "\n";
	$email_message .= "===================================================\n\n";
    $email_message .= "Name: ".clean_string($first_name)."\n";
    $email_message .= "E-mail: ".clean_string($email_from)."\n";
    $email_message .= "Tel: ".clean_string($phone)."\n";

    $email_message .= "Message: ".clean_string($comments)."\n\n";
    $email_message .= "Send on " . $datum . " from IP address " . $_SERVER['REMOTE_ADDR'] . "\n\n";
	$email_message .= "===================================================\n";
	$email_message .= "Tech support:\n";
	$email_message .= "===================================================\n\n";
	$email_message .= $_SERVER['HTTP_USER_AGENT'];

// create email headers
$headers = 'From: '.$email_from."\r\n".
'Reply-To: '.$email_from."\r\n" .
'X-Mailer: PHP/' . phpversion();
 if (($error_message == "") && ($antiflood == "")) 
  {
	$_SESSION['antiflood'] = time();
	  @mail($email_to, $email_subject, $email_message, $headers);
	  header("Location: index.html#succes");
  }
  else
  {
      header("Location: index.html#error");
  }  
} 
?>