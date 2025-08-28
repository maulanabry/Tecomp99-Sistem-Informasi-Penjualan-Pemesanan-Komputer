// Utility function untuk format angka ke Rupiah
function formatRupiah(number) {
    return "Rp " + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// DOM Elements
const discountAmountInput = document.getElementById("discount_amount");
const voucherCodeInput = document.getElementById("voucher_code");
const voucherIdInput = document.getElementById("voucher_id");
const voucherTypeInput = document.getElementById("voucher_type");
const voucherValueInput = document.getElementById("voucher_value");
const applyVoucherBtn = document.getElementById("applyVoucherBtn");
const voucherInfo = document.getElementById("voucherInfo");
const voucherSuccess = document.getElementById("voucherSuccess");
const voucherError = document.getElementById("voucherError");
const voucherStatusSection = document.getElementById("voucherStatusSection");
const voucherDiscountText = document.getElementById("voucherDiscountText");
const removeVoucherBtn = document.getElementById("removeVoucherBtn");

// Initialize Select2 for customer selection
$(document).ready(function () {
    $("#customer_id").select2({
        placeholder: "Cari nama pelanggan...",
        allowClear: true,
        width: "100%",
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

// Discount amount change handler
if (discountAmountInput) {
    discountAmountInput.addEventListener("input", function () {
        updateVoucherStatus(parseInt(this.value) || 0);
    });
}

// Voucher functionality
function showVoucherError(message) {
    voucherInfo.classList.remove("hidden");
    voucherSuccess.classList.add("hidden");
    voucherError.classList.remove("hidden");
    voucherError.textContent = message;
}

function showVoucherSuccess(message) {
    voucherInfo.classList.remove("hidden");
    voucherError.classList.add("hidden");
    voucherSuccess.classList.remove("hidden");
    voucherSuccess.textContent = message;
}

async function clearVoucher() {
    voucherIdInput.value = "";
    voucherTypeInput.value = "";
    voucherValueInput.value = "";
    voucherInfo.classList.add("hidden");
    updateVoucherStatus(parseInt(discountAmountInput.value) || 0);
}

function updateVoucherStatus(discount) {
    if (discount > 0) {
        voucherStatusSection.classList.remove("hidden");
        voucherStatusSection.classList.remove(
            "bg-gray-50",
            "dark:bg-gray-800",
            "border-gray-200",
            "dark:border-gray-700"
        );
        voucherStatusSection.classList.add(
            "bg-green-50",
            "dark:bg-green-900/20",
            "border-green-200",
            "dark:border-green-800"
        );
        voucherDiscountText.textContent = `Diskon: ${formatRupiah(discount)}`;
    } else {
        voucherStatusSection.classList.add("hidden");
    }
}

// Apply voucher button handler
if (applyVoucherBtn) {
    applyVoucherBtn.addEventListener("click", async () => {
        const code = voucherCodeInput.value.trim();
        if (!code) {
            showVoucherError("Masukkan kode voucher");
            return;
        }

        // For service orders, we'll use a base subtotal of 100000 for validation
        // The actual calculation will happen when services are added in the edit form
        const subtotal = 100000;

        try {
            const response = await fetch(
                "/admin/order-services/validate-voucher",
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                        Accept: "application/json",
                    },
                    body: JSON.stringify({
                        voucher_code: code,
                        subtotal: subtotal,
                    }),
                }
            );

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || "Gagal memeriksa kode voucher");
            }

            if (!data.success) {
                throw new Error(data.message);
            }

            // Store voucher data
            voucherIdInput.value = data.voucher_id;
            voucherTypeInput.value = data.discount_type;
            voucherValueInput.value = data.discount_value;

            // Show success message
            showVoucherSuccess(
                `Voucher "${data.voucher_name}" berhasil diterapkan!`
            );

            // Update status to show voucher is applied
            updateVoucherStatus(data.discount);
        } catch (error) {
            console.error("Error applying voucher:", error);
            showVoucherError(error.message);
            await clearVoucher();
        }
    });
}

// Remove voucher button handler
if (removeVoucherBtn) {
    removeVoucherBtn.addEventListener("click", async () => {
        try {
            // Reset discount amount
            discountAmountInput.value = 0;

            // Clear voucher data
            voucherCodeInput.value = "";
            await clearVoucher();

            // Show success message
            alert("Voucher berhasil dihapus");
        } catch (error) {
            console.error("Error removing voucher:", error);
            alert("Gagal menghapus voucher");
        }
    });
}

// Clear voucher when input is empty
voucherCodeInput.addEventListener("input", async () => {
    if (!voucherCodeInput.value.trim()) {
        await clearVoucher();
    }
});

// Form submission handling
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    if (form) {
        form.addEventListener("submit", function (e) {
            // Update hidden fields before submission
            const subTotalInput = document.getElementById("sub_total");
            const grandTotalInput = document.getElementById("grand_total");

            if (subTotalInput) {
                subTotalInput.value = 0; // Will be calculated when services are added
            }
            if (grandTotalInput) {
                grandTotalInput.value = 0; // Will be calculated when services are added
            }
        });
    }
});

// Initialize on page load
document.addEventListener("DOMContentLoaded", function () {
    updateVoucherStatus(parseInt(discountAmountInput?.value) || 0);
});
