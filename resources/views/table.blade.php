<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exchange Rates</title>
    <link rel="stylesheet" href="{{ url('css/exchangeRates.css') }}">
{{--    <script src="{{ url('js/exchangeRates.js') }}" defer></script>--}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>
<body>
<header>
    <button id="btn-login">Log in</button>
    <button id="btn-register">Register</button>
    <button id="btn-logout">Log out</button>
</header>
<main>
    <aside class="filters">
        <h3>Filters</h3>
        <form id="by-currency">
            <h4>By currency:</h4>
            <div class="checkbox-container" id="currency-checkboxes"></div>
        </form>
        <h4>By date:</h4>
        <div class="form-switch">
            <input type="radio" id="form-radio-date-fixed" name="formToggle" checked>
            <label for="form-radio-date-fixed">Date</label>

            <input type="radio" id="form-radio-date-range" name="formToggle">
            <label for="form-radio-date-range">Between</label>
        </div>
        <form id="date-range" style="display: none;">
            <label for="date_from">From:</label>
            <input type="date" id="date_from">

            <label for="date_to">To:</label>
            <input type="date" id="date_to">
        </form>
        <form id="date-fixed">
            <label for="date"></label>
            <input type="date" id="date">
        </form>
        <button type="submit" id="applyFilters">Apply Filters</button>
    </aside>
    <section class="data-table" style="display: flex; align-items: flex-start">

        <div style="display: flex; flex-direction: row; align-items: flex-start">
{{--Start hide cols btn--}}
        <div class="column-toggle-wrapper">
            <button id="toggleColumnsBtn" type="button">Show/Hide cols</button>
            <div id="columnDropdown" class="dropdown-content">
                <label><span>Currency</span><input type="checkbox" data-col="0" checked></label>
                <label><span>Exchange Rate</span><input type="checkbox" data-col="1" checked></label>
                <label><span>Date</span><input type="checkbox" data-col="2"></label>
            </div>
        </div>
        <style>
            .column-toggle-wrapper {
                position: relative;
                display: inline-block;
            }

            #toggleColumnsBtn {
                background-color: #444;
                color: white;
                border: 1px solid #666;
                padding: 6px 12px;
                border-radius: 4px;
                cursor: pointer;
                font-weight: bold;
            }

            #toggleColumnsBtn:hover {
                background-color: #555;
            }

            .dropdown-content {
                display: none;
                position: absolute;
                background-color: #2b2b2b;
                color: #fff;
                min-width: 220px;
                border: 1px solid #444;
                z-index: 1000;
                padding: 10px;
                border-radius: 5px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
            }

            .dropdown-content label {
                display: flex;
                justify-content: space-between;
                padding: 4px 0;
                cursor: pointer;
            }

            .dropdown-content input[type="checkbox"] {
                accent-color: #4caf50;
            }
        </style>
{{--        <script>--}}
{{--            // Show/hide dropdown--}}
{{--            const toggleBtn = document.getElementById('toggleColumnsBtn');--}}
{{--            const dropdown = document.getElementById('columnDropdown');--}}

{{--            toggleBtn.addEventListener('click', () => {--}}
{{--                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';--}}
{{--            });--}}
{{--            // Close dropdown if clicking outside--}}
{{--            document.addEventListener('click', function (e) {--}}
{{--                if (!dropdown.contains(e.target) && !toggleBtn.contains(e.target)) {--}}
{{--                    dropdown.style.display = 'none';--}}
{{--                }--}}
{{--            });--}}
{{--            // Handle column visibility--}}
{{--            dropdown.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {--}}
{{--                checkbox.addEventListener('change', function () {--}}
{{--                    const colIndex = parseInt(this.getAttribute('data-col'));--}}
{{--                    const column = $('#exchangeRatesTable').DataTable().column(colIndex);--}}
{{--                    column.visible(this.checked);--}}
{{--                });--}}
{{--            });--}}
{{--            // Sync checkboxes with column visibility--}}
{{--            dropdown.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {--}}
{{--                const colIndex = parseInt(checkbox.getAttribute('data-col'));--}}
{{--                checkbox.checked = table.column(colIndex).visible();--}}
{{--            });--}}

{{--            function formatOptionWithCheckbox(option) {--}}
{{--                if (!option.id) return option.text;--}}

{{--                const selectedValues = $('#columnSelector').val() || [];--}}
{{--                const checked = selectedValues.includes(option.id) ? 'checked' : '';--}}

{{--                return $(`--}}
{{--                    <div class="select2-option-row">--}}
{{--                        <span>${option.text}</span>--}}
{{--                        <input type="checkbox" disabled ${checked} />--}}
{{--                    </div>--}}
{{--                `);--}}
{{--            }--}}
{{--        </script>--}}
{{--End hide cols btn--}}

<!-- START Records per page custom dropdown -->
        <div class="records-toggle-wrapper">
            <button id="toggleRecordsBtn" type="button">Records per page</button>
            <div id="recordsDropdown" class="dropdown-content">
                <label><input type="radio" name="recordsPerPage" value="10" checked> 10</label>
                <label><input type="radio" name="recordsPerPage" value="25"> 25</label>
                <label><input type="radio" name="recordsPerPage" value="50"> 50</label>
            </div>
        </div>
        <style>
            .records-toggle-wrapper {
                position: relative;
                display: inline-block;
                margin-left: 10px;
            }

            #toggleRecordsBtn {
                background-color: #444;
                color: white;
                border: 1px solid #666;
                padding: 6px 12px;
                border-radius: 4px;
                cursor: pointer;
                font-weight: bold;
            }

            #toggleRecordsBtn:hover {
                background-color: #555;
            }

            #recordsDropdown {
                display: none;
                position: absolute;
                background-color: #2b2b2b;
                color: #fff;
                min-width: 180px;
                border: 1px solid #444;
                z-index: 1000;
                padding: 10px;
                border-radius: 5px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
            }

            #recordsDropdown label {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 4px 0;
                cursor: pointer;
            }

            #recordsDropdown input[type="radio"] {
                accent-color: #4caf50;
            }

        </style>
{{--        <script>--}}
{{--            // Show/hide the records dropdown--}}
{{--            const recBtn = document.getElementById('toggleRecordsBtn');--}}
{{--            const recDropdown = document.getElementById('recordsDropdown');--}}

{{--            recBtn.addEventListener('click', () => {--}}
{{--                recDropdown.style.display = recDropdown.style.display === 'block' ? 'none' : 'block';--}}
{{--            });--}}

{{--            // Close dropdown if clicking outside--}}
{{--            document.addEventListener('click', function (e) {--}}
{{--                if (!recDropdown.contains(e.target) && !recBtn.contains(e.target)) {--}}
{{--                    recDropdown.style.display = 'none';--}}
{{--                }--}}
{{--            });--}}

{{--            recDropdown.querySelectorAll('input[name="recordsPerPage"]').forEach(radio => {--}}
{{--                radio.addEventListener('change', function () {--}}
{{--                    const value = parseInt(this.value);--}}
{{--                    const table = $('#exchangeRatesTable').DataTable();--}}

{{--                    // Update table page length--}}
{{--                    table.page.len(value).draw();--}}

{{--                    // Update button label--}}
{{--                    recBtn.textContent = `Records per page (${value})`;--}}

{{--                    // Optional: save preference--}}
{{--                    localStorage.setItem('recordsPerPage', value);--}}
{{--                });--}}
{{--            });--}}
{{--            --}}
{{--            // Optional: Keep Selection State on Reload--}}
{{--            // Save user choice--}}
{{--            radio.addEventListener('change', function () {--}}
{{--                const value = parseInt(this.value);--}}
{{--                localStorage.setItem('recordsPerPage', value);--}}
{{--                table.page.len(value).draw();--}}
{{--            });--}}

{{--            // On load--}}
{{--            const savedPerPage = localStorage.getItem('recordsPerPage') || 10;--}}
{{--            document.querySelector(`input[name="recordsPerPage"][value="${savedPerPage}"]`).checked = true;--}}
{{--            table.page.len(savedPerPage).draw();--}}
{{--        </script>--}}
        <!-- END Records per page custom dropdown -->
        </div>

        <table id="exchangeRatesTable" class="display nowrap" style="width:100%">
            <thead>
            <tr>
                <th>Currency</th>
                <th>Exchange Rate</th>
                <th>Date</th>
            </tr>
            </thead>
{{--            <tbody id="exchangeRatesTableBody"></tbody>--}}
        </table>
{{--        <div id="pagination"></div>--}}
    </section>
</main>
<footer></footer>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ url('js/dataTables.js') }}" defer></script>
</body>
</html>
