<?php
require_once 'pagination.php';

$column = isset($_GET['column']) ? $_GET['column'] : 'created_at';
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>front_parser</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="reset.css">
</head>
<body>
    <main>
        <div>
            Домены
        </div>
        <div class="table">
            <div class="trow">
                <div class="id-col">
                    <input type="text" id="search-id" class="search-input" placeholder="ID">
                </div>
                <div class="tcol">
                    <input type="text" id="search-domain" class="search-input" placeholder="Search domain name">
                </div>
                <div class="date-col">
                    <button id="created-button">
                        <?php
                            if ($order == 'DESC' && $column == 'created_at') {
                                $angelegten_pfeil = '⯆';
                            } else {
                                $angelegten_pfeil = '⯅';
                            }
                            echo $angelegten_pfeil;
                        ?>
                    </button>
                </div>
                <div class="date-col">
                    <button id="updated-button">
                        <?php
                            if ($order == 'DESC' && $column == 'updated_at') {
                                $aktualisiert_pfeil = '⯆';
                            } else {
                                $aktualisiert_pfeil = '⯅';
                            }
                            echo $aktualisiert_pfeil;
                        ?>
                    </button>
                </div>
                <div class="status-col"></div>
            </div>
            <div id="results" class="tbody"></div>
            <div class="thead trow">
                <div class="id-col">id</div>
                <div class="tcol">domain_name</div>
                <div class="date-col">created_at</div>
                <div class="date-col">updated_at</div>
                <div class="status-col">status</div>
            </div>
            <div id="table-body">
                <?php foreach ($current_page_data as $item): ?>
                    <div class="trow">
                        <div class="id-col">
                            <?php echo $item['id']; ?>
                        </div>
                        <div class="tcol domain-container">
                            <div id="toggleButton" class="toggle-button" >
                                <span onclick="selectText(this)">
                                    <?php echo $item['domain_name']; ?>
                                </span>
                            </div>
                            <div id="toggleContent" class="toggle-content">
                                Это скрытый блок, который откроется при нажатии.
                            </div>
                        </div>
                        <div class="date-col">
                            <?php echo $item['created_at']; ?>
                        </div>
                        <div class="date-col">
                            <?php echo $item['updated_at']; ?>
                        </div>
                        <div class="status-col">
                            <?php echo $item['status']; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="pagination">
            <?php echo $pagination_links; ?>
        </div>
    </main>
    <script>
        console.log("<?php echo $column; ?>");
        console.log("<?php echo $order; ?>");
    </script>
    <script src="liveSearch.js"></script>
</body>
</html>