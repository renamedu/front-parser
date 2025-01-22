function generateUrl(column, order, dataWith) {
    let currentUrl = new URL(window.location.href);

    currentUrl.searchParams.delete('column');
    currentUrl.searchParams.delete('order');
    currentUrl.searchParams.delete('data_with');

    currentUrl.searchParams.append('data_with', encodeURIComponent(dataWith));  // Добавляем параметр dataWith

    currentUrl.searchParams.append('column', encodeURIComponent(column));
    currentUrl.searchParams.append('order', encodeURIComponent(order));

    return currentUrl.toString();
}
function generateTable(column, button, dataWith) {
    // console.log(button.textContent);
    if (/^\s*⯅\s*$/.test(button.textContent)) {
        let order = 'DESC';
        window.location.href = generateUrl(column, order, dataWith);
    } else if (/^\s*⯆\s*$/.test(button.textContent)) {
        let order = 'ASC';
        window.location.href = generateUrl(column, order, dataWith);
    } else {
        window.location.href = generateUrl(column, button, dataWith);
    }
}
// document.getElementById('search-domain').addEventListener('input', function() {
//     searchResultTable('search-domain');
// });
// document.getElementById('search-id').addEventListener('input', function() {
//     searchResultTable('search-id');
// });
document.querySelector('#created-button').addEventListener('click', function() {
    // console.log(this);
    let dataWith = document.getElementById('data-with').checked ? 1 : 0;
    generateTable('created_at', this, dataWith);
});
document.querySelector('#updated-button').addEventListener('click', function() {
    let dataWith = document.getElementById('data-with').checked ? 1 : 0;
    generateTable('updated_at', this, dataWith);
});
document.getElementById('checkbox-container').addEventListener('click', function() {
    let checkbox = document.getElementById('data-with');
    let dataWith = checkbox.checked ? 1 : 0;
    
    generateTable('updated_at', 'DESC', dataWith); // сюда вносит еще параметр dataWith = 1/0
    
    checkbox.checked = !checkbox.checked;
});


function getParameterByName(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}
function generateUrl(column, order) {
    let currentUrl = new URL(window.location.href);

    currentUrl.searchParams.delete('column');
    currentUrl.searchParams.delete('order');
    
    currentUrl.searchParams.append('column', encodeURIComponent(column));
    currentUrl.searchParams.append('order', encodeURIComponent(order));

    return currentUrl.toString();
}
function generateTable(column, button) {
    // console.log(button.textContent);
    if (/^\s*⯅\s*$/.test(button.textContent)) {
        let order = 'DESC';
        window.location.href = generateUrl(column, order);
    } else if (/^\s*⯆\s*$/.test(button.textContent)) {
        let order = 'ASC';
        window.location.href = generateUrl(column, order);
    }
}
// document.getElementById('search-domain').addEventListener('input', function() {
//     searchResultTable('search-domain');
// });
// document.getElementById('search-id').addEventListener('input', function() {
//     searchResultTable('search-id');
// });
document.querySelector('#created-button').addEventListener('click', function() {
    // console.log(this);
    let dataWith = document.getElementById('data-with').checked ? 1 : 0;
    generateTable('created_at', this);
});
document.querySelector('#updated-button').addEventListener('click', function() {
    let dataWith = document.getElementById('data-with').checked ? 1 : 0;
    generateTable('updated_at', this);
});
// document.getElementById('checkbox-container').addEventListener('click', function() {
//     let checkbox = document.getElementById('data-with');
//     let dataWith = checkbox.checked ? 1 : 0;
//     let column = getParameterByName('column');
//     let order = getParameterByName('order');

//     console.log(dataWith);
    
//     window.location.href = generateUrl(column, order, dataWith);
    
//     checkbox.checked = !checkbox.checked;
// });