// Customer Checkout JavaScript - Improved Livewire Integration
// Utility Functions
const checkoutUtils = {
    // Format number as Rupiah currency with enhanced null safety
    formatRupiah(number) {
        try {
            // Handle null, undefined, or invalid values
            if (number === null || number === undefined || number === "") {
                console.warn(
                    "Null/undefined number provided to formatRupiah:",
                    number
                );
                return "Rp 0";
            }

            const value = Number(number);
            if (isNaN(value) || !isFinite(value)) {
                console.warn(
                    "Invalid number provided to formatRupiah:",
                    number
                );
                return "Rp 0";
            }

            return `Rp ${value.toLocaleString("id-ID")}`;
        } catch (error) {
            console.error("Error formatting Rupiah:", error, "Input:", number);
            return "Rp 0";
        }
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

// Improved Shipping Calculator - Works with Livewire
const shippingCalculator = {
    isCalculating: false,
    currentShippingCost: 0,

    // Initialize shipping calculator
    init() {
        console.log("Customer checkout JavaScript initialized");

        // Don't interfere with Livewire radio buttons
        // Just provide utility functions for the Livewire @script section
        this.setupEventListeners();
    },

    // Setup minimal event listeners that don't conflict with Livewire
    setupEventListeners() {
        // Listen for manual recalculation requests
        document.addEventListener("click", (e) => {
            if (
                e.target.id === "manualRecalculateBtn" ||
                e.target.closest("#manualRecalculateBtn")
            ) {
                e.preventDefault();
                this.triggerLivewireRecalculation();
            }
        });
    },

    // Trigger Livewire recalculation
    triggerLivewireRecalculation() {
        if (window.Livewire) {
            try {
                const componentId = this.getLivewireComponentId();
                if (componentId) {
                    const component = Livewire.find(componentId);
                    if (component) {
                        component.call("calculateShippingCost");
                        console.log(
                            "Triggered Livewire shipping recalculation"
                        );
                    }
                }
            } catch (error) {
                console.error(
                    "Error triggering Livewire recalculation:",
                    error
                );
            }
        }
    },

    // Get Livewire component ID with safety checks
    getLivewireComponentId() {
        try {
            const livewireElement = document.querySelector("[wire\\:id]");
            return livewireElement?.getAttribute("wire:id") || null;
        } catch (error) {
            console.error("Error getting Livewire component ID:", error);
            return null;
        }
    },

    // Calculate total weight of all products
    calculateTotalWeight() {
        let totalWeight = 0;
        document.querySelectorAll(".cart-item").forEach((item) => {
            const qty = parseInt(item.getAttribute("data-quantity")) || 0;
            const baseWeight = parseInt(item.getAttribute("data-weight")) || 0;
            totalWeight += qty * baseWeight;
        });
        console.log("Total weight calculated:", totalWeight, "grams");
        return totalWeight;
    },

    // Get estimated shipping cost as fallback
    getEstimatedShippingCost(totalWeight) {
        const weightInKg = Math.ceil(totalWeight / 1000);
        return Math.max(15000, weightInKg * 5000); // Minimum 15rb, 5rb per kg
    },

    // Validate postal code format
    validatePostalCode(postalCode) {
        return /^\d{5}$/.test(postalCode);
    },

    // Update shipping cost display with enhanced error handling
    updateShippingDisplay(cost) {
        try {
            const formattedCost =
                cost && cost > 0 ? checkoutUtils.formatRupiah(cost) : "Rp 0";

            // Update various display elements
            const displays = [
                "shippingCostDisplay",
                "shippingCostAmount",
                "shippingAmount",
                "calculatedShippingCost",
            ];

            displays.forEach((id) => {
                try {
                    const element = document.getElementById(id);
                    if (element) {
                        element.textContent = formattedCost;
                    }
                } catch (elementError) {
                    console.warn(`Error updating element ${id}:`, elementError);
                }
            });

            console.log("Shipping display updated:", formattedCost);
        } catch (error) {
            console.error("Error updating shipping display:", error);
        }
    },

    // Update total calculations
    updateTotals() {
        const subtotal = this.calculateSubtotal();
        const discount = this.calculateDiscount();
        const shipping = this.currentShippingCost;
        const grandTotal = subtotal - discount + shipping;

        // Update displays
        const subtotalElement = document.getElementById("subtotalAmount");
        const shippingElement = document.getElementById("shippingAmount");
        const grandTotalElement = document.getElementById("grandTotalAmount");

        if (subtotalElement)
            subtotalElement.textContent = checkoutUtils.formatRupiah(subtotal);
        if (shippingElement)
            shippingElement.textContent = checkoutUtils.formatRupiah(shipping);
        if (grandTotalElement)
            grandTotalElement.textContent =
                checkoutUtils.formatRupiah(grandTotal);

        // Show/hide discount row
        const discountRow = document.getElementById("discountRow");
        if (discountRow) {
            if (discount > 0) {
                discountRow.classList.remove("hidden");
                const discountAmount =
                    document.getElementById("discountAmount");
                if (discountAmount) {
                    discountAmount.textContent =
                        "-" + checkoutUtils.formatRupiah(discount);
                }
            } else {
                discountRow.classList.add("hidden");
            }
        }

        // Show/hide shipping row
        const shippingRow = document.getElementById("shippingRow");
        if (shippingRow) {
            if (shipping > 0) {
                shippingRow.classList.remove("hidden");
            } else {
                shippingRow.classList.add("hidden");
            }
        }

        console.log("Totals updated:", {
            subtotal: subtotal,
            discount: discount,
            shipping: shipping,
            grandTotal: grandTotal,
        });
    },

    // Calculate subtotal
    calculateSubtotal() {
        let subtotal = 0;
        document.querySelectorAll(".cart-item").forEach((item) => {
            const price = parseFloat(item.getAttribute("data-price")) || 0;
            const quantity = parseInt(item.getAttribute("data-quantity")) || 0;
            subtotal += price * quantity;
        });
        return subtotal;
    },

    // Calculate discount (placeholder - would be handled by Livewire)
    calculateDiscount() {
        // This is handled by Livewire component
        return 0;
    },
};

// Initialize when document is ready
document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM loaded - initializing customer checkout");
    shippingCalculator.init();
});

// Initialize when Livewire is ready
document.addEventListener("livewire:init", function () {
    console.log("Livewire initialized - customer checkout ready");
    setTimeout(() => {
        shippingCalculator.init();
    }, 100);
});

// Handle pengiriman selection specifically
window.handlePengirimanSelection = function () {
    console.log(
        "Pengiriman selected - triggering calculation from checkout.js"
    );
    // Small delay to ensure Livewire has processed the change
    setTimeout(() => {
        if (window.Livewire && window.Livewire.find) {
            try {
                // Try to find the Livewire component and trigger calculation
                const components = document.querySelectorAll("[wire\\:id]");
                if (components.length > 0) {
                    const wireId = components[0].getAttribute("wire:id");
                    const component = window.Livewire.find(wireId);
                    if (component && component.orderType === "pengiriman") {
                        console.log(
                            "Triggering shipping calculation from checkout.js"
                        );
                        component.call("calculateShippingCost");
                    }
                }
            } catch (error) {
                console.error(
                    "Error triggering shipping calculation from checkout.js:",
                    error
                );
            }
        }
    }, 200);
};

// Export for external use and Livewire @script section
window.checkoutUtils = checkoutUtils;
window.shippingCalculator = shippingCalculator;

// Global functions for backward compatibility
window.calculateTotalWeight = () => shippingCalculator.calculateTotalWeight();
window.formatRupiah = (number) => checkoutUtils.formatRupiah(number);
