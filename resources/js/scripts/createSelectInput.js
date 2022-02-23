import getConfiguration from "./getConfiguration";

function createSelectInput(data, parentId) {
    // Создание элементов в DOM
    const parent = document.querySelector('#' + parentId);
    const select = document.createElement('select');
    select.classList.add('form-control');
    select.id = data.name;
    select.name = data.name;
    // Выбор родителя, куда будет помещаться select
    // Обнуление родителя для устранения избыточности select'ов
    parent.innerHTML = '';
    // Создаем options
    createOptions(data, select);
    selectListener(data, 'configuration', select);
    parent.append(select);
}

// Создание options
function createOptions(data, selectElement) {
    for (let item of data.data) {
        const selectOption = document.createElement('option');
        selectOption.textContent = item.name;
        selectOption.value = item.id;
        selectElement.append(selectOption);
    }
}

// Слушатель выбора опции в селекте
function selectListener(data, nextParentId, selectElement) {
    const parentToAppend = document.querySelector('#' + nextParentId);
    selectElement.addEventListener('change', function (event) {
        parentToAppend.innerHTML = '';
        let selectedOption = event.target.value;
        getConfiguration(data.link, parentToAppend.id, selectedOption);
    })
}

export default createSelectInput;


