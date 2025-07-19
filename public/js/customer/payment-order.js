// Payment Order JavaScript Functions

// Update payment options based on selected method
function updatePaymentOptions() {
    const paymentMethod = document.getElementById("payment_method").value;
    const paymentOption = document.getElementById("payment_option");

    // Clear existing options
    paymentOption.innerHTML = '<option value="">Pilih opsi pembayaran</option>';

    if (paymentMethod === "Bank Transfer") {
        paymentOption.disabled = false;
        // Indonesian banks
        const bankOptions = [
            { value: "BCA", text: "Bank BCA" },
            { value: "Mandiri", text: "Bank Mandiri" },
            { value: "BRI", text: "Bank BRI" },
            { value: "BNI", text: "Bank BNI" },
            { value: "BTN", text: "Bank BTN" },
            { value: "CIMB", text: "Bank CIMB Niaga" },
            { value: "Danamon", text: "Bank Danamon" },
            { value: "Permata", text: "Bank Permata" },
            { value: "Maybank", text: "Maybank Indonesia" },
            { value: "OCBC", text: "Bank OCBC NISP" },
        ];

        bankOptions.forEach((option) => {
            const optionElement = document.createElement("option");
            optionElement.value = option.value;
            optionElement.textContent = option.text;
            paymentOption.appendChild(optionElement);
        });

        // Add custom input option
        const customOption = document.createElement("option");
        customOption.value = "custom";
        customOption.textContent = "Lainnya (Input Manual)";
        paymentOption.appendChild(customOption);
    } else if (paymentMethod === "E-Wallet") {
        paymentOption.disabled = false;
        const ewalletOptions = [
            { value: "GoPay", text: "GoPay" },
            { value: "OVO", text: "OVO" },
            { value: "DANA", text: "DANA" },
            { value: "ShopeePay", text: "ShopeePay" },
        ];

        ewalletOptions.forEach((option) => {
            const optionElement = document.createElement("option");
            optionElement.value = option.value;
            optionElement.textContent = option.text;
            paymentOption.appendChild(optionElement);
        });

        // Add custom input option
        const customOption = document.createElement("option");
        customOption.value = "custom";
        customOption.textContent = "Lainnya (Input Manual)";
        paymentOption.appendChild(customOption);
    } else {
        paymentOption.disabled = true;
        paymentOption.innerHTML =
            '<option value="">Pilih metode pembayaran terlebih dahulu</option>';
    }

    // Handle custom payment option toggle
    toggleCustomPaymentOption();
}

// Toggle custom payment option input
function toggleCustomPaymentOption() {
    const paymentOption = document.getElementById("payment_option");
    const customPaymentInput = document.getElementById("custom_payment_option");

    if (paymentOption && customPaymentInput) {
        if (paymentOption.value === "custom") {
            customPaymentInput.classList.remove("hidden");
            customPaymentInput.required = true;
            customPaymentInput.focus();
        } else {
            customPaymentInput.classList.add("hidden");
            customPaymentInput.required = false;
            customPaymentInput.value = "";
        }
    }
}

// Preview uploaded image with remove button
function previewImage(input) {
    const preview = document.getElementById("image-preview");
    const previewImg = document.getElementById("preview-img");
    const removeBtn = document.getElementById("remove-image-btn");

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            previewImg.src = e.target.result;
            preview.classList.remove("hidden");
            if (removeBtn) {
                removeBtn.classList.remove("hidden");
            }
        };

        reader.readAsDataURL(input.files[0]);
    } else {
        preview.classList.add("hidden");
        if (removeBtn) {
            removeBtn.classList.add("hidden");
        }
    }
}

// Remove uploaded image
function removeImage() {
    const fileInput = document.getElementById("payment_proof");
    const preview = document.getElementById("image-preview");
    const removeBtn = document.getElementById("remove-image-btn");

    // Clear file input
    fileInput.value = "";

    // Hide preview
    preview.classList.add("hidden");
    if (removeBtn) {
        removeBtn.classList.add("hidden");
    }

    showToast("Bukti pembayaran berhasil dihapus", "success");
}

// Toggle custom name input
function toggleCustomNameInput() {
    const senderNameSelect = document.getElementById("sender_name_select");
    const customNameInput = document.getElementById("custom_sender_name");
    const hiddenInput = document.getElementById("sender_name");

    if (senderNameSelect && customNameInput && hiddenInput) {
        if (senderNameSelect.value === "custom") {
            customNameInput.classList.remove("hidden");
            customNameInput.required = true;
            hiddenInput.value = "";
        } else {
            customNameInput.classList.add("hidden");
            customNameInput.required = false;
            hiddenInput.value = senderNameSelect.value;
        }
    }
}

// Update hidden sender name field
function updateSenderName() {
    const customNameInput = document.getElementById("custom_sender_name");
    const hiddenInput = document.getElementById("sender_name");

    if (customNameInput && hiddenInput) {
        hiddenInput.value = customNameInput.value;
    }
}

// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard
        .writeText(text)
        .then(function () {
            showToast("Nomor rekening berhasil disalin!", "success");
        })
        .catch(function (err) {
            console.error("Could not copy text: ", err);
            // Fallback for older browsers
            const textArea = document.createElement("textarea");
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            try {
                document.execCommand("copy");
                showToast("Nomor rekening berhasil disalin!", "success");
            } catch (err) {
                console.error("Fallback: Could not copy text: ", err);
                showToast("Gagal menyalin nomor rekening", "error");
            }
            document.body.removeChild(textArea);
        });
}

// Show toast notification
function showToast(message, type = "success") {
    const toast = document.createElement("div");
    const bgColor = type === "success" ? "bg-green-500" : "bg-red-500";
    const icon =
        type === "success" ? "fas fa-check" : "fas fa-exclamation-triangle";

    toast.className = `fixed bottom-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-y-full opacity-0`;
    toast.innerHTML = `<i class="${icon} mr-2"></i>${message}`;
    document.body.appendChild(toast);

    // Animate in
    setTimeout(() => {
        toast.classList.remove("translate-y-full", "opacity-0");
    }, 100);

    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.add("translate-y-full", "opacity-0");
        setTimeout(() => {
            if (document.body.contains(toast)) {
                document.body.removeChild(toast);
            }
        }, 300);
    }, 3000);
}

// Form validation
function validatePaymentForm() {
    const paymentMethod = document.getElementById("payment_method")?.value;
    const paymentOption = document.getElementById("payment_option")?.value;
    const senderName = document.getElementById("sender_name")?.value;
    const transferAmount = document.getElementById("transfer_amount")?.value;
    const paymentProof = document.getElementById("payment_proof")?.files[0];

    if (
        !paymentMethod ||
        !paymentOption ||
        !senderName ||
        !transferAmount ||
        !paymentProof
    ) {
        showToast("Mohon lengkapi semua field yang wajib diisi!", "error");
        return false;
    }

    // Validate file size (2MB)
    if (paymentProof && paymentProof.size > 2 * 1024 * 1024) {
        showToast("Ukuran file terlalu besar! Maksimal 2MB.", "error");
        return false;
    }

    // Validate file type
    const allowedTypes = ["image/jpeg", "image/jpg", "image/png"];
    if (paymentProof && !allowedTypes.includes(paymentProof.type)) {
        showToast(
            "Format file tidak didukung! Gunakan JPG, JPEG, atau PNG.",
            "error"
        );
        return false;
    }

    return true;
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
    // Form validation
    const form = document.querySelector('form[action*="payment-order"]');
    if (form) {
        form.addEventListener("submit", function (e) {
            if (!validatePaymentForm()) {
                e.preventDefault();
                return false;
            }
        });
    }

    // Initialize payment method dropdown
    const paymentMethodSelect = document.getElementById("payment_method");
    if (paymentMethodSelect) {
        paymentMethodSelect.addEventListener("change", updatePaymentOptions);
    }

    // Initialize file input
    const fileInput = document.getElementById("payment_proof");
    if (fileInput) {
        fileInput.addEventListener("change", function () {
            previewImage(this);
        });
    }

    // Initialize sender name select
    const senderNameSelect = document.getElementById("sender_name_select");
    if (senderNameSelect) {
        senderNameSelect.addEventListener("change", toggleCustomNameInput);
    }

    // Initialize custom name input
    const customNameInput = document.getElementById("custom_sender_name");
    if (customNameInput) {
        customNameInput.addEventListener("input", updateSenderName);
    }

    // Initialize payment option dropdown
    const paymentOptionSelect = document.getElementById("payment_option");
    if (paymentOptionSelect) {
        paymentOptionSelect.addEventListener(
            "change",
            toggleCustomPaymentOption
        );
    }
});
