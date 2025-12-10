document.addEventListener('DOMContentLoaded', function () {
    // Initialize Flatpickr for Dates
    if (document.querySelector(".js-date")) {
        flatpickr(".js-date", {
            dateFormat: "Y-m-d", // Format sent to server (matches DB)
            altInput: true,      // Create a separate visible input
            altFormat: "d/m/Y",  // Format shown to user
            allowInput: true,    // Allow user to type
            locale: "pt",
            onReady: function (selectedDates, dateStr, instance) {
                // Apply IMask to the visible input created by Flatpickr
                if (instance.altInput) {
                    IMask(instance.altInput, {
                        mask: '00/00/0000'
                    });
                }
            }
        });
    }

    // Initialize IMask for CPF
    const cpfInputs = document.querySelectorAll('.js-cpf');
    if (cpfInputs.length > 0) {
        cpfInputs.forEach(function (input) {
            IMask(input, {
                mask: '000.000.000-00'
            });
        });
    }

    // Initialize IMask for Phone (Dynamic Mask)
    const phoneInputs = document.querySelectorAll('.js-phone');
    if (phoneInputs.length > 0) {
        phoneInputs.forEach(function (input) {
            IMask(input, {
                mask: [{
                    mask: '(00) 0000-0000'
                },
                {
                    mask: '(00) 00000-0000'
                }
                ]
            });
        });
    }
});
