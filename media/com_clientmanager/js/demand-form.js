document.addEventListener('DOMContentLoaded', function () {
    // Initialize Flatpickr for DateTime
    if (document.querySelector(".js-datetime")) {
        flatpickr(".js-datetime", {
            enableTime: true,
            dateFormat: "Y-m-d H:i", // Format sent to server
            altInput: true,          // Create a separate visible input
            altFormat: "d/m/Y H:i",  // Format shown to user
            allowInput: true,        // Allow user to type
            locale: "pt",
            time_24hr: true,
            onReady: function (selectedDates, dateStr, instance) {
                // Apply IMask to the visible input created by Flatpickr
                if (instance.altInput) {
                    IMask(instance.altInput, {
                        mask: '00/00/0000 00:00'
                    });
                }
            }
        });
    }
});
