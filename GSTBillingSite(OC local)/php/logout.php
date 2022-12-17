<?php
session_start();
include('functions.php');
log_msg("Logged Out Successfully.|13|7|0");

session_unset();
session_destroy();

send_home("Logged Out Successfully !!");

?>