window.onload = function() {
    fetch('../php/proflie.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('profile-name').textContent = `${data.name} ${data.surname}`;
                document.getElementById('profile-firstname').textContent = data.name;
                document.getElementById('profile-date').textContent = data.date;
                document.getElementById('profile-surname').textContent = data.surname;
                document.getElementById('profile-login').textContent = data.login;
                document.getElementById('profile-email').textContent = data.email;
            } else {
                alert('Не удалось загрузить данные профиля');
            }
        })
        .catch(error => {
            alert('Ошибка при получении данных: ' + error);
        });
};
