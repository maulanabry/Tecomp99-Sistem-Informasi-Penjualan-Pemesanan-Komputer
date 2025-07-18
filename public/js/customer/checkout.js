// Customer Checkout JavaScript - Based on working admin implementation
// Utility Functions
const checkoutUtils = {
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

    // Show loading state
    showLoading($button, loadingText = "Loading...") {
        $button.prop("disabled", true);
        const $span = $button.find("span").first();
        $span.html(`<i class="fas fa-spinner fa-spin mr-1"></i>${loadingText}`);
    },

    // Hide loading state
    hideLoading($button, originalText = "Hitung Ongkir") {
        $button.prop("disabled", false);
        const $span = $button.find("span").first();
        $span.text(originalText);
    },

    // Show error message
    showError(message) {
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

        setTimeout(() => {
            toast.classList.add("animate-fade-out-down");
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    },

    // Show success message
    showSuccess(message) {
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

        setTimeout(() => {
            toast.classList.add("animate-fade-out-down");
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    },
};

// Calculate total weight of all products
function calculateTotalWeight() {
    let totalWeight = 0;
    $(".cart-item").each(function () {
        const qty = parseInt($(this).data("quantity")) || 0;
        const baseWeight = parseInt($(this).data("weight")) || 0;
        totalWeight += qty * baseWeight;
    });
    console.log("Total weight calculated:", totalWeight, "grams");
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
        checkoutUtils.showError(
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
        checkoutUtils.showError(
            error.message ||
                "Terjadi kesalahan saat menghitung ongkos kirim. Silakan coba lagi."
        );
        return 0;
    }
}

// Shipping Cost Calculator
const shippingCalculator = {
    isCalculating: false,
    currentShippingCost: 0,

    // Initialize shipping calculator
    init() {
        this.bindEvents();
        console.log("Shipping calculator initialized");

        // Debug: Check if buttons exist
        const buttons = document.querySelectorAll(".order-type-btn");
        console.log("Found order type buttons:", buttons.length);
        buttons.forEach((btn, index) => {
            console.log(
                `Button ${index}:`,
                btn.getAttribute("data-order-type")
            );
        });
    },

    // Bind event handlers
    bindEvents() {
        // Handle order type button clicks
        document.addEventListener("click", (e) => {
            // Handle order type selection buttons
            if (
                e.target.classList.contains("order-type-btn") ||
                e.target.closest(".order-type-btn")
            ) {
                const button = e.target.classList.contains("order-type-btn")
                    ? e.target
                    : e.target.closest(".order-type-btn");
                const orderType = button.getAttribute("data-order-type");
                this.selectOrderType(orderType);
                this.handleOrderTypeChange(orderType);
            }

            // Handle manual shipping calculation
            if (
                e.target.id === "calculateShippingBtn" ||
                e.target.closest("#calculateShippingBtn")
            ) {
                e.preventDefault();
                this.calculateShippingCost();
            }
        });
    },

    // Select order type and update button states
    selectOrderType(orderType) {
        console.log("Selecting order type:", orderType);

        // Reset all buttons to unselected state
        document.querySelectorAll(".order-type-btn").forEach((btn) => {
            btn.classList.remove(
                "border-primary-500",
                "bg-primary-50",
                "text-primary-700"
            );
            btn.classList.add("border-gray-300", "bg-white", "text-gray-700");

            // Hide check icons
            const checkIcon = btn.querySelector(".fas.fa-check");
            if (checkIcon) {
                checkIcon.classList.add("hidden", "text-gray-400");
                checkIcon.classList.remove("text-primary-600");
            }

            // Update other icons
            const otherIcon = btn.querySelector(".fas:not(.fa-check)");
            if (otherIcon) {
                otherIcon.classList.remove("text-primary-600");
                otherIcon.classList.add("text-gray-600");
            }
        });

        // Highlight selected button
        const selectedButton = document.querySelector(
            `[data-order-type="${orderType}"]`
        );
        if (selectedButton) {
            selectedButton.classList.remove(
                "border-gray-300",
                "bg-white",
                "text-gray-700"
            );
            selectedButton.classList.add(
                "border-primary-500",
                "bg-primary-50",
                "text-primary-700"
            );

            // Show check icon
            const checkIcon = selectedButton.querySelector(".fas.fa-check");
            if (checkIcon) {
                checkIcon.classList.remove("hidden", "text-gray-400");
                checkIcon.classList.add("text-primary-600");
            }

            // Update other icon
            const otherIcon = selectedButton.querySelector(
                ".fas:not(.fa-check)"
            );
            if (otherIcon) {
                otherIcon.classList.remove("text-gray-600");
                otherIcon.classList.add("text-primary-600");
            }
        }
    },

    // Handle order type change
    handleOrderTypeChange(orderType) {
        console.log("Order type changed to:", orderType);

        const shippingSection = document.getElementById("shippingSection");

        if (orderType === "pengiriman") {
            shippingSection?.classList.remove("hidden");
            // Show loading state immediately
            this.showShippingLoadingState();
            // Auto-calculate shipping cost
            setTimeout(() => {
                this.calculateShippingCost();
            }, 500); // Small delay for better UX
        } else {
            shippingSection?.classList.add("hidden");
            this.currentShippingCost = 0;
            this.updateShippingDisplay(0);
            this.updateTotals();
        }
    },

    // Show shipping loading state
    showShippingLoadingState() {
        const loadingState = document.getElementById("shippingLoadingState");
        const normalState = document.getElementById("shippingNormalState");

        loadingState?.classList.remove("hidden");
        normalState?.classList.add("hidden");
    },

    // Hide shipping loading state
    hideShippingLoadingState() {
        const loadingState = document.getElementById("shippingLoadingState");
        const normalState = document.getElementById("shippingNormalState");

        loadingState?.classList.add("hidden");
        normalState?.classList.remove("hidden");
    },

    // Calculate shipping cost
    async calculateShippingCost() {
        if (this.isCalculating) {
            console.log("Shipping calculation already in progress");
            return;
        }

        console.log("=== MULAI KALKULASI ONGKIR (JavaScript) ===");

        // Get customer data
        const postalCode = $("#customerPostalCode").text().trim();
        const totalWeight = calculateTotalWeight();

        console.log("Customer data:", {
            postalCode: postalCode,
            totalWeight: totalWeight,
        });

        // Validate postal code
        if (
            !postalCode ||
            postalCode === "-" ||
            !this.validatePostalCode(postalCode)
        ) {
            checkoutUtils.showError("Kode pos tidak valid atau tidak tersedia");
            return;
        }

        // Validate weight
        if (totalWeight <= 0) {
            checkoutUtils.showError(
                "Total berat produk harus lebih dari 0 gram"
            );
            return;
        }

        this.isCalculating = true;
        const $button = $("#calculateShippingBtn");
        checkoutUtils.showLoading($button, "Menghitung...");

        try {
            // Step 1: Get destination data
            console.log(
                "STEP 1: Mencari data destinasi untuk kode pos:",
                postalCode
            );
            const destinationData = await getDestinationData(postalCode);

            if (!destinationData) {
                throw new Error(
                    "Kode pos tidak ditemukan dalam database RajaOngkir"
                );
            }

            console.log(
                "STEP 1 SUCCESS: Data destinasi ditemukan",
                destinationData
            );

            // Step 2: Calculate shipping cost
            const weightInGrams = Math.ceil(totalWeight);
            console.log("STEP 2: Menghitung ongkir", {
                destination_id: destinationData.id,
                weight_grams: weightInGrams,
            });

            const shippingCost = await calculateShippingCost(
                destinationData.id,
                weightInGrams
            );

            console.log("STEP 2 RESULT: Hasil kalkulasi ongkir", {
                shipping_cost: shippingCost,
                is_success: shippingCost > 0,
            });

            if (shippingCost > 0) {
                this.currentShippingCost = shippingCost;
                this.updateShippingDisplay(shippingCost);
                checkoutUtils.showSuccess("Biaya pengiriman berhasil dihitung");
                console.log(
                    "SUCCESS: Ongkir berhasil dihitung: Rp",
                    shippingCost.toLocaleString("id-ID")
                );
            } else {
                // Fallback to estimation
                const estimatedCost =
                    this.getEstimatedShippingCost(totalWeight);
                this.currentShippingCost = estimatedCost;
                this.updateShippingDisplay(estimatedCost);
                checkoutUtils.showError(
                    "Menggunakan estimasi ongkir karena API tidak tersedia"
                );
                console.log(
                    "FALLBACK: Menggunakan estimasi ongkir: Rp",
                    estimatedCost.toLocaleString("id-ID")
                );
            }

            this.updateTotals();
        } catch (error) {
            console.error("ERROR: Gagal menghitung ongkir", error);

            // Fallback to estimation
            const estimatedCost = this.getEstimatedShippingCost(totalWeight);
            this.currentShippingCost = estimatedCost;
            this.updateShippingDisplay(estimatedCost);
            this.updateTotals();

            checkoutUtils.showError(
                "Gagal menghitung ongkir: " + error.message
            );
            console.log(
                "FALLBACK: Menggunakan estimasi ongkir: Rp",
                estimatedCost.toLocaleString("id-ID")
            );
        } finally {
            this.isCalculating = false;
            this.hideShippingLoadingState();
            checkoutUtils.hideLoading($button, "Hitung Ulang");
            console.log("=== SELESAI KALKULASI ONGKIR ===");
        }
    },

    // Validate postal code format
    validatePostalCode(postalCode) {
        return /^\d{5}$/.test(postalCode);
    },

    // Get estimated shipping cost as fallback
    getEstimatedShippingCost(totalWeight) {
        const weightInKg = Math.ceil(totalWeight / 1000);
        return Math.max(15000, weightInKg * 5000); // Minimum 15rb, 5rb per kg
    },

    // Update shipping cost display
    updateShippingDisplay(cost) {
        const formattedCost =
            cost > 0 ? checkoutUtils.formatRupiah(cost) : "Menghitung...";
        $("#shippingCostAmount").text(formattedCost);
        $("#shippingAmount").text(checkoutUtils.formatRupiah(cost));
        console.log("Shipping display updated:", formattedCost);
    },

    // Update total calculations
    updateTotals() {
        const subtotal = this.calculateSubtotal();
        const discount = this.calculateDiscount();
        const shipping = this.currentShippingCost;
        const grandTotal = subtotal - discount + shipping;

        // Update displays
        $("#subtotalAmount").text(checkoutUtils.formatRupiah(subtotal));
        $("#shippingAmount").text(checkoutUtils.formatRupiah(shipping));
        $("#grandTotalAmount").text(checkoutUtils.formatRupiah(grandTotal));

        // Show/hide discount row
        if (discount > 0) {
            $("#discountRow").removeClass("hidden");
            $("#discountAmount").text(
                "-" + checkoutUtils.formatRupiah(discount)
            );
        } else {
            $("#discountRow").addClass("hidden");
        }

        // Show/hide shipping row
        if (shipping > 0) {
            $("#shippingRow").removeClass("hidden");
        } else {
            $("#shippingRow").addClass("hidden");
        }

        // Update Livewire component with shipping cost
        this.updateLivewireShippingCost(shipping);

        console.log("Totals updated:", {
            subtotal: subtotal,
            discount: discount,
            shipping: shipping,
            grandTotal: grandTotal,
        });
    },

    // Update Livewire component with shipping cost
    updateLivewireShippingCost(cost) {
        if (window.Livewire) {
            try {
                const componentId = this.getLivewireComponentId();
                if (componentId) {
                    Livewire.find(componentId)?.call("setShippingCost", cost);
                    console.log("Livewire shipping cost updated:", cost);
                }
            } catch (error) {
                console.error("Error updating Livewire shipping cost:", error);
            }
        }
    },

    // Get Livewire component ID
    getLivewireComponentId() {
        const livewireElement = document.querySelector("[wire\\:id]");
        return livewireElement?.getAttribute("wire:id");
    },

    // Calculate subtotal
    calculateSubtotal() {
        let subtotal = 0;
        $(".cart-item").each(function () {
            const price = parseFloat($(this).data("price")) || 0;
            const quantity = parseInt($(this).data("quantity")) || 0;
            subtotal += price * quantity;
        });
        return subtotal;
    },

    // Calculate discount (placeholder)
    calculateDiscount() {
        // This would be implemented based on applied vouchers
        return 0;
    },
};

// Initialize when document is ready
$(document).ready(function () {
    console.log("Customer checkout JavaScript loaded");
    shippingCalculator.init();
});

// Also initialize when window loads (fallback)
window.addEventListener("load", function () {
    console.log("Window loaded - reinitializing shipping calculator");
    shippingCalculator.init();
});

// Initialize when Livewire is ready (if present)
document.addEventListener("livewire:init", function () {
    console.log("Livewire initialized - reinitializing shipping calculator");
    setTimeout(() => {
        shippingCalculator.init();
    }, 100);
});

// Export for external use
window.checkoutUtils = checkoutUtils;
window.shippingCalculator = shippingCalculator;
