<?php
require ('class/class.phpmailer.php');

function sendMail($nom,$prenom,$phone,$email,$textarea){




	$mail = new PHPMailer;


	$mail->IsSMTP();								//Sets Mailer to send message using SMTP
	$mail->Host = 'smtp.gmail.com';



	//$mail->Host = 'tls://smtp.gmail.com:587';






		//Sets the SMTP hosts of your Email hosting, this for Godaddy
	$mail->Port = 587;//465;//587							//Sets the default SMTP server port
	$mail->SMTPAuth = true;							//Sets SMTP authentication. Utilizes the Username and Password variables
	$mail->Username =
	'amzianx@gmail.com';					//Sets SMTP username
	$mail->Password = 'benfqrice.01.benfqrice.01.benfqrice.01.';					//Sets SMTP password
	//$mail->SMTPSecure = 'ssl';
	$mail->SMTPSecure = 'tls';						//Sets connection prefix. Options are "", "ssl" or "tls"
	$mail->From = 'amzianx@gmail.com';					//Sets the From email address for the message
	$mail->FromName = 'SODIVIAS';				//Sets the From name of the message
	$mail->AddAddress("imzoughene@outlook.com", 'SODIVIAS');		//Adds a "To" address
	$mail->WordWrap = 50;							//Sets word wrapping on the body of the message to a given number of characters
	$mail->IsHTML(true);
	//$mail->AddEmbeddedImage("images\header.jpg", "my-attach");
	$message = '
		<h3 align="center">E-mail</h3>
		<table border="1" width="100%" cellpadding="5" cellspacing="5">

			<tr>
				<td width="30%">JOURNNEE : </td>
				<td width="70%">'.date('d/m/Y').'</td>
			</tr>
			<tr>
				<td width="30%">Nom</td>
				<td width="70%">'.$nom.'</td>
			</tr>
      <tr>
        <td width="30%">Prénom</td>
        <td width="70%">'.$prenom.'</td>
      </tr>
      <tr>
        <td width="30%">Email</td>
        <td width="70%">'.$email.'</td>
      </tr>
      <tr>
        <td width="30%">Téléphone</td>
        <td width="70%">'.$phone.'</td>
      </tr>
      <tr>
        <td width="30%">Message</td>
        <td width="70%">'.$textarea.'</td>
      </tr>

		</table>
	';
	$message_div = "";						//Sets message type to HTML
	//$mail->AddAttachment('upload\Plateforme de gestion de distribution.pdf');

						//Adds an attachment from a path on the filesystem
	$mail->Subject = 'SODIVIAS';				//Sets the Subject of the message
	$mail->Body = $message;
						//An HTML or plain text message body
	//echo $mail->Send();
	if(!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message has been sent';
    }						//Send an Email. Return true on
	}

//sendMail_daily();

if(isset($_POST['nom']) && $_POST['nom']!="" &&
   isset($_POST['prenom']) && $_POST['prenom']!="" &&
   isset($_POST['phone']) && $_POST['phone']!="" &&
   isset($_POST['email']) && $_POST['email']!="" &&
   isset($_POST['textarea']) && $_POST['textarea'] != "")
 {

       $nom =  $_POST['nom'];
       $prenom = $_POST['prenom'];
       $phone = $_POST['phone'];
       $email = $_POST['email'];
       $textarea =$_POST['textarea'];
       sendMail($nom,$prenom,$phone,$email,$textarea);
 }


?>
<script type="text/javascript">
  window.location.assign("index.html");
</script>
