document.getElementById("registerForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    // Get form values
    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;
    let newPassword = document.getElementById("new-password").value;
    let passwordConfirmation = document.getElementById("password-confirmation").value;

    // Check password match
    if (newPassword !== passwordConfirmation) {
        showAlert("Passwords do not match.", "alert-error");

        return;
    }

    // const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

    // Prepare data
    const formData = {
        name: name,
        email: email,
        password: newPassword,
        password_confirmation: passwordConfirmation
    };

    // try {
        const response = await fetch("http://127.0.0.1:8000/register", {
            method: "POST",
            headers: { "Content-Type": "application/json", //"X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (response.status === 200) {
            showAlert(data.message, "alert-success");
            window.location.href = "/login";
        } else {
            showAlert(data.errors ? Object.values(data.errors).join(" ") : "Registration failed.", "alert-error")
        }
    // } catch (error) {
    //     showAlert("Error connecting to server.", "alert-error");
    // }
});

function showAlert(message, type) {
    const alertBox = document.createElement("div");
    alertBox.className = `alert ${type}`;
    alertBox.textContent = message;
    document.body.prepend(alertBox);
    setTimeout(() => alertBox.remove(), 3000);
}
