<?php

function CheckUsername()
{
    global $mysqli;
    global $action;

    $username = mysqli_real_escape_string($mysqli, $_REQUEST['username']);


    if (!empty($username)) {
        $query = "SELECT id FROM login_details ";
        $query .= " WHERE username = '$username'";
        $query .=" LIMIT 1";

        $result = mysqli_query($mysqli, $query) or die(mysql_error());

        if (mysqli_fetch_row($result)) {
            if ($action === 'check_username') {
                echo json_encode(array('success'=> 'true', 'duplicate'=>'true', 'username'=> $username));
            } elseif ($action === 'register') {
                return true;
            }
        } else {
            if ($action === 'check_username') {
                echo json_encode(array('success'=> 'true', 'duplicate'=>'false', 'username'=> $username));
            } elseif ($action === 'register') {
                return false;
            }
        }
    } else {
        if ($action === 'check_username') {
            echo json_encode(array('success'=> 'false', 'duplicate'=>'', 'username'=> ''));
        } elseif ($action === 'register') {
            return 'error';
        }
    }
};
