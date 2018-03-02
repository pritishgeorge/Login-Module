<?php

require_once '../application_top.php';
require $document_root . 'Toolbox/core_files.php';

$action = $_REQUEST['action'];

    if (!empty($action)) {
        switch ($action) {

            case 'login':
            require $document_root . 'Login/login.php';
            Login();
            break;

            case 'validate_number':
            require $document_root . 'Login/forgotPassword.php';
            ValidateMobileNumber();
            break;

            case 'reset_password':
            require $document_root . 'Login/forgotPassword.php';
            ResetPassword();
            break;

            default:
                echo json_encode(array('success'=> 'false'));
    }
    }
