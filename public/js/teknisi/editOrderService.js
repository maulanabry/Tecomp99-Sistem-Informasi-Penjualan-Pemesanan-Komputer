// Utility function untuk format angka ke Rupiah
function formatRupiah(number) {
    return "Rp " + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// DOM Elements
const itemsTableBody = document.getElementById("itemsTableBody");
const itemsInput = document.getElementById("itemsInput");
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

// Listen for Livewire events for adding items
document.addEventListener("livewire:load", function () {
    Livewire.on("productSelected", (productId) => {
        fetchProductById(productId).then((product) => {
            addItemToTable({
                id: product.product_id,
                name: product.name,
                price: product.price,
                quantity: 1,
                type: "product",
            });
        });
    });

    Livewire.on("serviceSelected", (serviceId) => {
        fetchServiceById(serviceId).then((service) => {
            addItemToTable({
                id: service.service_id,
                name: service.name,
                price: service.price,
                quantity: 1,
                type: "service",
            });
        });
    });

    // Re-attach event listeners after Livewire updates DOM
    Livewire.hook("message.processed", (message, component) => {
        attachAddProductListeners();
        attachAddServiceListeners();
    });
});

// Fetch product details by ID
async function fetchProductById(productId) {
    const response = await fetch(`/api/products/${productId}`);
    if (!response.ok) {
        alert("Gagal mengambil data produk");
        throw new Error("Failed to fetch product");
    }
    return await response.json();
}

// Fetch service details by ID
async function fetchServiceById(serviceId) {
    const response = await fetch(`/api/services/${serviceId}`);
    if (!response.ok) {
        alert("Gagal mengambil data servis");
        throw new Error("Failed to fetch service");
    }
    return await response.json();
}

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
function attachAddProductListeners() {
    document.querySelectorAll(".add-product-btn").forEach((button) => {
        button.addEventListener("click", function () {
            const card = this.closest("[data-product-id]");
            if (!card) {
                alert("Error: Product card data not found.");
                return;
            }
            const productId = card.dataset.productId;
            const productName = card.dataset.productName;
            const price = parseInt(card.dataset.productPrice);
            const quantityInput = card.querySelector(".quantity-input");
            const quantity = quantityInput ? parseInt(quantityInput.value) : 1;
            const maxStock = quantityInput
                ? parseInt(quantityInput.max)
                : Infinity;

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
        });
    });
}

attachAddProductListeners();

// Event Listeners untuk tambah servis
function attachAddServiceListeners() {
    document.querySelectorAll(".add-service-btn").forEach((button) => {
        button.addEventListener("click", function () {
            const card = this.closest("[data-service-id]");
            if (!card) {
                alert("Error: Service card data not found.");
                return;
            }
            const serviceId = card.dataset.serviceId;
            const serviceName = card.dataset.serviceName;
            const price = parseInt(card.dataset.servicePrice);
            const quantityInput = card.querySelector(".quantity-input");
            const quantity = quantityInput ? parseInt(quantityInput.value) : 1;

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
        });
    });
}

attachAddServiceListeners();

// Fungsi untuk menambahkan item ke tabel
function addItemToTable(item) {
    const tr = document.createElement("tr");
    tr.className = "bg-white border-b dark:bg-gray-800 dark:border-gray-700";
    tr.dataset.type = item.type;
    tr.dataset.price = item.price;

    if (item.orderServiceItemId) {
        tr.dataset.orderServiceItemId = item.orderServiceItemId;
    }

    if (item.type === "service") {
        tr.dataset.serviceId = item.id;
    } else {
        tr.dataset.productId = item.id;
    }

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

// Serialize items for form submission
function serializeItems() {
    const items = [];
    document.querySelectorAll("#itemsTableBody tr").forEach((row) => {
        const type = row.dataset.type;
        const orderServiceItemId = row.dataset.orderServiceItemId || null;
        const price = parseInt(row.dataset.price);
        const quantity = parseInt(row.querySelector(".quantity-input").value);

        let itemId = null;
        if (type === "service") {
            itemId = row.dataset.serviceId;
        } else {
            itemId = row.dataset.productId;
        }

        items.push({
            order_service_item_id: orderServiceItemId,
            item_type:
                type === "service"
                    ? "App\\Models\\Service"
                    : "App\\Models\\Product",
            item_id: itemId,
            quantity: quantity,
            price: price,
            item_total: quantity * price,
        });
    });
    return items;
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

    let subtotal = 0;
    document.querySelectorAll("#itemsTableBody tr").forEach((row) => {
        const price = parseInt(row.dataset.price);
        const quantity = parseInt(row.querySelector(".quantity-input").value);
        subtotal += price * quantity;
    });

    try {
        const response = await fetch("/teknisi/order-services/validate-promo", {
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

    if (
        !promoIdInput.value ||
        !promoTypeInput.value ||
        !promoValueInput.value
    ) {
        updateDisplays(subtotal, 0);
        return;
    }

    try {
        const response = await fetch("/teknisi/order-services/validate-promo", {
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
document.getElementById("orderForm").addEventListener("submit", function (e) {
    if (document.querySelectorAll("#itemsTableBody tr").length === 0) {
        alert("Harap tambahkan setidaknya satu item ke dalam pesanan.");
        e.preventDefault();
        return;
    }

    const items = serializeItems();
    itemsInput.value = JSON.stringify(items);

    const subTotalInput = document.getElementById("sub_total");
    const discountAmountInput = document.getElementById("discount_amount");

    const subtotal =
        parseInt(subtotalDisplay.textContent.replace(/\D/g, "")) || 0;
    const discount =
        parseInt(discountDisplay.textContent.replace(/\D/g, "")) || 0;

    subTotalInput.value = subtotal;
    discountAmountInput.value = discount;
});

// Initialize totals on page load
calculateTotals();
