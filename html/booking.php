<?php
// Configure your Subject Prefix and Recipient here
$subjectPrefix = '[Booking via website]';
$emailTo       = 'your@mailaddress.com';

$errors = array(); // array to hold validation errors
$data   = array(); // array to pass back data

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $room           = isset($_POST["room"]) ? stripslashes(trim($_POST['room'])) : NULL;
    $checkin        = isset($_POST["checkin"]) ? stripslashes(trim($_POST['checkin'])) : NULL;
    $checkout       = isset($_POST["checkout"]) ? stripslashes(trim($_POST['checkout'])) : NULL;
    $adults         = isset($_POST["adults"]) ? stripslashes(trim($_POST['adults'])) : NULL;
    $childrens      = isset($_POST["childrens"]) ? stripslashes(trim($_POST['childrens'])) : NULL;
    $name           = isset($_POST["name"]) ? stripslashes(trim($_POST['name'])) : NULL;
    $surname        = isset($_POST["surname"]) ? stripslashes(trim($_POST['surname'])) : NULL;
    $email          = isset($_POST["email"]) ? stripslashes(trim($_POST['email'])) : NULL;
    $phone          = isset($_POST["phone"]) ? stripslashes(trim($_POST['phone'])) : NULL;
    $address1       = isset($_POST["address1"]) ? stripslashes(trim($_POST['address1'])) : NULL;
    $address2       = isset($_POST["address2"]) ? stripslashes(trim($_POST['address2'])) : NULL;
    $city           = isset($_POST["city"]) ? stripslashes(trim($_POST['city'])) : NULL;
    $country        = isset($_POST["country"]) ? stripslashes(trim($_POST['country'])) : NULL;
    $requirements   = isset($_POST["requirements"]) ? stripslashes(trim($_POST['requirements'])) : NULL;

    if (empty($checkin)) {
        $errors['checkin'] = 'Check in is required.';
    }
    
    if (empty($checkout)) {
        $errors['checkout'] = 'Check out is required.';
    }
    
    if (empty($adults)) {
        $errors['adults'] = 'Adults is required.';
    }
    
    if (empty($name)) {
        $errors['name'] = 'Name is required.';
    }
    
    if (empty($surname)) {
        $errors['surname'] = 'Surname is required.';
    }
    
    if (!preg_match('/^[^0-9][A-z0-9._%+-]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/', $email)) {
        $errors['email'] = 'Email is invalid.';
    }
    
    if (empty($phone)) {
        $errors['phone'] = 'Phone is required.';
    }

    // if there are any errors in our errors array, return a success boolean or false
    if (!empty($errors)) {
        $data['success'] = false;
        $data['errors']  = $errors;
    } else {
        $subjectTitle = "$subjectPrefix";
        $body    = '
            <strong>ROOM: </strong>'.$room.'<br />
            <strong>CHECK IN: </strong>'.$checkin.'<br />
            <strong>CHECK OUT: </strong>'.$checkout.'<br />
            <strong>ADULTS: </strong>'.$adults.'<br />
            <strong>CHILDRENS: </strong>'.$childrens.'<br />
            <strong>NAME: </strong>'.$name.'<br />
            <strong>SURNAME: </strong>'.$surname.'<br />
            <strong>EMAIL ADDRESS: </strong>'.$email.'<br />
            <strong>PHONE: </strong>'.$phone.'<br />
            <strong>ADDRESS 1: </strong>'.$address1.'<br />
            <strong>ADDRESS 2: </strong>'.$address2.'<br />
            <strong>CITY: </strong>'.$city.'<br />
            <strong>COUNTRY: </strong>'.$country.'<br />
            <strong>SPECIAL REQUIREMENTS: </strong>'.$requirements.'<br />
        ';

        $headers  = 'MIME-Version: 1.1' . PHP_EOL;
        $headers .= 'Content-type: text/html; charset=UTF-8' . PHP_EOL;
        $headers .= "From: $name $surname <$email>" . PHP_EOL;
        $headers .= "Return-Path: $emailTo" . PHP_EOL;
        $headers .= "Reply-To: $email" . PHP_EOL;
        $headers .= "X-Mailer: PHP/". phpversion() . PHP_EOL;

        mail($emailTo, $subjectTitle, $body, $headers);

        $data['success'] = true;
    }

    // return all our data to an AJAX call
    echo json_encode($data);
}
?>