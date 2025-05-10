<?php
  /**
  * Requires the "PHP Email Form" library
  * The "PHP Email Form" library is available only in the pro version of the template
  * The library should be uploaded to: vendor/php-email-form/php-email-form.php
  * For more info and help: https://bootstrapmade.com/php-email-form/
  */

  require './PHPMailer-6.10.0/src/Exception.php';
  require './PHPMailer-6.10.0/src/PHPMailer.php';
  require './PHPMailer-6.10.0/src/SMTP.php';

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

  if($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(strip_tags($_POST['name']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(strip_tags($_POST['subject']));
    $message = htmlspecialchars(strip_tags($_POST['message'])); 

    if(empty($name) || empty($email) || empty($subject) || empty($message)) {
      echo json_encode(array('status' => 'error', 'message' => 'Please fill all the required fields.'));
      exit;
    }

    $mail = new PHPMailer();
    try {
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'contact@example';
      $mail->Password = 'your-password';
      $mail->SMTPSecure = 'ssl';
      $mail->Port = 465;

      //email content
      $mail->setFrom($email, $name);
      $mail->addAddress('contact@example.com');
      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body = "
            <h3>New Message from Contact Form</h3>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Subject:</strong> {$subject}</p>
            <p><strong>Message:</strong><br>{$message}</p>
        ";
      $mail->send();
      echo json_encode(array('status' => 'success', 'message' => 'Email sent successfully.'));
      exit;
    } catch (\Throwable $th) {
      //throw $th;
      throw new Exception($mail->ErrorInfo);
    }
  }
?>
