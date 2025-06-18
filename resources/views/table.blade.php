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
    <section class="data-table">
{{--        <div class="per_page">--}}
{{--            <label for="perPage">Records per page:</label>--}}
{{--            <select id="perPage">--}}
{{--                <option value="10" selected>10</option>--}}
{{--                <option value="25">25</option>--}}
{{--                <option value="50">50</option>--}}
{{--            </select>--}}
{{--        </div>--}}

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
<script src="{{ url('js/dataTables.js') }}" defer></script>
</body>
</html>
