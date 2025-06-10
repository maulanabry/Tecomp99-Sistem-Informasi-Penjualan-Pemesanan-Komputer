// Utility function to format number as Rupiah currency
function formatRupiah(number) {
    return "Rp " + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Customer selection logic
const customerSelect = document.getElementById("customer_id");
const customerInfo = document.getElementById("customerInfo");
const customerEmail = document.getElementById("customerEmail");
const customerPhone = document.getElementById("customerPhone");
const customerFullAddress = document.getElementById("customerFullAddress");
const customerPostalCode = document.getElementById("customerPostalCode");

customerSelect.addEventListener("change", async function () {
    const selectedOption = this.options[this.selectedIndex];
    if (this.value) {
        // Update customer information
        customerEmail.textContent =
            selectedOption.getAttribute("data-email") || "-";
        customerPhone.textContent =
            selectedOption.getAttribute("data-contact") || "-";

        // Build full address
        const addressParts = [
            selectedOption.getAttribute("data-address"),
            selectedOption.getAttribute("data-subdistrict"),
            selectedOption.getAttribute("data-district"),
            selectedOption.getAttribute("data-city"),
            selectedOption.getAttribute("data-province"),
        ].filter((part) => part && part !== "-");

        customerFullAddress.textContent = addressParts.join(", ");
        customerPostalCode.textContent =
            selectedOption.getAttribute("data-postal") || "-";

        // Show customer info section
        customerInfo.classList.remove("hidden");
    } else {
        // Hide customer info section if no customer selected
        customerInfo.classList.add("hidden");

        // Reset shipping cost
        shippingCostInput.value = 0;
        document.getElementById("shipping_cost_hidden").value = 0;
        shippingCostDisplay.textContent = "Rp 0";
        await calculateTotals().catch((error) => {
            console.error(
                "Error calculating totals after customer change:",
                error
            );
        });
    }
});

// Order type logic
const orderTypeSelect = document.getElementById("order_type");
const shippingCostContainer = document.getElementById("shippingCostContainer");
const shippingCostInput = document.getElementById("shipping_cost");
const shippingCostDisplay = document.getElementById("shippingCostDisplay");

orderTypeSelect.addEventListener("change", async function () {
    if (this.value === "Pengiriman") {
        shippingCostContainer.classList.remove("hidden");
        checkOngkirBtn.parentElement.classList.remove("hidden");
    } else {
        shippingCostContainer.classList.add("hidden");
        checkOngkirBtn.parentElement.classList.add("hidden");
        shippingCostInput.value = 0;
        document.getElementById("shipping_cost_hidden").value = 0;
        shippingCostDisplay.textContent = "Rp 0";
        await calculateTotals().catch((error) => {
            console.error(
                "Error calculating totals after order type change:",
                error
            );
        });
    }
});

// Check Ongkir button handler
const checkOngkirBtn = document.getElementById("checkOngkirBtn");
const checkOngkirLoader = document.getElementById("checkOngkirLoader");

checkOngkirBtn.addEventListener("click", async function () {
    try {
        // Show loader
        checkOngkirLoader.classList.remove("hidden");
        checkOngkirBtn.querySelector("span").textContent = "Mengecek...";
        checkOngkirBtn.disabled = true;

        await updateShippingCost(true);
    } finally {
        // Hide loader and reset button
        checkOngkirLoader.classList.add("hidden");
        checkOngkirBtn.querySelector("span").textContent = "Cek Ongkir";
        checkOngkirBtn.disabled = false;
    }
});

shippingCostInput.addEventListener("input", function () {
    shippingCostDisplay.textContent = formatRupiah(parseInt(this.value) || 0);
    // Update hidden input value as well
    const hiddenInput = document.getElementById("shipping_cost_hidden");
    if (hiddenInput) {
        hiddenInput.value = this.value;
    }
    calculateTotals().catch((error) => {
        console.error("Error calculating totals:", error);
    });
});

// Also update hidden input when order type changes
orderTypeSelect.addEventListener("change", async function () {
    if (this.value === "Pengiriman") {
        shippingCostContainer.classList.remove("hidden");
        checkOngkirBtn.parentElement.classList.remove("hidden");
    } else {
        shippingCostContainer.classList.add("hidden");
        checkOngkirBtn.parentElement.classList.add("hidden");
        shippingCostInput.value = 0;
        shippingCostDisplay.textContent = "Rp 0";
        const hiddenInput = document.getElementById("shipping_cost_hidden");
        if (hiddenInput) {
            hiddenInput.value = 0;
        }
        await calculateTotals().catch((error) => {
            console.error(
                "Error calculating totals after order type change:",
                error
            );
        });
    }
});

// Product list management
const addProductBtn = document.getElementById("addProductBtn");
const addProductModal = document.getElementById("addProductModal");
const closeAddProductModalBtn = document.getElementById("closeAddProductModal");
const productItemsTableBody = document.getElementById("productItemsTableBody");
const itemsInput = document.getElementById("itemsInput");

addProductBtn.addEventListener("click", () => {
    addProductModal.classList.remove("hidden");
});

closeAddProductModalBtn.addEventListener("click", () => {
    addProductModal.classList.add("hidden");
});

// Add product from modal to product list
document.querySelectorAll(".add-product-btn").forEach((button) => {
    button.addEventListener("click", (e) => {
        const row = e.target.closest("tr");
        const productId = row.getAttribute("data-product-id");
        const productName = row.getAttribute("data-product-name");
        const productPrice = parseInt(row.getAttribute("data-product-price"));
        const quantityInput = row.querySelector(".quantity-input");
        let quantity = parseInt(quantityInput.value);

        if (isNaN(quantity) || quantity < 1) {
            alert("Kuantitas minimal 1.");
            return;
        }
        if (quantity > parseInt(quantityInput.max)) {
            alert("Kuantitas melebihi stok.");
            return;
        }

        // Add row to product items table
        const tr = document.createElement("tr");
        tr.className =
            "bg-white border-b dark:bg-gray-800 dark:border-gray-700";
        const productWeight =
            parseInt(row.getAttribute("data-product-weight")) || 0;
        tr.innerHTML = `
            <td class="px-6 py-4">${productName}</td>
            <td class="px-6 py-4 text-right quantity-cell" data-product-id="${productId}" data-quantity="${quantity}">${quantity}</td>
            <td class="px-6 py-4 text-right">${productWeight * quantity}</td>
            <td class="px-6 py-4 text-right">${formatRupiah(productPrice)}</td>
            <td class="px-6 py-4 text-right">${formatRupiah(
                productPrice * quantity
            )}</td>
            <td class="px-6 py-4 text-center">
                <button type="button" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800 remove-product-btn">
                    🗑️
                </button>
            </td>
        `;
        productItemsTableBody.appendChild(tr);

        // Add event listener for remove button
        tr.querySelector(".remove-product-btn").addEventListener(
            "click",
            async (event) => {
                const rowToRemove = event.target.closest("tr");
                if (rowToRemove) {
                    rowToRemove.remove();
                    await calculateTotals();
                }
            }
        );

        calculateTotals().catch(console.error);
        addProductModal.classList.add("hidden");
    });
});

// Promo code logic
const promoCodeInput = document.getElementById("promo_code");
const promoIdInput = document.getElementById("promo_id");
const promoTypeInput = document.getElementById("promo_type");
const promoValueInput = document.getElementById("promo_value");
const applyPromoBtn = document.getElementById("applyPromoBtn");
const promoInfo = document.getElementById("promoInfo");
const promoSuccess = document.getElementById("promoSuccess");
const promoError = document.getElementById("promoError");
const discountDisplay = document.getElementById("discountDisplay");

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
    try {
        await calculateTotals();
    } catch (error) {
        console.error("Error calculating totals after clearing promo:", error);
    }
}

promoCodeInput.addEventListener("input", async () => {
    if (!promoCodeInput.value.trim()) {
        await clearPromo().catch((error) => {
            console.error("Error clearing promo:", error);
        });
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
    document.querySelectorAll("#productItemsTableBody tr").forEach((row) => {
        const quantityCell = row.querySelector(".quantity-cell");
        const qty = parseInt(quantityCell.dataset.quantity);
        const unitPriceText = row.children[3].textContent.replace(/[^\d]/g, "");
        const unitPrice = parseInt(unitPriceText) || 0;
        subtotal += qty * unitPrice;
    });

    try {
        const response = await fetch("/admin/order-products/validate-promo", {
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

        console.log("Promo validation request:", {
            promo_code: code,
            subtotal: subtotal,
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || "Gagal memeriksa kode promo");
        }

        if (!data.success) {
            throw new Error(data.message);
        }

        console.log("Promo validation response:", data);

        // Store promo data and calculated discount
        promoIdInput.value = data.promo_id;
        promoTypeInput.value = data.discount_type;
        promoValueInput.value = data.discount_value;

        console.log("Stored promo values:", {
            id: promoIdInput.value,
            type: promoTypeInput.value,
            value: promoValueInput.value,
            calculatedDiscount: data.discount,
        });

        // Show success with discount info
        const discountInfo = formatRupiah(data.discount);
        console.log("Formatted discount:", discountInfo);

        showPromoSuccess(
            `Promo "${data.promo_name}" berhasil diterapkan! (${discountInfo})`
        );
        await calculateTotals();
    } catch (error) {
        console.error("Error applying promo:", error);
        showPromoError(error.message);
        clearPromo();
    }
});

// Calculate total weight
function calculateTotalWeight() {
    let totalWeight = 0;
    document.querySelectorAll("#productItemsTableBody tr").forEach((row) => {
        const weightCell = row.querySelector("td:nth-child(3)");
        const weight = parseInt(weightCell.textContent) || 0;
        totalWeight += weight;
    });
    return totalWeight;
}

// Fetch destination data from postal code
async function getDestinationData(postalCode) {
    try {
        console.log("Mencari destinasi untuk kode pos:", postalCode);
        const response = await fetch(
            `/api/public/search-destination?search=${encodeURIComponent(
                postalCode
            )}&limit=1`,
            {
                headers: {
                    Accept: "application/json",
                },
            }
        );

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(
                errorData.message || `HTTP error! status: ${response.status}`
            );
        }

        console.log("Response status:", response.status);
        const data = await response.json();
        console.log("Data destinasi:", data);

        if (!data || !Array.isArray(data) || data.length === 0) {
            throw new Error(
                `Kode pos ${postalCode} tidak ditemukan. Pastikan kode pos yang digunakan valid.`
            );
        }

        console.log("Destinasi ditemukan:", data[0]);
        return data[0];
    } catch (error) {
        console.error("Gagal mengambil data tujuan:", error);
        throw error;
    }
}

// Calculate shipping cost
async function calculateShippingCost(destination, weight) {
    try {
        console.log("Menghitung ongkir dengan params:", {
            destination,
            weight,
        });

        // Create URL-encoded form data
        const params = new URLSearchParams();
        params.append("destination", destination);
        params.append("weight", weight);
        params.append("courier", "jne");
        params.append("service", "reg");

        const response = await fetch("/api/public/check-ongkir", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
                Accept: "application/json",
            },
            body: params,
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(
                errorData.message || `HTTP error! status: ${response.status}`
            );
        }

        console.log("Response status:", response.status);
        const data = await response.json();
        console.log("Data ongkir:", data);

        // Find JNE REG service
        const regService = data.find(
            (service) => service.code === "jne" && service.service === "REG"
        );

        if (!regService) {
            throw new Error("Layanan JNE REG tidak tersedia untuk rute ini");
        }

        const cost = regService.cost;
        console.log("Biaya pengiriman:", cost);
        return cost;
    } catch (error) {
        console.error("Gagal menghitung ongkos kirim:", error);
        alert(`Gagal menghitung ongkos kirim: ${error.message}`);
        return 0;
    }
}

// Update shipping cost
async function updateShippingCost(isButtonClick = false) {
    const checkOngkirBtn = document.getElementById("checkOngkirBtn");
    const checkOngkirLoader = document.getElementById("checkOngkirLoader");

    try {
        // Only proceed if it's a button click or resetting cost for non-delivery
        if (!isButtonClick && orderTypeSelect.value === "Pengiriman") {
            return;
        }

        if (orderTypeSelect.value !== "Pengiriman") {
            shippingCostInput.value = 0;
            shippingCostDisplay.textContent = "Rp 0";
            await calculateTotals();
            return;
        }

        // Validate customer selection
        if (!customerSelect.value) {
            alert("Silakan pilih pelanggan terlebih dahulu.");
            return;
        }

        const selectedOption =
            customerSelect.options[customerSelect.selectedIndex];
        const postalCode = selectedOption.getAttribute("data-postal");
        console.log("Kode pos pelanggan:", postalCode);

        if (!postalCode || postalCode === "-") {
            alert(
                "Alamat pelanggan tidak lengkap. Pastikan alamat dan kode pos telah diisi dengan benar."
            );
            return;
        }

        // Validate products
        const weight = calculateTotalWeight();
        console.log("Total berat:", weight);
        if (weight === 0) {
            alert(
                "Belum ada produk yang dipilih. Silakan tambahkan produk terlebih dahulu."
            );
            return;
        }

        // Show loading indicator
        if (checkOngkirBtn && checkOngkirLoader) {
            checkOngkirLoader.classList.remove("hidden");
            checkOngkirBtn.disabled = true;
            checkOngkirBtn.querySelector("span").textContent = "Mengecek...";
        }

        try {
            // Get destination data and calculate shipping cost
            const destinationData = await getDestinationData(postalCode);
            console.log("Data destinasi:", destinationData);

            const weightInGrams = Math.ceil(weight);
            const shippingCost = await calculateShippingCost(
                destinationData.id,
                weightInGrams
            );

            if (!shippingCost) {
                throw new Error(
                    "Gagal mendapatkan biaya pengiriman dari JNE. Silakan coba lagi."
                );
            }

            console.log("Biaya pengiriman:", shippingCost);
            shippingCostInput.value = shippingCost;
            document.getElementById("shipping_cost_hidden").value =
                shippingCost;
            shippingCostDisplay.textContent = `Rp ${shippingCost.toLocaleString(
                "id-ID"
            )}`;
            await calculateTotals();
        } catch (error) {
            console.error("Error dalam cek ongkir:", error);
            throw new Error(`Gagal cek ongkir: ${error.message}`);
        }
    } catch (error) {
        console.error("Error dalam updateShippingCost:", error);
        alert(
            error.message ||
                "Terjadi kesalahan saat menghitung ongkos kirim. Silakan coba lagi."
        );

        // Reset shipping cost on error
        shippingCostInput.value = 0;
        shippingCostDisplay.textContent = "Rp 0";
        await calculateTotals();
    } finally {
        // Hide loading indicator and reset button
        if (checkOngkirBtn && checkOngkirLoader) {
            checkOngkirLoader.classList.add("hidden");
            checkOngkirBtn.disabled = false;
            checkOngkirBtn.querySelector("span").textContent = "Cek Ongkir";
        }
    }
}

// Update display values
function updateDisplays(subtotal, discount, shippingCost) {
    const grandTotal = subtotal - discount + shippingCost;

    console.log("Updating displays with:", {
        subtotal,
        discount,
        shippingCost,
        grandTotal,
    });

    document.getElementById("subtotalDisplay").textContent =
        formatRupiah(subtotal);
    discountDisplay.textContent = formatRupiah(discount);
    shippingCostDisplay.textContent = formatRupiah(shippingCost);
    document.getElementById("grandTotalDisplay").textContent =
        formatRupiah(grandTotal);
}

// Calculate totals function
async function calculateTotals() {
    // Calculate subtotal
    let subtotal = 0;
    document.querySelectorAll("#productItemsTableBody tr").forEach((row) => {
        const quantityCell = row.querySelector(".quantity-cell");
        const qty = parseInt(quantityCell.dataset.quantity);
        const unitPriceText = row.children[3].textContent.replace(/[^\d]/g, "");
        const unitPrice = parseInt(unitPriceText) || 0;
        subtotal += qty * unitPrice;
    });

    const shippingCost = parseInt(shippingCostInput.value) || 0;

    // If no promo is applied, update displays immediately
    if (
        !promoIdInput.value ||
        !promoTypeInput.value ||
        !promoValueInput.value
    ) {
        updateDisplays(subtotal, 0, shippingCost);
        return;
    }

    // Re-validate promo with current subtotal
    try {
        const response = await fetch("/admin/order-products/validate-promo", {
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
            console.log("Server calculated discount:", data.discount);
            updateDisplays(subtotal, data.discount, shippingCost);
        } else {
            console.error("Failed to validate promo:", data.message);
            updateDisplays(subtotal, 0, shippingCost);
            await clearPromo();
        }
    } catch (error) {
        console.error("Error validating promo:", error);
        updateDisplays(subtotal, 0, shippingCost);
        await clearPromo();
    }
}

// On form submit, validate and prepare data
document
    .getElementById("orderForm")
    .addEventListener("submit", async function (e) {
        e.preventDefault();

        // Validate at least one product
        if (
            document.querySelectorAll("#productItemsTableBody tr").length === 0
        ) {
            alert("Harap tambahkan setidaknya satu produk ke dalam pesanan.");
            return;
        }

        // Prepare items JSON
        const items = [];
        document
            .querySelectorAll("#productItemsTableBody tr")
            .forEach((row) => {
                const productId =
                    row.querySelector(".quantity-cell").dataset.productId;
                const qty = parseInt(
                    row.querySelector(".quantity-cell").dataset.quantity
                );
                const unitPriceText = row.children[3].textContent.replace(
                    /[^\d]/g,
                    ""
                );
                const unitPrice = parseInt(unitPriceText) || 0;
                items.push({
                    product_id: productId,
                    quantity: qty,
                    unit_price: unitPrice,
                    total: qty * unitPrice,
                });
            });
        itemsInput.value = JSON.stringify(items);

        // Submit the form
        this.submit();
    });
