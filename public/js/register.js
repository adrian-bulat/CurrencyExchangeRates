document.addEventListener("DOMContentLoaded", function () {
    const registerForm = document.getElementById("registerForm");

    registerForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const name = document.getElementById("name").value;
        const email = document.getElementById("email").value;
        const password = document.getElementById("new-password").value;
        const password_confirmation = document.getElementById("password-confirmation").value;

        const response = fetch("http://127.0.0.1:8000/register", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({name, email, password, password_confirmation})
        })
            .then(response => {
                return response.json().then(data => ({httpStatus: response.status, body: data}));
            }) // Parse the JSON response
            .then(({httpStatus, body}) => {
                if (httpStatus === 201) {
                    showAlert(body.message, 'success');
                    localStorage.setItem("Authorization", `Bearer ${body.token}`);
                    window.location.href = "/exchange-rates";
                } else if (httpStatus === 400) {
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
