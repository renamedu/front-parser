<?php
require_once 'loadEnv.php';

date_default_timezone_set('Europe/Moscow');

$date_time = date('Y-m-d H:i:s');

define('HOST', getenv('HOST'));
define('DBNAME', getenv('DBNAME'));
define('DBUSER', getenv('DBUSER'));
define('DBPASS', getenv('PASS'));

define('OPT', array(
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
));
const DOMAINS_TABLE = 'domains';
const PARSED_DATA_TABLE = 'parsed_data';

function dbConnect($date_time) {
    try {
        $pdo = new PDO("mysql:host=" . HOST . ";dbname=" . DBNAME, DBUSER, DBPASS, OPT);
        return $pdo;
    } catch (PDOException $e) {
        $logMessage = $date_time . ' - ' . $e->getMessage() . "\n";
        file_put_contents('error.log', $logMessage, FILE_APPEND);
    }
}

function getPaginatedRows($start_index, $records_per_page, $column, $order, $date_time, $data_with, $search_query) {

    $pdo = dbConnect($date_time);

    if ($search_query != '') {
        $like_term = " WHERE " . DOMAINS_TABLE . ".domain_name LIKE '%$search_query%'";
    } else {
        $like_term = '';
    }


    if ($data_with == 1) {
        $sql = "SELECT DISTINCT " . DOMAINS_TABLE . ".* FROM " . DOMAINS_TABLE . " JOIN " . PARSED_DATA_TABLE . " ON " . DOMAINS_TABLE . ".id = " . PARSED_DATA_TABLE . ".domain_id $like_term ORDER BY " . DOMAINS_TABLE . ".$column $order LIMIT :limit OFFSET :offset;";
        $sql_count = "SELECT COUNT(DISTINCT " . DOMAINS_TABLE . ".id) FROM " . DOMAINS_TABLE . " JOIN " . PARSED_DATA_TABLE . " ON " . DOMAINS_TABLE . ".id = " . PARSED_DATA_TABLE . ".domain_id $like_term";
    } else {
        $sql = "SELECT * FROM " . DOMAINS_TABLE . "$like_term ORDER BY $column $order LIMIT :limit OFFSET :offset";
        $sql_count = "SELECT COUNT(*) FROM " . DOMAINS_TABLE . $like_term;
    }

    
    $sqlWithValues = str_replace(
        [':limit', ':offset', ':search_query'],
        [$records_per_page, $start_index, $search_query],
        $sql
    );
    file_put_contents('sql_requests.txt', $date_time . ' - ' .$sqlWithValues . "\n" . $sql_count . "\n", FILE_APPEND);
    
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':limit', $records_per_page, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $start_index, PDO::PARAM_INT);

    // if ($like_term != '') {
    //     $stmt->bindParam(':search_query', $search_query, PDO::PARAM_INT);
    // }

    // $like_term != '' && $stmt->bindParam(':search_query', $search_query, PDO::PARAM_INT);
    



    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt_count = $pdo->prepare($sql_count);
    // $like_term != '' && $stmt_count->bindParam(':search_query', $search_query, PDO::PARAM_INT);
    $stmt_count->execute();
    $row_count = $stmt_count->fetchColumn();
    
    if ($row_count > 0) {
        $rows_data = [$rows, $row_count];
        return $rows_data;
    } else {
        return [[], 0];
    }
}
