// Utility Functions
const utils = {
    // Format number as Rupiah currency
    formatRupiah(number) {
        try {
            const value = Number(number);
            if (isNaN(value)) {
                console.warn(
                    "Invalid number provided to formatRupiah:",
                    number
                );
                return "Rp 0";
            }
            return `Rp ${value.toLocaleString("id-ID")}`;
        } catch (error) {
            console.error("Error formatting Rupiah:", error);
            return "Rp 0";
        }
    },

    // Extract number from string (e.g., "Rp 1.000" -> 1000)
    extractNumber(str) {
        try {
            return Number(str.replace(/[^\d]/g, "")) || 0;
        } catch (error) {
            console.error("Error extracting number:", error);
            return 0;
        }
    },

    // Show loading state
    showLoading($button, loadingText = "Loading...") {
        $button.prop("disabled", true).find("span").text(loadingText);
    },

    // Hide loading state
    hideLoading($button, originalText = "Submit") {
        $button.prop("disabled", false).find("span").text(originalText);
    },

    // Show error message
    showError(message) {
        // Create toast notification
        const toast = document.createElement("div");
        toast.className =
            "fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in-up";
        toast.innerHTML = `
            <div class="flex items-center space-x-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(toast);
        console.error(message);

        // Remove toast after 5 seconds
        setTimeout(() => {
            toast.classList.add("animate-fade-out-down");
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    },

    // Show success message
    showSuccess(message) {
        // Create toast notification
        const toast = document.createElement("div");
        toast.className =
            "fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in-up";
        toast.innerHTML = `
            <div class="flex items-center space-x-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(toast);

        // Remove toast after 5 seconds
        setTimeout(() => {
            toast.classList.add("animate-fade-out-down");
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    },
};

const formatRupiah = utils.formatRupiah;

// Initialize customer selection
$(document).ready(function () {
    const $customerId = $("#customer_id");
    const $customerInfo = $("#customer-info");
    const $customerEmail = $("#customerEmail");
    const $customerPhone = $("#customerPhone");
    const $customerAddress = $("#customerFullAddress");
    const $customerPostalCode = $("#customerPostalCode");

    $customerId.select2({
        placeholder: "Cari nama pelanggan...",
        allowClear: true,
        width: "100%",
        dropdownParent: $customerId.parent(),
    });

    $customerId.on("change", async function () {
        const $selected = $(this).find(":selected");
        const customerData = {
            name: $selected.data("name"),
            contact: $selected.data("contact"),
            email: $selected.data("email"),
            address: $selected.data("address"),
            postalCode: $selected.data("postal-code"),
        };

        if (Object.values(customerData).some((value) => value)) {
            $customerEmail.text(customerData.email || "-");
            $customerPhone.text(customerData.contact || "-");
            $customerAddress.text(customerData.address || "-");
            $customerPostalCode.text(customerData.postalCode || "-");
            $customerInfo.removeClass("hidden");
        } else {
            $customerInfo.addClass("hidden");
            $("#shipping_cost").val(0);
            $("#shipping_cost_hidden").val(0);
            $("#shippingCostDisplay").text("Rp 0");

            await calculateTotals().catch((error) => {
                console.error(
                    "Error calculating totals after customer change:",
                    error
                );
            });
        }
    });

    // Initialize calculations on page load
    calculateTotals().catch(console.error);
});

// Order type and shipping cost logic
$("#order_type").on("change", async function () {
    const isDelivery = $(this).val() === "Pengiriman";

    if (isDelivery) {
        $("#shippingCostContainer").removeClass("hidden");
        $("#checkOngkirBtn").parent().removeClass("hidden");
    } else {
        $("#shippingCostContainer").addClass("hidden");
        $("#checkOngkirBtn").parent().addClass("hidden");
        $("#shipping_cost").val(0);
        $("#shipping_cost_hidden").val(0);
        $("#shippingCostDisplay").text("Rp 0");

        await calculateTotals().catch((error) => {
            console.error(
                "Error calculating totals after order type change:",
                error
            );
        });
    }
});

// Handle shipping cost input changes
$("#shipping_cost").on("input", function () {
    const cost = parseInt($(this).val()) || 0;
    $("#shippingCostDisplay").text(formatRupiah(cost));
    $("#shipping_cost_hidden").val(cost);

    calculateTotals().catch((error) => {
        console.error("Error calculating totals:", error);
    });
});

// Handle check ongkir button clicks
$("#checkOngkirBtn").on("click", async function () {
    try {
        await updateShippingCost(true);
    } catch (error) {
        console.error("Error in check ongkir click:", error);
    }
});

// Product list management
const productList = {
    // Cache jQuery selectors
    $tableBody: $("#productItemsTableBody"),
    $itemsInput: $("#itemsInput"),

    // Initialize product list handling
    init() {
        this.bindEvents();
    },

    // Bind event handlers
    bindEvents() {
        $(document).on("click", ".add-product-btn", this.handleAddProduct);
        $(document).on("change", ".quantity-input", this.handleQuantityChange);
        $(document).on(
            "click",
            ".remove-product-btn",
            this.handleRemoveProduct
        );
    },

    // Create product row HTML
    createProductRow(product) {
        return $("<tr>", {
            id: `product-row-${product.id}`,
            class: "bg-white border-b dark:bg-gray-800 dark:border-gray-700",
            "data-base-weight": product.weight,
        }).html(`
            <td class="px-6 py-4">${product.name}</td>
            <td class="px-6 py-4 text-right">
                <input type="number" 
                    class="quantity-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-20 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                    value="1" 
                    min="1" 
                    data-product-id="${product.id}"
                >
            </td>
            <td class="px-6 py-4 text-right weight-cell">${product.weight}</td>
            <td class="px-6 py-4 text-right">${formatRupiah(product.price)}</td>
            <td class="px-6 py-4 text-right total-cell">${formatRupiah(
                product.price
            )}</td>
            <td class="px-6 py-4 text-center">
                <button type="button" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800 remove-product-btn">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </td>
        `);
    },

    // Handle adding a product
    handleAddProduct(e) {
        const $card = $(this).closest("[data-product-id]");
        const productWeight = parseInt($card.data("product-weight"));

        // Validate product weight
        if (isNaN(productWeight) || productWeight <= 0) {
            utils.showError(
                "Produk ini tidak memiliki informasi berat yang valid. Silakan hubungi admin."
            );
            return;
        }

        const product = {
            id: $card.data("product-id"),
            name: $card.data("product-name"),
            price: parseInt($card.data("product-price")),
            weight: productWeight,
        };

        // Check if product is already in the list
        if ($(`#product-row-${product.id}`).length > 0) {
            utils.showError("Produk ini sudah ditambahkan ke daftar.");
            return;
        }

        // Add product to list
        productList.$tableBody.append(productList.createProductRow(product));
        calculateTotals().catch(console.error);
    },

    // Handle quantity changes
    handleQuantityChange() {
        const $row = $(this).closest("tr");
        const quantity = parseInt($(this).val()) || 0;
        const price = utils.extractNumber($row.find("td:eq(3)").text());
        const baseWeight = parseInt($row.data("base-weight")) || 0;

        // Update weight cell with calculated weight
        const totalWeight = baseWeight * quantity;
        $row.find(".weight-cell").text(totalWeight);

        // Update total price
        $row.find(".total-cell").text(formatRupiah(price * quantity));

        calculateTotals().catch(console.error);
    },

    // Handle removing a product
    async handleRemoveProduct() {
        $(this).closest("tr").remove();
        await calculateTotals();
    },
};

// Initialize product list management
productList.init();

// Promo code management
const promoManager = {
    // Cache jQuery selectors
    $code: $("#promo_code"),
    $id: $("#promo_id"),
    $type: $("#promo_type"),
    $value: $("#promo_value"),
    $info: $("#promoInfo"),
    $success: $("#promoSuccess"),
    $error: $("#promoError"),
    $applyBtn: $("#applyPromoBtn"),

    // Initialize promo handling
    init() {
        this.bindEvents();
    },

    // Bind event handlers
    bindEvents() {
        this.$code.on("input", () => this.handlePromoInput());
        this.$applyBtn.on("click", () => this.handleApplyPromo());
    },

    // Show error message
    showError(message) {
        this.$info.removeClass("hidden");
        this.$success.addClass("hidden");
        this.$error.removeClass("hidden").text(message);
    },

    // Show success message
    showSuccess(message) {
        this.$info.removeClass("hidden");
        this.$error.addClass("hidden");
        this.$success.removeClass("hidden").text(message);
    },

    // Clear promo data
    async clearPromo() {
        this.$id.val("");
        this.$type.val("");
        this.$value.val("");
        this.$info.addClass("hidden");
        try {
            await calculateTotals();
        } catch (error) {
            console.error(
                "Error calculating totals after clearing promo:",
                error
            );
        }
    },

    // Calculate current subtotal
    calculateSubtotal() {
        let subtotal = 0;
        $("#productItemsTableBody tr").each(function () {
            const qty = parseInt($(this).find(".quantity-input").val()) || 0;
            const unitPrice = utils.extractNumber(
                $(this).find("td:eq(3)").text()
            );
            subtotal += qty * unitPrice;
        });
        return subtotal;
    },

    // Handle promo code input changes
    async handlePromoInput() {
        if (!this.$code.val().trim()) {
            await this.clearPromo();
        }
    },

    // Handle apply promo button clicks
    async handleApplyPromo() {
        try {
            const code = this.$code.val().trim();
            if (!code) {
                this.showError("Masukkan kode promo");
                return;
            }

            utils.showLoading(this.$applyBtn, "Memeriksa...");

            const response = await $.ajax({
                url: "/admin/order-products/validate-voucher",
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                    Accept: "application/json",
                },
                data: JSON.stringify({
                    voucher_code: code,
                    subtotal: this.calculateSubtotal(),
                }),
            });

            if (!response.success) {
                throw new Error(response.message);
            }

            // Store promo data
            this.$id.val(response.promo_id);
            this.$type.val(response.discount_type);
            this.$value.val(response.discount_value);

            // Show success message
            this.showSuccess(
                `Promo "${
                    response.promo_name
                }" berhasil diterapkan! (${formatRupiah(response.discount)})`
            );
            await calculateTotals();
        } catch (error) {
            console.error("Error applying promo:", error);
            this.showError(error.message);
            await this.clearPromo();
        } finally {
            utils.hideLoading(this.$applyBtn, "Terapkan");
        }
    },
};

// Initialize promo manager
promoManager.init();

// Discount Management
const discountManager = {
    // Cache jQuery selectors
    $discountAmount: $("#discount_amount"),
    $voucherSection: $("#voucherStatusSection"),
    $voucherDiscountText: $("#voucherDiscountText"),
    $removeVoucherBtn: $("#removeVoucherBtn"),

    // Initialize discount handling
    init() {
        this.bindEvents();
    },

    // Bind event handlers
    bindEvents() {
        this.$discountAmount.on("input", () => this.handleDiscountChange());
        this.$removeVoucherBtn.on("click", () => this.handleRemoveVoucher());
    },

    // Handle discount amount input changes
    async handleDiscountChange() {
        try {
            const discount = parseInt(this.$discountAmount.val()) || 0;
            await calculateTotals();
            this.updateVoucherStatus(discount);
        } catch (error) {
            console.error("Error handling discount change:", error);
        }
    },

    // Handle remove voucher button click
    async handleRemoveVoucher() {
        try {
            // Reset discount amount
            this.$discountAmount.val(0);

            // Clear promo data
            $("#promo_code").val("");
            $("#promo_id").val("");
            $("#promo_type").val("");
            $("#promo_value").val("");
            $("#promoInfo").addClass("hidden");

            // Recalculate totals
            await calculateTotals();

            // Show success message
            utils.showSuccess("Voucher berhasil dihapus");
        } catch (error) {
            console.error("Error removing voucher:", error);
            utils.showError("Gagal menghapus voucher");
        }
    },

    // Update voucher status section
    updateVoucherStatus(discount) {
        if (discount > 0) {
            this.$voucherSection.removeClass("hidden");
            this.$voucherSection.removeClass(
                "bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700"
            );
            this.$voucherSection.addClass(
                "bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800"
            );
            this.$voucherDiscountText.text(`Diskon: ${formatRupiah(discount)}`);
        } else {
            this.$voucherSection.addClass("hidden");
        }
    },
};

// Initialize discount manager
discountManager.init();

// Update voucher status function (called from calculateTotals)
function updateVoucherStatus(discount) {
    discountManager.updateVoucherStatus(discount);
}

// Calculate total weight of all products
function calculateTotalWeight() {
    let totalWeight = 0;
    $("#productItemsTableBody tr").each(function () {
        const qty = parseInt($(this).find(".quantity-input").val()) || 0;
        const baseWeight = parseInt($(this).data("base-weight")) || 0;
        totalWeight += qty * baseWeight;
    });
    return totalWeight;
}

// Fetch destination data from postal code
async function getDestinationData(postalCode) {
    try {
        console.log("Mencari destinasi untuk kode pos:", postalCode);

        // Validate postal code format
        if (!postalCode || !/^\d{5}$/.test(postalCode)) {
            throw new Error(
                "Format kode pos tidak valid. Pastikan kode pos terdiri dari 5 digit angka."
            );
        }

        // Show loading state in the UI
        $("#checkOngkirLoader").removeClass("hidden");
        $("#checkOngkirBtn span").text("Mencari...");
        $("#checkOngkirBtn").prop("disabled", true);

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

        const data = await response.json();

        // Check for API error responses
        if (!response.ok) {
            throw new Error(
                data.message || `Gagal mencari destinasi: ${response.status}`
            );
        }

        // Validate response data structure
        if (!data || !Array.isArray(data) || data.length === 0) {
            throw new Error(
                `Kode pos ${postalCode} tidak ditemukan dalam database. Mohon periksa kembali kode pos yang digunakan.`
            );
        }

        console.log("Destinasi ditemukan:", data[0]);
        return data[0];
    } catch (error) {
        console.error("Gagal mengambil data tujuan:", error);
        utils.showError(
            error.message ||
                "Terjadi kesalahan saat mencari data destinasi. Silakan coba lagi."
        );
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

        // Validate parameters
        if (!destination) {
            throw new Error("Data destinasi tidak valid");
        }
        if (!weight || weight <= 0) {
            throw new Error("Berat pengiriman harus lebih dari 0 gram");
        }

        // Create URL-encoded form data
        const params = new URLSearchParams();
        params.append("destination", destination);
        params.append("weight", Math.ceil(weight));
        params.append("courier", "jne");
        params.append("service", "reg");

        const response = await fetch("/api/public/check-ongkir", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                Accept: "application/json",
            },
            body: params,
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(
                data.message ||
                    `Gagal mendapatkan ongkos kirim: ${response.status}`
            );
        }

        // Validate response data
        if (!Array.isArray(data)) {
            throw new Error("Format respons ongkos kirim tidak valid");
        }

        console.log("Data ongkir:", data);

        // Find JNE REG service
        const regService = data.find(
            (service) =>
                service.code?.toLowerCase() === "jne" &&
                service.service?.toUpperCase() === "REG"
        );

        if (!regService || !regService.cost) {
            throw new Error(
                "Layanan JNE REG tidak tersedia untuk rute ini. Silakan coba dengan alamat lain."
            );
        }

        const cost = parseInt(regService.cost);
        if (isNaN(cost) || cost < 0) {
            throw new Error("Biaya pengiriman tidak valid");
        }

        console.log("Biaya pengiriman:", cost);
        return cost;
    } catch (error) {
        console.error("Gagal menghitung ongkos kirim:", error);
        utils.showError(
            error.message ||
                "Terjadi kesalahan saat menghitung ongkos kirim. Silakan coba lagi."
        );
        return 0;
    }
}

// Update shipping cost
async function updateShippingCost(isButtonClick = false) {
    try {
        // Only proceed if it's a button click or resetting cost for non-delivery
        if (!isButtonClick && $("#order_type").val() === "Pengiriman") {
            return;
        }

        if ($("#order_type").val() !== "Pengiriman") {
            await resetShippingCost();
            return;
        }

        // Initial validation checks
        const selectedOption = $("#customer_id").find(":selected");
        if (!selectedOption.val()) {
            throw new Error("Silakan pilih pelanggan terlebih dahulu.");
        }

        const postalCode = selectedOption.data("postal-code");
        console.log("Kode pos pelanggan:", postalCode);

        // Comprehensive postal code validation
        if (!postalCode || postalCode === "-") {
            throw new Error(
                "Alamat pelanggan tidak lengkap. Pastikan alamat dan kode pos telah diisi dengan benar."
            );
        }

        if (!/^\d{5}$/.test(postalCode)) {
            throw new Error(
                "Format kode pos tidak valid. Kode pos harus terdiri dari 5 digit angka."
            );
        }

        // Validate products and weight
        const weight = calculateTotalWeight();
        console.log("Total berat:", weight);
        if ($("#productItemsTableBody tr").length === 0) {
            throw new Error(
                "Belum ada produk yang dipilih. Silakan tambahkan produk terlebih dahulu."
            );
        }

        if (weight === 0) {
            throw new Error(
                "Total berat produk adalah 0 gram. Pastikan setiap produk memiliki berat yang valid."
            );
        }

        // Show loading state
        $("#checkOngkirBtn").prop("disabled", true);
        $("#checkOngkirLoader").removeClass("hidden");
        $("#checkOngkirBtn span").text("Mengecek...");

        try {
            // Get destination data and calculate shipping cost
            const destinationData = await getDestinationData(postalCode);
            console.log("Data destinasi:", destinationData);

            if (!destinationData || !destinationData.id) {
                throw new Error("Data destinasi tidak valid");
            }

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

            // Update UI with shipping cost
            console.log("Biaya pengiriman:", shippingCost);
            $("#shipping_cost").val(shippingCost);
            $("#shipping_cost_hidden").val(shippingCost);
            $("#shippingCostDisplay").text(
                `Rp ${shippingCost.toLocaleString("id-ID")}`
            );

            // Show success message
            utils.showSuccess("Biaya pengiriman berhasil dihitung");

            // Recalculate totals
            await calculateTotals();
        } catch (error) {
            console.error("Error dalam kalkulasi ongkir:", error);
            // Reset shipping cost on error
            await resetShippingCost();
            throw error;
        }
    } catch (error) {
        console.error("Error dalam updateShippingCost:", error);
        utils.showError(
            error.message ||
                "Terjadi kesalahan saat menghitung ongkos kirim. Silakan coba lagi."
        );
        await resetShippingCost();
    } finally {
        // Reset loading state
        $("#checkOngkirBtn").prop("disabled", false);
        $("#checkOngkirLoader").addClass("hidden");
        $("#checkOngkirBtn span").text("Cek Ongkir");
    }
}

// Helper function to reset shipping cost
async function resetShippingCost() {
    $("#shipping_cost").val(0);
    $("#shipping_cost_hidden").val(0);
    $("#shippingCostDisplay").text("Rp 0");
    await calculateTotals();
}

// Calculate totals function
async function calculateTotals() {
    try {
        // Calculate subtotal from product list
        let subtotal = 0;
        $("#productItemsTableBody tr").each(function () {
            const qty = parseInt($(this).find(".quantity-input").val()) || 0;
            const price = utils.extractNumber($(this).find("td:eq(3)").text());
            subtotal += qty * price;
        });

        // Get discount - prioritize manual discount amount over promo
        let discount = parseInt($("#discount_amount").val()) || 0;

        // If no manual discount, check for promo discount
        if (discount === 0) {
            const discountValue = parseInt($("#promo_value").val()) || 0;
            const discountType = $("#promo_type").val();

            if (discountType === "percentage") {
                discount = Math.round(subtotal * (discountValue / 100));
            } else if (discountType === "amount") {
                discount = discountValue;
            }
        }

        // Get shipping cost
        const shippingCost = parseInt($("#shipping_cost").val()) || 0;

        // Update displays
        updateDisplays(subtotal, discount, shippingCost);

        // Update voucher status section
        updateVoucherStatus(discount);

        // Update items JSON for form submission
        const items = [];
        $("#productItemsTableBody tr").each(function () {
            const $row = $(this);
            const qty = parseInt($row.find(".quantity-input").val()) || 0;
            const productId = $row.find(".quantity-input").data("product-id");
            const unitPrice = utils.extractNumber($row.find("td:eq(3)").text());

            items.push({
                product_id: productId,
                quantity: qty,
                unit_price: unitPrice,
                total: qty * unitPrice,
            });
        });

        $("#itemsInput").val(JSON.stringify(items));
    } catch (error) {
        console.error("Error calculating totals:", error);
        utils.showError(
            "Terjadi kesalahan saat menghitung total. Silakan coba lagi."
        );
    }
}

// Update display values
function updateDisplays(subtotal, discount, shippingCost) {
    try {
        // Calculate grand total using correct formula: Subtotal + Shipping - Discount
        const grandTotal = subtotal + shippingCost - discount;

        // Log for debugging
        console.log("Updating displays with:", {
            subtotal,
            discount,
            shippingCost,
            grandTotal,
        });

        // Update all displays at once
        const updates = {
            subtotalDisplay: subtotal,
            discountDisplay: discount,
            shippingCostDisplay: shippingCost,
            grandTotalDisplay: grandTotal,
        };

        // Apply updates
        Object.entries(updates).forEach(([id, value]) => {
            $(`#${id}`).text(formatRupiah(value));
        });
    } catch (error) {
        console.error("Error updating displays:", error);
        // Fallback to zero values on error
        [
            "subtotalDisplay",
            "discountDisplay",
            "shippingCostDisplay",
            "grandTotalDisplay",
        ].forEach((id) => {
            $(`#${id}`).text("Rp 0");
        });
    }
}

// Initialize form validation
$("#orderForm").validate({
    rules: {
        customer_id: "required",
        order_type: "required",
        status_order: "required",
    },
    messages: {
        customer_id: "Silakan pilih pelanggan",
        order_type: "Silakan pilih tipe pesanan",
        status_order: "Silakan pilih status pesanan",
    },
    errorClass: "text-red-500 text-sm mt-1",
});

// On form submit, validate and prepare data
$("#orderForm").on("submit", async function (e) {
    e.preventDefault();

    // Validate at least one product
    if ($("#productItemsTableBody tr").length === 0) {
        utils.showError(
            "Harap tambahkan setidaknya satu produk ke dalam pesanan."
        );
        return;
    }

    // Validate quantities
    let hasInvalidQuantity = false;
    $("#productItemsTableBody tr").each(function () {
        const qty = parseInt($(this).find(".quantity-input").val()) || 0;
        if (qty <= 0) {
            hasInvalidQuantity = true;
            return false; // break the loop
        }
    });

    if (hasInvalidQuantity) {
        utils.showError("Kuantitas produk harus lebih dari 0.");
        return;
    }

    // Submit the form
    this.submit();
});

// Export modules and functions for external use
const exports = {
    utils,
    productList,
    promoManager,
    calculateTotals,
    updateShippingCost,
    calculateTotalWeight,
    resetShippingCost,
};

// Assign exports to window object
Object.assign(window, exports);
