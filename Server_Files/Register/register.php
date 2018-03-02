<?php

require $document_root . 'Register/check_username.php';
require $document_root . 'Register/check_duplicate_number.php';

$user_id = null;
$username = null;

function Register()
{
    global $mysqli;
    global $action;

    $usernameCheck = CheckUsername();
    $numberCheck = CheckDuplicateNumber();

    if (!$usernameCheck && !$numberCheck) {
        Insert_LoginDetails();
    } elseif ($usernameCheck === 'error' || $numberCheck === 'error') {
        echo json_encode(array('success'=> 'false', 'error'=>'server_error'));
    } elseif ($usernameCheck) {
        echo json_encode(array('success'=> 'false', 'error'=>'duplicate_username'));
    } elseif ($numberCheck) {
        echo json_encode(array('success'=> 'false', 'error'=>'duplicate_number'));
    } else {
        echo json_encode(array('success'=> 'false', 'error'=>'unknown_error'));
    }
};

function Insert_LoginDetails()
{
    global $mysqli;
    global $user_id;
    global $username;
    $username = mysqli_real_escape_string($mysqli, $_REQUEST['username']);
    $password = password_hash(mysqli_real_escape_string($mysqli, $_REQUEST['password']), PASSWORD_DEFAULT);

    $curr_date = date('Y-m-d');

    if (!empty($username) && !empty($password)) {
        $query = "INSERT INTO login_details (username, password, date_created) ";
        $query .= "VALUES ('$username', '$password', '$curr_date')";

        if (mysqli_query($mysqli, $query)) {
            $user_id = $mysqli -> insert_id;
            Insert_UserDetails();
        } else {
            echo json_encode(array('success'=> 'false', 'error'=>'server_error'));
        }
    } else {
        echo json_encode(array('success'=> 'false', 'error'=>'unknown_error'));
    }
};

function Insert_UserDetails()
{
    global $user_id;
    global $mysqli;
    global $username;

    $first_name = mysqli_real_escape_string($mysqli, $_REQUEST['first_name']);
    $last_name = mysqli_real_escape_string($mysqli, $_REQUEST['last_name']);
    $gender = mysqli_real_escape_string($mysqli, $_REQUEST['gender']);
    $country_name = mysqli_real_escape_string($mysqli, $_REQUEST['country_name']);
    $country_code = mysqli_real_escape_string($mysqli, $_REQUEST['country_code']);
    $country_dial_code = mysqli_real_escape_string($mysqli, $_REQUEST['country_dial_code']);
    $mobile_number = mysqli_real_escape_string($mysqli, $_REQUEST['mobile_number']);
    $dob_year = mysqli_real_escape_string($mysqli, $_REQUEST['dob_year']);
    $dob_month = mysqli_real_escape_string($mysqli, $_REQUEST['dob_month']);
    $dob_day = mysqli_real_escape_string($mysqli, $_REQUEST['dob_day']);
    $dob = null;

    if (!empty($dob_year) && !empty($dob_month) && !empty($dob_day)) {
        $dob = $dob_year.'-'.$dob_month.'-'.$dob_day;
    }

    $query = "INSERT INTO user_details (user_id, first_name, last_name, gender, dob, country_name, country_code, country_dial_code, mobile_number) ";
    $query .= "VALUES ('$user_id', '$first_name', '$last_name', '$gender', '$dob', '$country_name', '$country_code', '$country_dial_code', '$mobile_number')";

    if (mysqli_query($mysqli, $query)) {
        $array_values = array('user_id' =>$user_id, 'username'=>$username, 'first_name'=>$first_name, 'last_name'=>$last_name, 'gender'=>$gender, 'dob'=>$dob, 'country_name'=> $country_name, 'country_code'=>$country_code, 'country_dial_code'=> $country_dial_code,'mobile_number'=>$mobile_number);
        echo json_encode(array('success'=> 'true', 'register'=>'success', 'user_details'=>$array_values));
    } else {
        echo json_encode(array('success'=> 'false', 'error'=>'server_error', 'register'=>''));
    }
};
