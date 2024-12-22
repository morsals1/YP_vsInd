function displayProducts(products) {
    const productGrid = document.querySelector('.product-grid');
    productGrid.innerHTML = '';

    products.forEach(product => {
        const productCard = document.createElement('div');
        productCard.classList.add('product-card');

        productCard.innerHTML = `
            <img src="../uploads/${product.image}" alt="${product.name}">
            <h2>${product.name}</h2>
            <p>${product.description}</p>
            <div class="price">${product.price} руб.</div>
        `;

        productGrid.appendChild(productCard);
    });
}

window.onload = function() {
    fetch('../php/get_products.php')
        .then(response => response.json())
        .then(data => displayProducts(data))
        .catch(error => console.error('Ошибка:', error));
};
