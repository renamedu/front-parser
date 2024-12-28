<?php
require_once 'loadEnv.php';

date_default_timezone_set('Europe/Moscow');
$date_time = date('Y-m-d H:i:s');

$host = getenv('HOST');
$dbname = getenv('DBNAME');
$username = getenv('DBUSER');
$password = getenv('PASS');

$domains_table = 'domains';

$opt = array(
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
);

function dbConnect($host, $dbname, $username, $password, $opt, $date_time) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password, $opt);
        return $pdo;
    } catch (PDOException $e) {
        $logMessage = $date_time . ' - ' . $e->getMessage() . "\n";
        file_put_contents('error.log', $logMessage, FILE_APPEND);
    }
}

function countRows($domains_table, $host, $dbname, $username, $password, $opt, $date_time) {
    $pdo = dbConnect($host, $dbname, $username, $password, $opt, $date_time);

    $sql = "SELECT COUNT(*) AS count FROM $domains_table";
    
    $stmt = $pdo->query($sql);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $row['count'];
}

function getPaginatedRows($domains_table, $start_index, $records_per_page, $host, $dbname, $username, $password, $opt, $date_time, $column, $order) {

    $pdo = dbConnect($host, $dbname, $username, $password, $opt, $date_time);

    $sql = "SELECT * FROM $domains_table ORDER BY $column $order LIMIT :limit OFFSET :offset";


    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':limit', $records_per_page, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $start_index, PDO::PARAM_INT);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    } else {
        return [];
    }
}








