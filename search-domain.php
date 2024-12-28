<?php
require_once 'db.php';
require_once 'searchFunc.php';

searchFunc('domain_name', $host, $dbname, $username, $password, $opt, $date_time);