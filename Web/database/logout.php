<?php
session_start();
session_destroy();
header("Location: /Capstone_project/Web/login.php");
exit;
