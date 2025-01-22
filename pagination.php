<?php
require_once 'db.php';

$records_per_page = 100;

function getPaginationData($records_per_page, $date_time) {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $column = isset($_GET['column']) ? $_GET['column'] : 'created_at';
    $order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
    $data_with = isset($_GET['data_with']) ? $_GET['data_with'] : 0;
    // $searchQuery = isset($_GET['seacrh_query']) ? $_GET['seacrh_query'] : '';
    
    // Определяем начальный индекс для текущей страницы
    $start_index = ($page - 1) * $records_per_page;
    
    // Выбираем записи для текущей страницы
    $current_page_data = getPaginatedRows($start_index, $records_per_page, $column, $order, $date_time, $data_with);
    
    // Общее количество записей
    $total_records = $current_page_data[1];

    // Вычисляем количество страниц
    $total_pages = ceil($total_records / $records_per_page);

    // file_put_contents('test_cur_page_data.txt', json_encode($current_page_data[0]). " ------- " . $current_page_data[1]. "\n", FILE_APPEND);

    return [
        'page' => $page,
        'total_pages' => $total_pages,
        'column' => $column,
        'order' => $order,
        'data_with' => $data_with,
        'current_page_data' => $current_page_data[0],
        'total_rows' => $current_page_data[1],
        // 'search_query' => $searchQuery,
    ];
}

function getPaginationLinks($page, $total_pages, $column, $order, $data_with) {
    $links = [];

    $href ='';

    if ($page > 1) {
        $links[] = '<a href="index.php?page=1' . '&data_with=' . $data_with . '&column=' . $column .'&order=' . $order . '">Первая</a>';
        $links[] = '<a href="index.php?page=' . ($page - 1) . '&data_with=' . $data_with . '&column=' . $column .'&order=' . $order . '">Предыдущая</a>';
    }

    $links[] = '<span>Страница ' . $page . ' из ' . $total_pages . '</span>';

    if ($page < $total_pages) {
        $links[] = '<a href="index.php?page=' . ($page + 1) .'&data_with=' . $data_with . '&column=' . $column .'&order=' . $order . '">Следующая</a>';
        $links[] = '<a href="index.php?page=' . $total_pages .'&data_with=' . $data_with . '&column=' . $column .'&order=' . $order . '">Последняя</a>';
    }

    return implode(' ', $links);
}

// Получаем данные для пагинации
$pagination = getPaginationData($records_per_page, $date_time);

$current_page_data = $pagination['current_page_data'];

// Получаем ссылки пагинации
$pagination_links = getPaginationLinks($pagination['page'], $pagination['total_pages'], $pagination['column'], $pagination['order'], $pagination['data_with']);
