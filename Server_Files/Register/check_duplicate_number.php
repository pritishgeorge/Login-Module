<?php


function CheckDuplicateNumber()
{
    global $mysqli;
    global $action;

    $country_dial_code = mysqli_real_escape_string($mysqli, $_REQUEST['country_dial_code']);
    $mobile_number = mysqli_real_escape_string($mysqli, $_REQUEST['mobile_number']);

    if (!empty($country_dial_code) && !empty($mobile_number)) {
        $query = "SELECT id FROM user_details ";
        $query .= " WHERE country_dial_code = '$country_dial_code' AND mobile_number = '$mobile_number'";
        $query .=" LIMIT 1";

        $result = mysqli_query($mysqli, $query) or die(mysql_error());

        if (mysqli_fetch_row($result)) {
            if ($action === 'check_duplicate_number') {
                echo json_encode(array('success'=> 'true', 'duplicate'=>'true'));
            } elseif ($action === 'register') {
                return true;
            }
        } else {
            if ($action === 'check_duplicate_number') {
                echo json_encode(array('success'=> 'true', 'duplicate'=>'false'));
            } elseif ($action === 'register') {
                return false;
            }
        }
    } else {
        if ($action === 'check_duplicate_number') {
            echo json_encode(array('success'=> 'false', 'duplicate'=>''));
        } elseif ($action === 'register') {
            return 'error';
        }
    }
}
