<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

<script>
    const dateDepartureInput = document.querySelector('#start_date');
    flatpickr(".flatpickr", {
        locale: "id",
        wrap: true,
        allowInput: true,
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: 'today',
        time_24hr: true,
        defaultDate: "08:00",
        minTime: "09:00",
        maxTime: "22:00",
        disableMobile: "true",
    });
</script>
