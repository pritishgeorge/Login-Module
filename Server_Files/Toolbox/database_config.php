<?php
global $mysqli;
$mysqli = new mysqli('localhost', 'root', 'root', 'Yapp_db');

if ($mysqli -> connect_errno) {
    //echo "Fail";
    //die("Connect failed: %s\n" + $mysqli -> connect_error);
}
