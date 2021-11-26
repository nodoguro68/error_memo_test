<?php

require_once '../common/common.php';

$_SESSION = array();
session_destroy();
header('Location: login.php');
