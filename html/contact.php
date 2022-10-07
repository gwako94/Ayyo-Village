<?php
// Configure your Subject Prefix and Recipient here
$subjectPrefix = '[Contact via website]';
$emailTo       = 'your@mailaddress.com';

$errors = array(); // array to hold validation errors
$data   = array(); // array to pass back data

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $name		= isset($_POST["name"]) ? stripslashes(trim($_POST['name'])) : NULL;
    $surname	= isset($_POST["surname"]) ? stripslashes(trim($_POST['surname'])) : NULL;
    $phone		= isset($_POST["phone"]) ? stripslashes(trim($_POST['phone'])) : NULL;
    $email		= isset($_POST["email"]) ? stripslashes(trim($_POST['email'])) : NULL;
    $message	= isset($_POST["message"]) ? stripslashes(trim($_POST['message'])) : NULL;

    if (empty($name)) {
        $errors['name'] = 'Name is required.';
    }
    
    if (empty($surname)) {
        $errors['surname'] = 'Surname is required.';
    }
    
    if (empty($phone)) {
        $errors['phone'] = 'Phone is required.';
    }

    if (!preg_match('/^[^0-9][A-z0-9._%+-]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/', $email)) {
        $errors['email'] = 'Email is invalid.';
    }

    if (empty($message)) {
        $errors['message'] = 'Message is required.';
    }

    // if there are any errors in our errors array, return a success boolean or false
    if (!empty($errors)) {
        $data['success'] = false;
        $data['errors']  = $errors;
    } else {
        $subjectTitle = "$subjectPrefix";
        $body    = '
            <strong>Name: </strong>'.$name.'<br />
            <strong>Surname: </strong>'.$surname.'<br />
            <strong>Phone: </strong>'.$phone.'<br />
            <strong>Email: </strong>'.$email.'<br />
            <strong>Message: </strong>'.nl2br($message).'<br />
        ';

        $headers  = 'MIME-Version: 1.1' . PHP_EOL;
        $headers .= 'Content-type: text/html; charset=UTF-8' . PHP_EOL;
        $headers .= "From: $name $surname <$email>" . PHP_EOL;
        $headers .= "Return-Path: $emailTo" . PHP_EOL;
        $headers .= "Reply-To: $email" . PHP_EOL;
        $headers .= "X-Mailer: PHP/". phpversion() . PHP_EOL;

        mail($emailTo, $subjectTitle, $body, $headers);

        $data['success'] = true;
        $data['message'] = 'Congratulations. Your message has been sent successfully';
    }

    // return all our data to an AJAX call
    echo json_encode($data);
}
?>