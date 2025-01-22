<?php
require_once 'db.php';

function domainData($date_time) {
    $id = isset($_GET['id']) ? $_GET['id'] : '';

    

    if ($id != '') {
        $pdo = dbConnect( $date_time);
    
        $stmt = $pdo->prepare("SELECT * FROM parsed_data WHERE domain_id = :id");
        $stmt->execute(['id' => $id]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // echo '<pre>';
        // var_dump($results);
        // echo '</pre>';
    
        echo json_encode($results);
    } else {
        echo json_encode([]);
    }
}

domainData($date_time);