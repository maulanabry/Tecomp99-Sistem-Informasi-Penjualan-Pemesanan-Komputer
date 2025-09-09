/**
 * Rupiah Currency Formatter Utility
 * Provides real-time formatting for currency input fields
 * Format: Rp 25.000, Rp 1.000.000
 * Stores only numeric values in database
 */

class RupiahFormatter {
    constructor() {
        this.formatters = new Map();
    }

    /**
     * Initialize currency formatting for input fields
     * @param {string|HTMLElement|NodeList} selector - CSS selector, element, or NodeList
     * @param {Object} options - Configuration options
     */
    init(selector, options = {}) {
        const defaultOptions = {
            allowEmpty: true,
            minValue: 0,
            maxValue: null,
            placeholder: "",
            onValueChange: null,
            livewire: false,
        };

        const config = { ...defaultOptions, ...options };
        let elements;

        if (typeof selector === "string") {
            elements = document.querySelectorAll(selector);
        } else if (selector instanceof HTMLElement) {
            elements = [selector];
        } else if (selector instanceof NodeList) {
            elements = selector;
        } else {
            console.error("Invalid selector provided to RupiahFormatter");
            return;
        }

        elements.forEach((element) => {
            if (element.tagName !== "INPUT") {
                console.warn(
                    "RupiahFormatter can only be applied to input elements"
                );
                return;
            }

            this.setupFormatter(element, config);
        });
    }

    /**
     * Setup formatter for a single input element
     * @param {HTMLElement} input - Input element
     * @param {Object} config - Configuration options
     */
    setupFormatter(input, config) {
        // Store original attributes
        const originalValue = input.value;
        const originalType = input.type;

        // Set input type to text for formatting
        input.type = "text";
        input.setAttribute("data-currency-formatter", "true");

        // Create hidden input for actual numeric value
        const hiddenInput = document.createElement("input");
        hiddenInput.type = "hidden";
        hiddenInput.name = input.name;
        hiddenInput.value = this.parseNumericValue(originalValue);

        // Update original input name to avoid form submission
        input.name = input.name + "_display";
        input.removeAttribute("name");

        // Insert hidden input after the display input
        input.parentNode.insertBefore(hiddenInput, input.nextSibling);

        // Store formatter data
        const formatterData = {
            displayInput: input,
            hiddenInput: hiddenInput,
            config: config,
            lastValidValue: hiddenInput.value,
        };

        this.formatters.set(input, formatterData);

        // Format initial value
        this.formatDisplayValue(input, hiddenInput.value);

        // Add event listeners
        this.addEventListeners(input, formatterData);

        // Set placeholder
        if (config.placeholder) {
            input.placeholder = config.placeholder;
        }
    }

    /**
     * Add event listeners to input element
     * @param {HTMLElement} input - Input element
     * @param {Object} formatterData - Formatter data
     */
    addEventListeners(input, formatterData) {
        const { hiddenInput, config } = formatterData;

        // Handle input event (real-time formatting)
        input.addEventListener("input", (e) => {
            this.handleInput(e, formatterData);
        });

        // Handle paste event
        input.addEventListener("paste", (e) => {
            setTimeout(() => {
                this.handleInput(e, formatterData);
            }, 0);
        });

        // Handle focus event
        input.addEventListener("focus", (e) => {
            // Select all text on focus for easy editing
            setTimeout(() => {
                e.target.select();
            }, 0);
        });

        // Handle blur event
        input.addEventListener("blur", (e) => {
            this.handleBlur(e, formatterData);
        });

        // Handle keydown for special keys
        input.addEventListener("keydown", (e) => {
            this.handleKeydown(e, formatterData);
        });

        // Livewire compatibility
        if (config.livewire) {
            input.addEventListener("input", () => {
                // Dispatch Livewire event with numeric value
                if (window.Livewire) {
                    const event = new CustomEvent("input", {
                        bubbles: true,
                        detail: { value: hiddenInput.value },
                    });
                    hiddenInput.dispatchEvent(event);
                }
            });
        }
    }

    /**
     * Handle input event
     * @param {Event} e - Input event
     * @param {Object} formatterData - Formatter data
     */
    handleInput(e, formatterData) {
        const { displayInput, hiddenInput, config } = formatterData;
        const cursorPosition = displayInput.selectionStart;
        const oldValue = displayInput.value;

        // Extract numeric value
        const numericValue = this.parseNumericValue(oldValue);

        // Validate value
        if (this.isValidValue(numericValue, config)) {
            hiddenInput.value = numericValue;
            formatterData.lastValidValue = numericValue;

            // Format display value
            const formattedValue = this.formatDisplayValue(
                displayInput,
                numericValue
            );

            // Restore cursor position
            this.restoreCursorPosition(
                displayInput,
                cursorPosition,
                oldValue,
                formattedValue
            );

            // Trigger callback
            if (config.onValueChange) {
                config.onValueChange(numericValue, formattedValue);
            }
        } else {
            // Restore last valid value
            hiddenInput.value = formatterData.lastValidValue;
            this.formatDisplayValue(displayInput, formatterData.lastValidValue);
        }
    }

    /**
     * Handle blur event
     * @param {Event} e - Blur event
     * @param {Object} formatterData - Formatter data
     */
    handleBlur(e, formatterData) {
        const { displayInput, hiddenInput, config } = formatterData;

        // Ensure proper formatting on blur
        const numericValue = this.parseNumericValue(displayInput.value);

        if (numericValue === 0 && !config.allowEmpty) {
            hiddenInput.value = config.minValue || 0;
            this.formatDisplayValue(displayInput, hiddenInput.value);
        } else {
            hiddenInput.value = numericValue;
            this.formatDisplayValue(displayInput, numericValue);
        }
    }

    /**
     * Handle keydown event
     * @param {Event} e - Keydown event
     * @param {Object} formatterData - Formatter data
     */
    handleKeydown(e, formatterData) {
        const { displayInput } = formatterData;

        // Allow: backspace, delete, tab, escape, enter
        if (
            [8, 9, 27, 13, 46].indexOf(e.keyCode) !== -1 ||
            // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            (e.keyCode === 65 && e.ctrlKey === true) ||
            (e.keyCode === 67 && e.ctrlKey === true) ||
            (e.keyCode === 86 && e.ctrlKey === true) ||
            (e.keyCode === 88 && e.ctrlKey === true) ||
            // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)
        ) {
            return;
        }

        // Ensure that it is a number and stop the keypress
        if (
            (e.shiftKey || e.keyCode < 48 || e.keyCode > 57) &&
            (e.keyCode < 96 || e.keyCode > 105)
        ) {
            e.preventDefault();
        }
    }

    /**
     * Parse numeric value from formatted string
     * @param {string} value - Formatted value
     * @returns {number} - Numeric value
     */
    parseNumericValue(value) {
        if (!value || value === "") return 0;

        // Remove "Rp", spaces, and dots, keep only numbers
        const cleaned = value.toString().replace(/[^0-9]/g, "");
        return cleaned === "" ? 0 : parseInt(cleaned, 10);
    }

    /**
     * Format numeric value for display
     * @param {HTMLElement} input - Input element
     * @param {number} value - Numeric value
     * @returns {string} - Formatted value
     */
    formatDisplayValue(input, value) {
        if (value === 0 || value === "0") {
            input.value = "";
            return "";
        }

        const formatted = this.formatRupiah(value);
        input.value = formatted;
        return formatted;
    }

    /**
     * Format number as Rupiah
     * @param {number} value - Numeric value
     * @returns {string} - Formatted Rupiah string
     */
    formatRupiah(value) {
        if (!value || value === 0) return "";

        // Convert to string and add dots every 3 digits
        const numberString = value.toString();
        const formatted = numberString.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

        return `Rp ${formatted}`;
    }

    /**
     * Validate if value is within acceptable range
     * @param {number} value - Value to validate
     * @param {Object} config - Configuration options
     * @returns {boolean} - Is valid
     */
    isValidValue(value, config) {
        if (value < config.minValue) return false;
        if (config.maxValue !== null && value > config.maxValue) return false;
        return true;
    }

    /**
     * Restore cursor position after formatting
     * @param {HTMLElement} input - Input element
     * @param {number} cursorPosition - Original cursor position
     * @param {string} oldValue - Old value
     * @param {string} newValue - New formatted value
     */
    restoreCursorPosition(input, cursorPosition, oldValue, newValue) {
        // Calculate new cursor position based on the difference in length
        const lengthDiff = newValue.length - oldValue.length;
        let newPosition = cursorPosition + lengthDiff;

        // Ensure position is within bounds
        newPosition = Math.max(0, Math.min(newPosition, newValue.length));

        // Set cursor position
        setTimeout(() => {
            input.setSelectionRange(newPosition, newPosition);
        }, 0);
    }

    /**
     * Get numeric value from formatted input
     * @param {HTMLElement} input - Input element
     * @returns {number} - Numeric value
     */
    getValue(input) {
        const formatterData = this.formatters.get(input);
        if (formatterData) {
            return parseInt(formatterData.hiddenInput.value, 10) || 0;
        }
        return this.parseNumericValue(input.value);
    }

    /**
     * Set value for formatted input
     * @param {HTMLElement} input - Input element
     * @param {number} value - Numeric value to set
     */
    setValue(input, value) {
        const formatterData = this.formatters.get(input);
        if (formatterData) {
            formatterData.hiddenInput.value = value;
            this.formatDisplayValue(input, value);
        }
    }

    /**
     * Destroy formatter for input element
     * @param {HTMLElement} input - Input element
     */
    destroy(input) {
        const formatterData = this.formatters.get(input);
        if (formatterData) {
            // Restore original input
            input.type = "number";
            input.name = formatterData.hiddenInput.name;
            input.value = formatterData.hiddenInput.value;

            // Remove hidden input
            formatterData.hiddenInput.remove();

            // Remove formatter data
            this.formatters.delete(input);
        }
    }

    /**
     * Initialize formatters for all currency inputs on page
     */
    static autoInit() {
        const formatter = new RupiahFormatter();

        // Auto-initialize inputs with data-currency attribute
        document.addEventListener("DOMContentLoaded", () => {
            const currencyInputs = document.querySelectorAll(
                'input[data-currency="true"]'
            );
            if (currencyInputs.length > 0) {
                formatter.init(currencyInputs);
            }
        });

        return formatter;
    }
}

// Create global instance
window.RupiahFormatter = RupiahFormatter;
window.rupiahFormatter = new RupiahFormatter();

// Auto-initialize on page load
document.addEventListener("DOMContentLoaded", () => {
    // Initialize all inputs with data-currency attribute
    const currencyInputs = document.querySelectorAll(
        'input[data-currency="true"]'
    );
    if (currencyInputs.length > 0) {
        window.rupiahFormatter.init(currencyInputs);
    }
});

// Export for module systems
if (typeof module !== "undefined" && module.exports) {
    module.exports = RupiahFormatter;
}
