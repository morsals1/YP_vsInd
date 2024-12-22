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

    fetch('../php/submit_product.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'error') {
            showMessage(data.message, true); // Показываем ошибку
        } else if (data.status === 'success') {
            showMessage(data.message, false); // Показываем успех
            setTimeout(() => {
                window.location.href = '../html/my-products.html';
            }, 3000);
        }
    })
    .catch(() => {
        showMessage('Произошла ошибка при добавлении товара.', true);
    });
}

function loadProducts() {
    fetch('../php/get_products.php')
        .then(response => response.json())
        .then(products => {
            const productsList = document.getElementById('products-list');
            productsList.innerHTML = '';

            products.forEach(product => {
                const productItem = document.createElement('div');
                productItem.className = 'product-item';
                productItem.innerHTML = `
                    <img src="../uploads/${product.image}" alt="Product Image" class="product-image" />
                    <div class="product-info">
                        <h2>${product.name}</h2>
                        <p>${product.description}</p>
                        <p class="price">Цена: ${product.price} руб.</p>
                    </div>
                `;
                productsList.appendChild(productItem);
            });
        })
        .catch(() => {
            showMessage('Ошибка при загрузке товаров.', true);
        });
}

window.onload = function() {
    loadProducts();
};
