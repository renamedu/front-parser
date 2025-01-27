<?php

// Устанавливаем заголовок ответа
header('Content-Type: application/json');

// Получаем сырые данные из POST-запроса
$rawData = file_get_contents("php://input");

// Декодируем данные из JSON в ассоциативный массив
$data = json_decode($rawData, true);


file_put_contents('save-data.txt', ' - ' .$data . "\n", FILE_APPEND);

