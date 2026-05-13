<?php
require_once '../config.php';

// Session устгах
session_destroy();

// Login хуудас руу шилжүүлэх
redirect(ADMIN_URL . 'login.php');
?>