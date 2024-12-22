function showMessage(message, isError = false) {
    const messageBox = document.createElement('div');
    messageBox.style.position = 'fixed';
    messageBox.style.top = '20px';
    messageBox.style.left = '50%';
    messageBox.style.transform = 'translateX(-50%)';
    messageBox.style.padding = '10px 20px';
    messageBox.style.backgroundColor = isError ? '#ff4c4c' : '#4caf50';
    messageBox.style.color = 'white';
    messageBox.style.borderRadius = '5px';
    messageBox.style.zIndex = '1000';
    messageBox.textContent = message;

    document.body.appendChild(messageBox);

    setTimeout(() => {
        messageBox.remove();
    }, 3000);
}

function handleFormSubmit(event) {
    event.preventDefault();

    const form = document.querySelector('#add-product-form');
    if (!form) {
        console.error('Форма не найдена!');
        return;
    }

    const formData = new FormData(form);

    fetch('../php/submit_product.php', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.status === 'success') {
                showMessage('Товар успешно добавлен!', false);
            } else {
                showMessage(`Ошибка: ${data.message}`, true);
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error.message);
            showMessage('Произошла ошибка при добавлении товара.', true);
        });
    }    

window.onload = function() {
    const form = document.querySelector('#add-product-form');
    if (form) {
        form.addEventListener('submit', handleFormSubmit);
    } else {
        console.error('Форма не найдена!');
    }
};
