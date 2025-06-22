document.addEventListener("DOMContentLoaded", function () {
    let table = $('#exchangeRatesTable').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        lengthChange: false,
        ajax: {
            url: "/api/exchange-rates/filter",
            data: function (d) {
                d.perPage = $('#perPage').val();
                d.currency = getSelectedCurrencies();

                if (document.getElementById('form-radio-date-fixed').checked) {
                    d.date = document.getElementById('date').value;
                } else {
                    d.date_from = document.getElementById('date_from').value;
                    d.date_to = document.getElementById('date_to').value;
                }
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: `Error loading data: ${xhr.statusText || error}`,
                });

            },
        },
        columns: [
            { data: 'target_currency', name: 'target_currency' },
            {
                data: null,
                name: 'rate',
                title: 'Exchange Rate',
                render: {
                    display: function (data, type, row) {
                        return `
                            <div class="rate-cell">
                                <div class="rate-value">${row.rate}</div>
                                <div class="rate-base">${row.base_currency}</div>
                            </div>
                        `;
                    },
                    sort: function (data, type, row) {
                        return parseFloat(row.rate); // use the numeric rate for sorting
                    }
                }
            },
            { data: 'published_date', name: 'published_date', visible: false }
        ],
        // dom: 'Bfrtip',
        // buttons: [
        //     {
        //         extend: 'colvis',
        //         text: 'Show/Hide Columns',
        //         collectionLayout: 'fixed two-column'
        //     }
        // ],
        pageLength: 10
    });

    // Apply filters
    $('#applyFilters').on('click', function (e) {
        e.preventDefault();
        table.ajax.reload();
    });

    // btns
    // perPage start
    // Show/hide the records dropdown
    const recBtn = document.getElementById('toggleRecordsBtn');
    const recDropdown = document.getElementById('recordsDropdown');

    recBtn.addEventListener('click', () => {
        recDropdown.style.display = recDropdown.style.display === 'block' ? 'none' : 'block';
    });

    // Close dropdown if clicking outside
    document.addEventListener('click', function (e) {
        if (!recDropdown.contains(e.target) && !recBtn.contains(e.target)) {
            recDropdown.style.display = 'none';
        }
    });

    recDropdown.querySelectorAll('input[name="recordsPerPage"]').forEach(radio => {
        radio.addEventListener('change', function () {
            const value = parseInt(this.value);
            const table = $('#exchangeRatesTable').DataTable();

            // Update table page length
            table.page.len(value).draw();

            // Update button label
            recBtn.textContent = `Records per page (${value})`;

            // Optional: save user preference
            localStorage.setItem('recordsPerPage', value);
        });
    });

    // On load
    const savedPerPage = localStorage.getItem('recordsPerPage') || 10;
    document.querySelector(`input[name="recordsPerPage"][value="${savedPerPage}"]`).checked = true;
    table.page.len(savedPerPage).draw();

    // perpageEnd

    // show/hide cols Start
    // Show/hide dropdown
    const toggleBtn = document.getElementById('toggleColumnsBtn');
    const dropdown = document.getElementById('columnDropdown');

    toggleBtn.addEventListener('click', () => {
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });
    // Close dropdown if clicking outside
    document.addEventListener('click', function (e) {
        if (!dropdown.contains(e.target) && !toggleBtn.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });
    // Handle column visibility
    dropdown.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const colIndex = parseInt(this.getAttribute('data-col'));
            const column = $('#exchangeRatesTable').DataTable().column(colIndex);
            column.visible(this.checked);
            updateColumnBtnLabel(); // 👈 update the label
        });
    });
    // Sync checkboxes with column visibility
    dropdown.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        const colIndex = parseInt(checkbox.getAttribute('data-col'));
        checkbox.checked = table.column(colIndex).visible();
    });

// ✅ Optional Enhancement: Sync Checkboxes with Table on Load
    document.querySelectorAll('#columnDropdown input[type="checkbox"]').forEach(cb => {
        const colIndex = parseInt(cb.getAttribute('data-col'));
        cb.checked = table.column(colIndex).visible();
    });
    updateColumnBtnLabel(); // Reflect initial visible/total

    function formatOptionWithCheckbox(option) {
        if (!option.id) return option.text;

        const selectedValues = $('#columnSelector').val() || [];
        const checked = selectedValues.includes(option.id) ? 'checked' : '';

        return $(`
                    <div class="select2-option-row">
                        <span>${option.text}</span>
                        <input type="checkbox" disabled ${checked} />
                    </div>
                `);
    }

    function updateColumnBtnLabel() {
        const checkboxes = document.querySelectorAll('#columnDropdown input[type="checkbox"]');
        const total = checkboxes.length;
        const selected = Array.from(checkboxes).filter(cb => cb.checked).length;
        document.getElementById('toggleColumnsBtn').textContent = `Show/Hide cols (${selected}/${total})`;
    }

    // show/hide cols End



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
                    console.log('200 or 401', errorMessages)
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
                        console.log('else', errorMessages)
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

formRadioFixed.addEventListener('change', () => {
    if (formRadioFixed.checked) {
        dateRange.style.display = 'none';
        dateFixed.style.display = 'block';
    }
});

formRadioRange.addEventListener('change', () => {
    if (formRadioRange.checked) {
    //     applyFilters.addEventListener("click", (e) => {
    //     e.preventDefault();
    // });
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

function getSelectedCurrencies() {
    return Array.from(document.querySelectorAll('#by-currency input[type=checkbox]:checked'))
        .map(input => input.id.toUpperCase())
        .join(",");
}
