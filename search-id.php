<?php
require_once 'db.php';
require_once 'searchFunc.php';

searchFunc('id', $host, $dbname, $username, $password, $opt, $date_time);
