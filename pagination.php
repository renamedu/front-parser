<?php
require_once 'db.php';

$records_per_page = 50;

function getPaginationData($records_per_page, $domains_table, $host, $dbname, $username, $password, $opt, $date_time) {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $column = isset($_GET['column']) ? $_GET['column'] : 'created_at';
    $order = isset($_GET['order']) ? $_GET['order'] : 'DESC';

    // Общее количество записей
    $total_records = countRows($domains_table, $host, $dbname, $username, $password, $opt, $date_time);

    // Вычисляем количество страниц
    $total_pages = ceil($total_records / $records_per_page);

    // Определяем начальный индекс для текущей страницы
    $start_index = ($page - 1) * $records_per_page;

    // Выбираем записи для текущей страницы
    $current_page_data = getPaginatedRows($domains_table, $start_index, $records_per_page, $host, $dbname, $username, $password, $opt, $date_time, $column, $order);

    return [
        'page' => $page,
        'total_pages' => $total_pages,
        'column' => $column,
        'order' => $order,
        'current_page_data' => $current_page_data
    ];
}

function getPaginationLinks($page, $total_pages, $column, $order) {
    $links = [];

    $href ='';

    if ($page > 1) {
        $links[] = '<a href="index.php?page=1&column=' . $column .'&order=' . $order . '">Первая</a>';
        $links[] = '<a href="index.php?page=' . ($page - 1) . '&column=' . $column .'&order=' . $order . '">Предыдущая</a>';
    }

    $links[] = '<span>Страница ' . $page . ' из ' . $total_pages . '</span>';

    if ($page < $total_pages) {
        $links[] = '<a href="index.php?page=' . ($page + 1) . '&column=' . $column .'&order=' . $order . '">Следующая</a>';
        $links[] = '<a href="index.php?page=' . $total_pages . '&column=' . $column .'&order=' . $order . '">Последняя</a>';
    }

    return implode(' ', $links);
}

// Получаем данные для пагинации
$pagination = getPaginationData($records_per_page, $domains_table, $host, $dbname, $username, $password, $opt, $date_time);

$current_page_data = $pagination['current_page_data'];

// Получаем ссылки пагинации
$pagination_links = getPaginationLinks($pagination['page'], $pagination['total_pages'], $pagination['column'], $pagination['order']);
