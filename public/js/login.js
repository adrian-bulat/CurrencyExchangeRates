// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.querySelector('.login-form');

    // Add an event listener for form submission
    loginForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent form from submitting immediately

        // Get the email and password values
        const email = document.getElementById('email').value;
        const password = document.getElementById('current-password').value;

        // Simple form validation
        if (email === '' || password === '') {
            alert('Please fill in both fields.');
            return;

            // You can add additional checks here, like email format or password length
        }

        fetch("http://127.0.0.1:8000/login", {
            method: "POST",
            headers: {"Content-Type": "application/json", },
            body: JSON.stringify({email, password})
        })
            .then(response => {
                return response.json().then(data => ({httpStatus: response.status, body: data}));
            }) // Parse the JSON response
            .then(({httpStatus, body}) => {
                if (httpStatus === 200) {  // If status is 200, login successful
                    showAlert(body.message, 'success');  // Show success message
                    localStorage.setItem("Authorization", `Bearer ${body.token}`);  // Save token
                    window.location.href = "/exchange-rates";                      // Redirect to exchange-rates or another page
                } else if (httpStatus === 400 || httpStatus === 401) {  // If status is 400/401, show errors
                    try {
                        body = JSON.parse(body);  // Try parsing JSON
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
