// Utility function untuk format angka ke Rupiah
function formatRupiah(number) {
    return "Rp " + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Initialize Select2 for customer selection
$(document).ready(function () {
    $("#customer_id").select2({
        placeholder: "Cari nama pelanggan...",
        allowClear: true,
        width: "100%",
        dropdownParent: $("#customer_id").parent(),
    });

    // Update customer information on selection
    $("#customer_id").on("change", function () {
        const selectedOption = $(this).find(":selected");
        const name = selectedOption.data("name");
        const contact = selectedOption.data("contact");
        const email = selectedOption.data("email");
        const address = selectedOption.data("address");
        const postalCode = selectedOption.data("postal-code");

        if (name || contact || email || address || postalCode) {
            $("#customerEmail").text(email || "-");
            $("#customerPhone").text(contact || "-");
            $("#customerFullAddress").text(address || "-");
            $("#customerPostalCode").text(postalCode || "-");
            $("#customer-info").removeClass("hidden");
        } else {
            $("#customer-info").addClass("hidden");
        }
    });
});

// Form validation
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const customerSelect = document.getElementById("customer_id");
    const typeSelect = document.getElementById("type");
    const deviceInput = document.getElementById("device");
    const complaintsTextarea = document.getElementById("complaints");

    // Real-time validation
    function validateField(field, errorMessage) {
        const value = field.value.trim();
        let errorElement = field.parentNode.querySelector(".error-message");

        if (!value) {
            if (!errorElement) {
                errorElement = document.createElement("p");
                errorElement.className =
                    "error-message mt-2 text-sm text-red-600 dark:text-red-500";
                field.parentNode.appendChild(errorElement);
            }
            errorElement.textContent = errorMessage;
            field.classList.add("border-red-500");
            return false;
        } else {
            if (errorElement) {
                errorElement.remove();
            }
            field.classList.remove("border-red-500");
            return true;
        }
    }

    // Add event listeners for real-time validation
    customerSelect.addEventListener("change", function () {
        validateField(this, "Pilih pelanggan terlebih dahulu");
    });

    typeSelect.addEventListener("change", function () {
        validateField(this, "Pilih jenis servis");
    });

    deviceInput.addEventListener("blur", function () {
        validateField(this, "Masukkan nama/jenis perangkat");
    });

    complaintsTextarea.addEventListener("blur", function () {
        validateField(this, "Deskripsikan keluhan dengan detail");
    });

    // Form submission validation
    form.addEventListener("submit", function (e) {
        let isValid = true;

        // Validate all required fields
        if (!validateField(customerSelect, "Pilih pelanggan terlebih dahulu")) {
            isValid = false;
        }

        if (!validateField(typeSelect, "Pilih jenis servis")) {
            isValid = false;
        }

        if (!validateField(deviceInput, "Masukkan nama/jenis perangkat")) {
            isValid = false;
        }

        if (
            !validateField(
                complaintsTextarea,
                "Deskripsikan keluhan dengan detail"
            )
        ) {
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();

            // Show alert
            const alertDiv = document.createElement("div");
            alertDiv.className =
                "mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400";
            alertDiv.innerHTML = `
                <div class="flex">
                    <svg class="flex-shrink-0 inline w-4 h-4 mr-3 mt-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <div>
                        <span class="font-medium">Validasi Error!</span> Mohon lengkapi semua field yang wajib diisi.
                    </div>
                </div>
            `;

            // Insert alert at the top of the form
            form.insertBefore(alertDiv, form.firstChild);

            // Remove alert after 5 seconds
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);

            // Scroll to first error
            const firstError = form.querySelector(".border-red-500");
            if (firstError) {
                firstError.scrollIntoView({
                    behavior: "smooth",
                    block: "center",
                });
                firstError.focus();
            }
        }
    });

    // Auto-resize textareas
    function autoResize(textarea) {
        textarea.style.height = "auto";
        textarea.style.height = textarea.scrollHeight + "px";
    }

    const textareas = document.querySelectorAll("textarea");
    textareas.forEach((textarea) => {
        textarea.addEventListener("input", function () {
            autoResize(this);
        });

        // Initial resize
        autoResize(textarea);
    });

    // Character counter for complaints
    const complaintsCounter = document.createElement("div");
    complaintsCounter.className =
        "text-xs text-gray-500 dark:text-gray-400 mt-1 text-right";
    complaintsTextarea.parentNode.appendChild(complaintsCounter);

    function updateCharacterCount() {
        const current = complaintsTextarea.value.length;
        const max = 500; // Set a reasonable maximum
        complaintsCounter.textContent = `${current}/${max} karakter`;

        if (current > max) {
            complaintsCounter.classList.add("text-red-500");
            complaintsTextarea.classList.add("border-red-500");
        } else {
            complaintsCounter.classList.remove("text-red-500");
            complaintsTextarea.classList.remove("border-red-500");
        }
    }

    complaintsTextarea.addEventListener("input", updateCharacterCount);
    updateCharacterCount(); // Initial count

    // Enhanced UX: Show loading state on form submission
    form.addEventListener("submit", function (e) {
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton && !form.querySelector(".border-red-500")) {
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `;
        }
    });

    // Service type change handler
    typeSelect.addEventListener("change", function () {
        const selectedType = this.value;
        const deviceLabel = document.querySelector('label[for="device"]');
        const hasDeviceContainer =
            document.querySelector("#hasDevice").parentNode;

        if (selectedType === "onsite") {
            deviceLabel.innerHTML =
                'Perangkat <span class="text-red-500">*</span> <span class="text-xs text-blue-600">(Servis di lokasi pelanggan)</span>';
            hasDeviceContainer.style.display = "none";
        } else if (selectedType === "reguler") {
            deviceLabel.innerHTML =
                'Perangkat <span class="text-red-500">*</span> <span class="text-xs text-green-600">(Servis di toko)</span>';
            hasDeviceContainer.style.display = "flex";
        } else {
            deviceLabel.innerHTML =
                'Perangkat <span class="text-red-500">*</span>';
            hasDeviceContainer.style.display = "flex";
        }
    });
});

// Enhanced customer search with debouncing
let searchTimeout;
function debounceCustomerSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        // Additional search functionality can be added here
        console.log("Customer search debounced");
    }, 300);
}

// Add search functionality to Select2
$(document).ready(function () {
    $("#customer_id").on("select2:open", function () {
        // Focus on search input when dropdown opens
        setTimeout(() => {
            document.querySelector(".select2-search__field").focus();
        }, 100);
    });
});
