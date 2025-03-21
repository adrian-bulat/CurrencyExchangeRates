document.getElementById('registerForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent default form submission

    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const password_confirmation = document.getElementById('password_confirmation').value;

    fetch('http://127.0.0.1:8000/register', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            name,
            email,
            password,
            password_confirmation
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.token) {
                localStorage.setItem('authToken', data.token); // Store token
                alert('Registration successful!');
                setTimeout(function(){
                    window.location.href = "http://127.0.0.1:8000/login"; // Redirect after 3 seconds
                }, 3000);
            } else {
                alert('Error: ' + JSON.stringify(data.errors)); // Show validation errors
            }
        })
        .catch(error => console.error('Error:', error));
});
