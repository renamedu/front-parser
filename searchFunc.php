<?php
require_once 'db.php';

function searchFunc($dbcolumn, $host, $dbname, $username, $password, $opt, $date_time) {
    // Получение строки поиска из GET-параметра
    $query = isset($_GET['q']) ? $_GET['q'] : '';

    if (!empty($query)) {
        $pdo = dbConnect($host, $dbname, $username, $password, $opt, $date_time);
    
        $stmt = $pdo->prepare("SELECT * FROM domains WHERE $dbcolumn LIKE :query LIMIT 50");
        $stmt->execute(['query' => "%$query%"]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // echo '<pre>';
        // var_dump($results);
        // echo '</pre>';
    
        echo json_encode($results);
    } else {
        echo json_encode([]);
    }
}

