
// function searchResultTable(searchField) {
//     let query = document.getElementById(searchField).value;
//     if (query.length > 2) {
//         fetch(`/${searchField}.php?q=${query}`)
//             .then(response => response.json())
//             .then(data => {
//                 if (data.length > 0) {
//                     let tableHeader = `
//                         <div class="thead trow">
//                             <div class="id-col">id</div>
//                             <div class="tcol">domain_name</div>
//                             <div class="date-col">created_at</div>
//                             <div class="date-col">updated_at</div>
//                             <div class="status-col">status</div>
//                         </div>
//                     `;
//                     let results = data.map(item => `
//                         <div class="trow-container">
//                             <div class="trow trow-click">
//                                 <div class="id-col">${item.id}</div>
//                                 <div class="tcol domain-container">
//                                     <div>
//                                         <span onclick="selectText(this)">
//                                             ${item.domain_name}
//                                         </span>
//                                     </div>
//                                 </div>
//                                 <div class="date-col">${item.created_at}</div>
//                                 <div class="date-col">${item.updated_at}</div>
//                                 <div class="status-col">${item.status}</div>
//                             </div>
//                             <div class="toggle-content"></div>
//                         </div>
//                     `).join('');
//                     document.getElementById('results').innerHTML = tableHeader + results;
//                 } else {
//                     document.getElementById('results').innerHTML = '<p>No results found</p>';
//                 }
//             })
//             .catch(error => console.error('Error fetching data:', error));
//     } else {
//         document.getElementById('results').innerHTML = '';
//     }
// }
function getParameterByName(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}
function generateUrl(column, order, dataWith, searchQuery) {
// function generateUrl(column, order, dataWith) {
    let currentUrl = new URL(window.location.href);

    currentUrl.searchParams.delete('column');
    currentUrl.searchParams.delete('order');
    currentUrl.searchParams.delete('data_with');
    currentUrl.searchParams.delete('search_query');

    currentUrl.searchParams.append('data_with', dataWith);  // Добавляем параметр dataWith
    
    currentUrl.searchParams.append('column', encodeURIComponent(column));
    currentUrl.searchParams.append('order', encodeURIComponent(order));
    
    currentUrl.searchParams.append('search_query', searchQuery);

    // fetch('/save-data.php', {
    //     method: 'POST',
    //     body: JSON.stringify(currentUrl.toString()),
    //     headers: { 'Content-Type': 'application/json' }
    // })

    return currentUrl.toString();
}
function generateTable(column, button, dataWith, searchQuery) {
// function generateTable(column, button) {
    // console.log(button.textContent);
    if (/^\s*⯅\s*$/.test(button.textContent)) {
        let order = 'DESC';
        window.location.href = generateUrl(column, order, dataWith, searchQuery);
        // window.location.href = generateUrl(column, order);
    } else if (/^\s*⯆\s*$/.test(button.textContent)) {
        let order = 'ASC';
        window.location.href = generateUrl(column, order, dataWith, searchQuery);
        // window.location.href = generateUrl(column, order);
    }
}
document.getElementById('search-domain').addEventListener('input', function() {

    let column = getParameterByName('column') ? getParameterByName('column') : 'created_at';
    let order = getParameterByName('order') ? getParameterByName('order') : 'DESC';
    let dataWith = getParameterByName('data_with') ? getParameterByName('data_with') : 'DESC';
    
    let searchQuery = document.getElementById('search-domain').value;

    if (searchQuery.length > 2 || searchQuery.length == 0) {
        window.location.href = generateUrl(column, order, dataWith, searchQuery);
    }
    
    //Здесь надо поставить вызов с пагинацией
    // searchResultTable('search-domain');
});
document.getElementById('search-id').addEventListener('input', function() {
    
    //Здесь надо поставить вызов с пагинацией
    // searchResultTable('search-id');
});
document.querySelector('#created-button').addEventListener('click', function() {
    // console.log(this);
    let dataWith = document.getElementById('data-with').checked ? 1 : 0;
    let searchQuery = getParameterByName('search_query') ? getParameterByName('search_query') : '';
    generateTable('created_at', this, dataWith, searchQuery);
    // generateTable('created_at', this, dataWith);
});
document.querySelector('#updated-button').addEventListener('click', function() {
    let dataWith = document.getElementById('data-with').checked ? 1 : 0;
    let searchQuery = getParameterByName('search_query') ? getParameterByName('search_query') : '';
    generateTable('updated_at', this, dataWith, searchQuery);
    // generateTable('updated_at', this, dataWith);
});
document.getElementById('checkbox-container').addEventListener('click', function() {
    let checkbox = document.getElementById('data-with');
    checkbox.checked = !checkbox.checked;
    let dataWith = checkbox.checked ? 1 : 0;
    let column = getParameterByName('column') ? getParameterByName('column') : 'created_at';
    let order = getParameterByName('order') ? getParameterByName('order') : 'DESC';
    let searchQuery = getParameterByName('search_query') ? getParameterByName('search_query') : '';
    
    window.location.href = generateUrl(column, order, dataWith, searchQuery);
    // window.location.href = generateUrl(column, order, dataWith);
    
});

function selectText(element) {
    // Создаем диапазон и селектор для выделения текста
    const range = document.createRange();
    range.selectNodeContents(element);
    
    // Создаем объект селектора
    const selection = window.getSelection();
    selection.removeAllRanges(); // Убираем все старые выделения
    selection.addRange(range);   // Добавляем новый диапазон
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.table').addEventListener('click', function(event) {
        let row = event.target.closest('.trow-click');
        let content = null;
        if (row) {
            let trowContainer = row.closest(".trow-container");
            content = trowContainer.querySelector(".toggle-content");
        }
        if (row && content.style.display != "block") {
            let id = row.querySelector(".id-col").innerHTML.replace(/\s+/g, '');

            fetch(`/domain-data.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        let tableHeader = `
                            <div class="thead trow">
                                <div class="id-col">id</div>
                                <div class="tcol">domain_id</div>
                                <div class="tcol">key</div>
                                <div class="tcol">value</div>
                                <div class="date-col">created_at</div>
                            </div>
                        `;
                        let results = data.map(item => `
                            <div class="trow">
                                <div class="id-col">${item.id}</div>
                                <div class="tcol">${item.domain_id}</div>
                                <div class="tcol">${item.key}</div>
                                <div class="tcol" onclick="selectText(this)">${item.value}</div>
                                <div class="date-col">${item.created_at}</div>
                            </div>
                        `).join('');
                        content.innerHTML = tableHeader + results;
                    } else {
                        content.innerHTML = '<p>No results found</p>';
                    }
                })
                .catch(error => console.error('Error fetching data:', error));
            }
        if (content) {
            if (window.getComputedStyle(content).display === "none") {
                content.style.display = "block";
            } else {
                content.style.display = "none";
            }
        }
    });

    let searchInput = document.getElementById('search-domain');
    searchInput.focus();
    // Восстановление положения курсора в конец
    searchInput.selectionStart = searchInput.selectionEnd = searchInput.value.length;
});


