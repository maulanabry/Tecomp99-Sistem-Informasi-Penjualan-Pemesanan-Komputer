// Utility function untuk format angka ke Rupiah
function formatRupiah(number) {
    return "Rp " + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// DOM Elements
const itemsTableBody = document.getElementById("itemsTableBody");
const itemsInput = document.getElementById("itemsInput");
const addProductBtn = document.getElementById("addProductBtn");
const addServiceBtn = document.getElementById("addServiceBtn");
const addProductModal = document.getElementById("addProductModal");
const addServiceModal = document.getElementById("addServiceModal");
const closeAddProductModal = document.getElementById("closeAddProductModal");
const closeAddServiceModal = document.getElementById("closeAddServiceModal");
const promoCodeInput = document.getElementById("promo_code");
const promoIdInput = document.getElementById("promo_id");
const promoTypeInput = document.getElementById("promo_type");
const promoValueInput = document.getElementById("promo_value");
const applyPromoBtn = document.getElementById("applyPromoBtn");
const promoInfo = document.getElementById("promoInfo");
const promoSuccess = document.getElementById("promoSuccess");
const promoError = document.getElementById("promoError");
const subtotalDisplay = document.getElementById("subtotalDisplay");
const discountDisplay = document.getElementById("discountDisplay");
const grandTotalDisplay = document.getElementById("grandTotalDisplay");

// Event Listeners untuk Modal
addProductBtn.addEventListener("click", () =>
    addProductModal.classList.remove("hidden")
);
addServiceBtn.addEventListener("click", () =>
    addServiceModal.classList.remove("hidden")
);
closeAddProductModal.addEventListener("click", () =>
    addProductModal.classList.add("hidden")
);
closeAddServiceModal.addEventListener("click", () =>
    addServiceModal.classList.add("hidden")
);

// Event Listener untuk quantity inputs yang sudah ada
document.querySelectorAll(".quantity-input").forEach((input) => {
    input.addEventListener("change", function () {
        updateRowTotal(this);
        calculateTotals();
    });
});

// Event Listener untuk tombol hapus yang sudah ada
document.querySelectorAll(".remove-item-btn").forEach((button) => {
    button.addEventListener("click", function () {
        this.closest("tr").remove();
        calculateTotals();
    });
});

// Fungsi untuk update total per baris
function updateRowTotal(quantityInput) {
    const row = quantityInput.closest("tr");
    const price = parseInt(row.dataset.price);
    const quantity = parseInt(quantityInput.value);
    const total = price * quantity;
    row.querySelector(".item-total").textContent = formatRupiah(total);
}

// Event Listeners untuk tambah produk
document.querySelectorAll(".add-product-btn").forEach((button) => {
    button.addEventListener("click", function () {
        const row = this.closest("tr");
        const productId = row.dataset.productId;
        const productName = row.dataset.productName;
        const price = parseInt(row.dataset.productPrice);
        const quantityInput = row.querySelector(".quantity-input");
        const quantity = parseInt(quantityInput.value);
        const maxStock = parseInt(quantityInput.max);

        if (isNaN(quantity) || quantity < 1) {
            alert("Kuantitas minimal 1");
            return;
        }

        if (quantity > maxStock) {
            alert("Kuantitas melebihi stok tersedia");
            return;
        }

        addItemToTable({
            id: productId,
            name: productName,
            price: price,
            quantity: quantity,
            type: "product",
        });

        addProductModal.classList.add("hidden");
    });
});

// Event Listeners untuk tambah servis
document.querySelectorAll(".add-service-btn").forEach((button) => {
    button.addEventListener("click", function () {
        const row = this.closest("tr");
        const serviceId = row.dataset.serviceId;
        const serviceName = row.dataset.serviceName;
        const price = parseInt(row.dataset.servicePrice);
        const quantityInput = row.querySelector(".quantity-input");
        const quantity = parseInt(quantityInput.value);

        if (isNaN(quantity) || quantity < 1) {
            alert("Kuantitas minimal 1");
            return;
        }

        addItemToTable({
            id: serviceId,
            name: serviceName,
            price: price,
            quantity: quantity,
            type: "service",
        });

        addServiceModal.classList.add("hidden");
    });
});

// Fungsi untuk menambahkan item ke tabel
function addItemToTable(item) {
    const tr = document.createElement("tr");
    tr.className = "bg-white border-b dark:bg-gray-800 dark:border-gray-700";
    tr.dataset.type = item.type;
    tr.dataset.price = item.price;
    tr.dataset[`${item.type}Id`] = item.id;

    tr.innerHTML = `
        <td class="px-6 py-4">${item.name}</td>
        <td class="px-6 py-4">${
            item.type === "service" ? "Jasa" : "Produk"
        }</td>
        <td class="px-6 py-4 text-right">${formatRupiah(item.price)}</td>
        <td class="px-6 py-4 text-right">
            <input type="number" 
                   class="quantity-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-20 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                   value="${item.quantity}" 
                   min="1" />
        </td>
        <td class="px-6 py-4 text-right item-total">${formatRupiah(
            item.price * item.quantity
        )}</td>
        <td class="px-6 py-4 text-center">
            <button type="button" class="text-red-600 hover:text-red-900 remove-item-btn">
                <span class="sr-only">Hapus item</span>
                🗑️
            </button>
        </td>
    `;

    // Add event listeners to new elements
    const quantityInput = tr.querySelector(".quantity-input");
    quantityInput.addEventListener("change", function () {
        updateRowTotal(this);
        calculateTotals();
    });

    const removeBtn = tr.querySelector(".remove-item-btn");
    removeBtn.addEventListener("click", function () {
        tr.remove();
        calculateTotals();
    });

    itemsTableBody.appendChild(tr);
    calculateTotals();
}

// Promo code handling
function showPromoError(message) {
    promoInfo.classList.remove("hidden");
    promoSuccess.classList.add("hidden");
    promoError.classList.remove("hidden");
    promoError.textContent = message;
}

function showPromoSuccess(message) {
    promoInfo.classList.remove("hidden");
    promoError.classList.add("hidden");
    promoSuccess.classList.remove("hidden");
    promoSuccess.textContent = message;
}

async function clearPromo() {
    promoIdInput.value = "";
    promoTypeInput.value = "";
    promoValueInput.value = "";
    promoInfo.classList.add("hidden");
    await calculateTotals();
}

promoCodeInput.addEventListener("input", async () => {
    if (!promoCodeInput.value.trim()) {
        await clearPromo();
    }
});

applyPromoBtn.addEventListener("click", async () => {
    const code = promoCodeInput.value.trim();
    if (!code) {
        showPromoError("Masukkan kode promo");
        return;
    }

    // Calculate current subtotal
    let subtotal = 0;
    document.querySelectorAll("#itemsTableBody tr").forEach((row) => {
        const price = parseInt(row.dataset.price);
        const quantity = parseInt(row.querySelector(".quantity-input").value);
        subtotal += price * quantity;
    });

    try {
        const response = await fetch("/admin/order-services/validate-promo", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
                Accept: "application/json",
            },
            body: JSON.stringify({
                promo_code: code,
                subtotal: subtotal,
            }),
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || "Gagal memeriksa kode promo");
        }

        if (!data.success) {
            throw new Error(data.message);
        }

        // Store promo data
        promoIdInput.value = data.promo_id;
        promoTypeInput.value = data.discount_type;
        promoValueInput.value = data.discount_value;

        // Show success message
        showPromoSuccess(
            `Promo "${data.promo_name}" berhasil diterapkan! (${formatRupiah(
                data.discount
            )})`
        );
        await calculateTotals();
    } catch (error) {
        console.error("Error applying promo:", error);
        showPromoError(error.message);
        await clearPromo();
    }
});

// Calculate totals
async function calculateTotals() {
    let subtotal = 0;
    document.querySelectorAll("#itemsTableBody tr").forEach((row) => {
        const price = parseInt(row.dataset.price);
        const quantity = parseInt(row.querySelector(".quantity-input").value);
        subtotal += price * quantity;
    });

    // If no promo is applied, update displays immediately
    if (
        !promoIdInput.value ||
        !promoTypeInput.value ||
        !promoValueInput.value
    ) {
        updateDisplays(subtotal, 0);
        return;
    }

    // Re-validate promo with current subtotal
    try {
        const response = await fetch("/admin/order-services/validate-promo", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
                Accept: "application/json",
            },
            body: JSON.stringify({
                promo_code: promoCodeInput.value.trim(),
                subtotal: subtotal,
            }),
        });

        const data = await response.json();
        if (response.ok && data.success) {
            updateDisplays(subtotal, data.discount);
        } else {
            updateDisplays(subtotal, 0);
            await clearPromo();
        }
    } catch (error) {
        console.error("Error validating promo:", error);
        updateDisplays(subtotal, 0);
        await clearPromo();
    }
}

// Update display values
function updateDisplays(subtotal, discount) {
    const grandTotal = subtotal - discount;

    subtotalDisplay.textContent = formatRupiah(subtotal);
    discountDisplay.textContent = formatRupiah(discount);
    grandTotalDisplay.textContent = formatRupiah(grandTotal);
}

// Form submission handling
document
    .getElementById("orderForm")
    .addEventListener("submit", async function (e) {
        e.preventDefault();

        // Validate at least one item
        if (document.querySelectorAll("#itemsTableBody tr").length === 0) {
            alert("Harap tambahkan setidaknya satu item ke dalam pesanan.");
            return;
        }

        // Prepare items JSON
        const items = [];
        document.querySelectorAll("#itemsTableBody tr").forEach((row) => {
            const type = row.dataset.type;
            const id = row.dataset[`${type}Id`];
            const price = parseInt(row.dataset.price);
            const quantity = parseInt(
                row.querySelector(".quantity-input").value
            );

            items.push({
                type: type,
                [`${type}_id`]: id,
                quantity: quantity,
                price: price,
                total: quantity * price,
            });
        });

        itemsInput.value = JSON.stringify(items);

        // Submit the form
        this.submit();
    });

// Initialize totals on page load
calculateTotals();
