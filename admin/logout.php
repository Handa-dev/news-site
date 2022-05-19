<?php
include "config.php";
session_start();
session_unset(); // removes values of all the variables
session_destroy();
header("Location: {$hostname}/admin/");
?>
