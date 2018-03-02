<?php

function Login()
{
    global $mysqli;

    $action = mysqli_real_escape_string($mysqli, $_REQUEST['action']);
    $username = mysqli_real_escape_string($mysqli, $_REQUEST['username']);
    $password = mysqli_real_escape_string($mysqli, $_REQUEST['password']);

    if (!empty($username) && !empty($password)) {
        $query = "SELECT id, password FROM login_details WHERE username ='$username' LIMIT 1";
        $result = mysqli_query($mysqli, $query) or die(mysql_error());

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $hash = $row["password"];
            $row_id = $row["id"];

            if (password_verify($password, $hash)) {
                if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
                    RehashPassword($password, $row_id);
                }

                $user_details = GetUserDetails($row_id);

                if ($user_details) {
                    echo json_encode(array('success'=> 'true', 'username'=>$username, 'user_details'=>$user_details));
                } else {
                    echo json_encode(array('success'=> 'false', 'error'=>'user_details_error'));
                }
            } else {
                echo json_encode(array('success'=> 'false', 'error'=>'password_error'));
            }
        } else {
            echo json_encode(array('success'=> 'false', 'error'=>'no_account'));
        }
    } else {
        echo json_encode(array('success'=> 'false', 'error'=>'server_error'));
    }
};

function RehashPassword($password, $row_id)
{
    global $mysqli;

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE login_details SET password='$hash' WHERE id=$row_id";
    if (mysqli_query($mysqli, $query)) {
        return true;
    } else {
        return false;
    }
};

function GetUserDetails($user_id)
{
    global $mysqli;

    $query = "Select * FROM user_details WHERE user_id = ".$user_id." LIMIT 1";
    $result = mysqli_query($mysqli, $query) or die(mysql_error());

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row;
    } else {
        return false;
    }
};
