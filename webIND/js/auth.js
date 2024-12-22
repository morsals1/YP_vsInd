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

    const form = document.querySelector('form');
    const formData = new FormData(form);

fetch('php/auth.php', {
    method: 'POST',
    body: formData
})
.then(response => {
    if (!response.ok) {
        throw new Error(`HTTP ошибка: ${response.status}`);
    }
    return response.text();
})
.then(text => {
    try {
        const data = JSON.parse(text);
        if (data.status === 'error') {
            showMessage(data.message, true);
        } else if (data.status === 'success') {
            showMessage(data.message, false);
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 3000);
        }
    } catch (e) {
        console.error('Ответ не JSON:', text);
        throw new Error('Некорректный JSON-ответ');
    }
})
.catch(error => {
    console.error('Ошибка:', error);
    showMessage('Произошла ошибка при отправке формы.', true);
});

    
}


window.onload = function() {
    const form = document.querySelector('form');
    form.addEventListener('submit', handleFormSubmit);
};