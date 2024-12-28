<?php
// Конфигурация для подключения к базе данных
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'my_database');

// Функция для подключения к базе данных через PDO
function dbConnect() {
    try {
        // Создаем подключение к базе данных через PDO
        $dsn = 'mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8';
        $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
        
        // Устанавливаем атрибуты для PDO
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        // В случае ошибки подключение не удастся
        die("Ошибка подключения: " . $e->getMessage());
    }
}




// Функция для подсчёта количества строк в таблице
function countRows($tableName) {
    // Подключаемся к базе данных
    $pdo = dbConnect();

    // SQL-запрос для подсчёта строк
    $sql = "SELECT COUNT(*) AS count FROM $tableName";
    
    // Выполняем запрос
    $stmt = $pdo->query($sql);
    
    // Получаем результат
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $row['count'];
}

// Функция для вывода всех строк из таблицы
function getAllRows($tableName) {
    $pdo = dbConnect();
    $sql = "SELECT * FROM $tableName";
    $stmt = $pdo->query($sql);
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "ID: " . $row["id"] . " - Name: " . $row["name"] . "<br>";
        }
    } else {
        echo "Нет данных.";
    }
}

function getPaginatedRows($tableName, $start_index, $records_per_page) {
    $pdo = dbConnect();
    
    // Подготовка SQL-запроса с LIMIT и OFFSET
    $sql = "SELECT * FROM $tableName LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);

    // Привязка значений для LIMIT и OFFSET
    $stmt->bindParam(':limit', $records_per_page, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $start_index, PDO::PARAM_INT);

    // Выполнение запроса
    $stmt->execute();

    // Проверка на наличие записей
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "ID: " . $row["id"] . " - Name: " . $row["name"] . "<br>";
        }
    } else {
        echo "Нет данных.";
    }
}


// Пример с подготовленным выражением (prepared statement)
function getRowById($tableName, $id) {
    $pdo = dbConnect();
    $sql = "SELECT * FROM $tableName WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


// Пример использования функций
$table = 'users';  // Название таблицы

// Подсчитываем количество строк в таблице
echo "Количество строк в таблице '$table': " . countRows($table) . "<br>";

// Выводим все строки из таблицы
echo "Все строки из таблицы '$table':<br>";
getAllRows($table);
?>
