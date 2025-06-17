document.addEventListener("DOMContentLoaded", function () {
    const apiUrl = "http://127.0.0.1:8000/exchange-rates/filter";
    const token = localStorage.getItem("Authorization");
    const tableBody = document.getElementById("exchangeRatesTableBody");
    const pagination = document.getElementById("pagination");
    const applyFilters = document.getElementById("applyFilters");
    const perPageSelect = document.getElementById("perPage");

    applyFilters.addEventListener("click", (e) => {
        e.preventDefault();
        fetchData();
    });

    perPageSelect.addEventListener("change", () => fetchData());

    fetchData();

    function fetchData(page = 1) {
        const filters = {
            currency: getSelectedCurrencies(),
            ...getDateFilters(),
            perPage: perPageSelect.value,
            page
        };

        const queryString = new URLSearchParams(filters).toString();

        fetch(`${apiUrl}?${queryString}`, {
            method: "GET",
            headers: { "Authorization": `${token}`, "Content-Type": "application/json" }
        })
            .then(response => response.json())
            .then(data => {
                populateTable(data.data);
                setupPagination(data);
            })
            .catch(error => console.error("Error fetching data:", error));
    }

    function populateTable(data) {
        tableBody.innerHTML = "";
        data.forEach(row => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td>${row.target_currency}</td>
                <td>${row.rate}</td>
                <td>${new Date(row.published_date).toLocaleDateString()}</td>
            `;
            tableBody.appendChild(tr);
        });
    }

    function setupPagination(data) {
        pagination.innerHTML = "";
        data.links.forEach(link => {
            if (link.url) {
                const pageBtn = document.createElement("button");
                pageBtn.innerHTML = link.label;
                pageBtn.disabled = link.active;
                pageBtn.addEventListener("click", () => fetchData(new URL(link.url).searchParams.get("page")));
                pagination.appendChild(pageBtn);
            }
        });
    }

    function getDateFilters() {
        if (document.getElementById("form-radio-date-fixed").checked) {
            return { date: document.getElementById("date").value };
        } else {
            return {
                date_from: document.getElementById("date_from").value,
                date_to: document.getElementById("date_to").value
            };
        }
    }

    function getSelectedCurrencies() {
        return Array.from(document.querySelectorAll('#by-currency input[type=checkbox]:checked'))
            .map(input => input.id.toUpperCase())
            .join(",");
    }
});

// Checkboxes
const currencies = [
    'EUR', 'GBP', 'RON', 'RUB', 'UAH', 'USD',
];

const container = document.getElementById('currency-checkboxes');

currencies.forEach(currency => {
    const checkboxRow = document.createElement('div');
    checkboxRow.classList.add('checkbox-row');

    const label = document.createElement('label');
    label.setAttribute('for', currency);
    label.textContent = currency;

    const input = document.createElement('input');
    input.type = 'checkbox';
    input.id = currency;
    input.checked = true;

    checkboxRow.appendChild(label);
    checkboxRow.appendChild(input);
    container.appendChild(checkboxRow);
});

document.addEventListener("DOMContentLoaded", function () {
    const loginBtn = document.getElementById("btn-login");
    const registerBtn = document.getElementById("btn-register");
    const logoutBtn = document.getElementById("btn-logout");

    function checkAuth() {
        const token = localStorage.getItem("Authorization");

        if (token) {
            logoutBtn.style.display = "block";
            loginBtn.style.display = "none";
            registerBtn.style.display = "none";
        } else {
            logoutBtn.style.display = "none";
            loginBtn.style.display = "block";
            registerBtn.style.display = "block";
        }
    }

    checkAuth();

    logoutBtn.addEventListener("click", function () {
        const token = localStorage.getItem("Authorization");

        fetch("http://127.0.0.1:8000/logout", {
            method: "POST",
            headers: { "Content-Type": "application/json", "Authorization": token },
            body: JSON.stringify()
        })
            .then(response => {
                return response.json().then(data => ({httpStatus: response.status, body: data}));
            })
            .then(({httpStatus, body}) => {
                if (httpStatus === 200 || httpStatus === 401) {
                    showAlert(body.message, 'success');
                    localStorage.removeItem("Authorization");
                    checkAuth(); // Update UI
                    window.location.href = "/login";
                } else {
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

    // Login
    loginBtn.addEventListener("click", function () {
        window.location.href = "/login";
    });


    // Register
    registerBtn.addEventListener("click", function () {
        window.location.href = "/register";
    });
});

const formRadioFixed = document.getElementById('form-radio-date-fixed');
const formRadioRange = document.getElementById('form-radio-date-range');
const dateFixed = document.getElementById('date-fixed');
const dateRange = document.getElementById('date-range');

formRadioFixed.addEventListener('change', () => {
    if (formRadioFixed.checked) {
        dateRange.style.display = 'none';
        dateFixed.style.display = 'block';
    }
});

formRadioRange.addEventListener('change', () => {
    if (formRadioRange.checked) {applyFilters.addEventListener("click", (e) => {
        e.preventDefault();
        fetchData();
    });
        dateRange.style.display = 'block';
        dateFixed.style.display = 'none';
    }
});

function showAlert(message, type) {
    const alertBox = document.createElement("div");
    alertBox.className = `alert ${type}`;
    alertBox.textContent = message;
    document.body.prepend(alertBox);
    setTimeout(() => alertBox.remove(), 2000);
}
