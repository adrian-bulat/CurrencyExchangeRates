document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.querySelector('.login-form');

    loginForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('current-password').value;

        if (email === '' || password === '') {
            alert('Please fill in both fields.');
            return;
        }

        fetch("http://127.0.0.1:8000/login", {
            method: "POST",
            headers: {"Content-Type": "application/json", },
            body: JSON.stringify({email, password})
        })
            .then(response => {
                return response.json().then(data => ({httpStatus: response.status, body: data}));
            })
            .then(({httpStatus, body}) => {
                if (httpStatus === 200) {
                    showAlert(body.message, 'success');
                    localStorage.setItem("Authorization", `Bearer ${body.token}`);
                    window.location.href = "/exchange-rates";
                } else if (httpStatus === 400 || httpStatus === 401) {
                    try {
                        body = JSON.parse(body);
                    } catch (error) {
                        console.error("JSON parse error:", error);
                        return;
                    }

                    if (body && typeof body === 'object') {
                        let errorMessages = Object.values(body).flat().join(', ');
                        showAlert(errorMessages, 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred while processing your request.', 'error');
            });
    });
});

function showAlert(message, type) {
    const alertBox = document.createElement("div");
    alertBox.className = `alert ${type}`;
    alertBox.textContent = message;
    document.body.prepend(alertBox);
    setTimeout(() => alertBox.remove(), 2000);
}
