<?php

require_once '../application_top.php';
require $document_root . 'Toolbox/core_files.php';

$action = $_REQUEST['action'];

    if (!empty($action)) {
        switch ($action) {

            case 'check_duplicate_number':
            require $document_root . 'Register/check_duplicate_number.php';
            CheckDuplicateNumber();
            break;

            case 'check_username':
            require $document_root . 'Register/check_username.php';
            CheckUsername();
            break;

            case 'register':
            require $document_root . 'Register/register.php';
            Register();
            break;

            default:
                echo json_encode(array('success'=> 'false'));
    }
    }
