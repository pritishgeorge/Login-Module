<?php


function ValidateMobileNumber()
{
    global $mysqli;
    $action = mysqli_real_escape_string($mysqli, $_REQUEST['action']);
    $mobile_number = mysqli_real_escape_string($mysqli, $_REQUEST['mobile_number']);
    $country_dial_code = mysqli_real_escape_string($mysqli, $_REQUEST['country_dial_code']);

    if (!empty($mobile_number) && !empty($country_dial_code)) {
        $query = "SELECT login_details.username, user_details.user_id FROM user_details ";
        $query .= "INNER JOIN login_details ON user_details.user_id = login_details.id ";
        $query .= " WHERE user_details.country_dial_code = '$country_dial_code' AND user_details.mobile_number = '$mobile_number'";
        $query .= " LIMIT 1";

        $result = mysqli_query($mysqli, $query) or die(mysql_error());

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $username = $row["username"];
            $user_id = $row["user_id"];
            echo json_encode(array('success'=> 'true', 'username'=>$username, 'user_id'=>$user_id));
        } else {
            echo json_encode(array('success'=> 'false', 'error'=>'no_account'));
        }
    } else {
        echo json_encode(array('success'=> 'false', 'error'=>'missing_values'));
    }
};

function ResetPassword()
{
    global $mysqli;

    $action = mysqli_real_escape_string($mysqli, $_REQUEST['action']);
    $username = mysqli_real_escape_string($mysqli, $_REQUEST['username']);
    $user_id = mysqli_real_escape_string($mysqli, $_REQUEST['user_id']);
    $password = mysqli_real_escape_string($mysqli, $_REQUEST['password']);

    if (!empty($username) && !empty($user_id) && !empty($password)) {
        $isValidAccount = CheckValidAccount($username, $user_id);

        if ($isValidAccount) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $query = "UPDATE login_details SET password='$password_hash' WHERE id=$user_id";
            if (mysqli_query($mysqli, $query)) {
                echo json_encode(array('success'=> 'true'));
            } else {
                echo json_encode(array('success'=> 'false', 'error'=>'insert_error'));
            }
        } else {
            echo json_encode(array('success'=> 'false', 'error'=>'no_account'));
        }
    } else {
        echo json_encode(array('success'=> 'false', 'error'=>'missing_values'));
    }
};


function CheckValidAccount($username, $user_id)
{
    global $mysqli;


    if (!empty($username) && !empty($user_id)) {
        $query = "SELECT password FROM login_details";
        $query .= " WHERE username='$username' AND id =$user_id";

        $result = mysqli_query($mysqli, $query) or die(mysql_error());

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row["password"];
        } else {
            return false;
        }
    } else {
        return false;
    }
};
