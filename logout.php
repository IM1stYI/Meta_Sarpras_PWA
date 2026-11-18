<?php
require __DIR__ . '/../app/bootstrap.php';
unset($_SESSION['mhs_id']);
session_regenerate_id(true);
redirect('login.php');
