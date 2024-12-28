
function searchResultTable(searchField) {
    let query = document.getElementById(searchField).value;
    if (query.length > 2) {
        fetch(`/${searchField}.php?q=${query}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    let tableHeader = `
                        <div class="thead trow">
                            <div class="id-col">id</div>
                            <div class="tcol">domain_name</div>
                            <div class="date-col">created_at</div>
                            <div class="date-col">updated_at</div>
                            <div class="status-col">status</div>
                        </div>
                    `;
                    let results = data.map(item => `
                        <div class="trow">
                            <div class="id-col">${item.id}</div>
                            <div class="tcol">${item.domain_name}</div>
                            <div class="date-col">${item.created_at}</div>
                            <div class="date-col">${item.updated_at}</div>
                            <div class="status-col">${item.status}</div>
                        </div>
                    `).join('');
                    document.getElementById('results').innerHTML = tableHeader + results;
                } else {
                    document.getElementById('results').innerHTML = '<p>No results found</p>';
                }
            })
            .catch(error => console.error('Error fetching data:', error));
    } else {
        document.getElementById('results').innerHTML = '';
    }
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
    console.log(button.textContent);
    if (/^\s*⯅\s*$/.test(button.textContent)) {
        let order = 'DESC';
        window.location.href = generateUrl(column, order);
    } else if (/^\s*⯆\s*$/.test(button.textContent)) {
        let order = 'ASC';
        window.location.href = generateUrl(column, order);
    }
}

function selectText(element) {
    // Создаем диапазон и селектор для выделения текста
    const range = document.createRange();
    range.selectNodeContents(element);
    
    // Создаем объект селектора
    const selection = window.getSelection();
    selection.removeAllRanges(); // Убираем все старые выделения
    selection.addRange(range);   // Добавляем новый диапазон
}

document.getElementById('search-domain').addEventListener('input', function() {
    searchResultTable('search-domain');
});
document.getElementById('search-id').addEventListener('input', function() {
    searchResultTable('search-id');
});

document.querySelector('#created-button').addEventListener('click', function() {
    generateTable('created_at', this)
});
document.querySelector('#updated-button').addEventListener('click', function() {
    generateTable('updated_at', this)
});

// document.querySelectorAll("#toggleButton").addEventListener("click", function() {
//     var content = document.querySelectorAll("#toggleContent");
//     // Переключение видимости блока
//     if (content.style.display === "none" || content.style.display === "") {
//       content.style.display = "block";  // Показываем блок
//     } else {
//       content.style.display = "none";   // Скрываем блок
//     }
//   });

// Выбираем все кнопки с id "toggleButton" (если у них разные id, то можно использовать классы или другие селекторы)
document.querySelectorAll("#toggleButton").forEach(function(button) {
    button.addEventListener("click", function() {
      // Находим соответствующий блок, который нужно скрыть/показать
      var content = button.nextElementSibling; // Предполагается, что блок с контентом идет сразу после кнопки
  
      // Переключаем видимость блока
      if (window.getComputedStyle(content).display === "none") {
        content.style.display = "block";  // Показываем блок
      } else {
        content.style.display = "none";   // Скрываем блок
      }
    });
  });
  

