class AddItemsModal {
    constructor(options) {
        this.modalId = options.modalId;
        this.type = options.type; // 'product' or 'service'
        this.fetchUrl = options.fetchUrl;
        this.onItemSelect = options.onItemSelect;

        this.currentPage = 1;
        this.searchQuery = "";
        this.categoryFilter = "";
        this.lastPage = 1;

        this.initializeElements();
        this.setupEventListeners();
        this.fetchItems();
    }

    initializeElements() {
        this.modal = document.getElementById(this.modalId);
        this.searchInput = document.getElementById(`${this.modalId}-search`);
        this.filterSelect = document.getElementById(`${this.modalId}-filter`);
        this.itemsContainer = document.getElementById(`${this.modalId}-items`);
        this.prevButton = document.getElementById(`${this.modalId}-prev-page`);
        this.nextButton = document.getElementById(`${this.modalId}-next-page`);
        this.currentPageSpan = document.getElementById(
            `${this.modalId}-current-page`
        );
    }

    setupEventListeners() {
        // Search input with debounce
        let searchTimeout;
        this.searchInput.addEventListener("input", (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.searchQuery = e.target.value;
                this.currentPage = 1;
                this.fetchItems();
            }, 300);
        });

        // Category filter
        if (this.filterSelect) {
            this.filterSelect.addEventListener("change", (e) => {
                this.categoryFilter = e.target.value;
                this.currentPage = 1;
                this.fetchItems();
            });
        }

        // Pagination
        this.prevButton.addEventListener("click", () => {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.fetchItems();
            }
        });

        this.nextButton.addEventListener("click", () => {
            if (this.currentPage < this.lastPage) {
                this.currentPage++;
                this.fetchItems();
            }
        });
    }

    async fetchItems() {
        try {
            const params = new URLSearchParams({
                page: this.currentPage,
                search: this.searchQuery,
                category: this.categoryFilter,
                type: this.type,
            });

            const response = await fetch(`${this.fetchUrl}?${params}`);
            const data = await response.json();

            this.lastPage = data.last_page;
            this.updatePagination(data.current_page, data.last_page);
            this.renderItems(data.data);
        } catch (error) {
            console.error("Error fetching items:", error);
        }
    }

    updatePagination(currentPage, lastPage) {
        this.currentPageSpan.innerHTML = `
            Page <span class="font-semibold text-gray-900 dark:text-white">${currentPage}</span> 
            of <span class="font-semibold text-gray-900 dark:text-white">${lastPage}</span>
        `;

        this.prevButton.disabled = currentPage === 1;
        this.nextButton.disabled = currentPage === lastPage;

        if (currentPage === 1) {
            this.prevButton.classList.add("opacity-50", "cursor-not-allowed");
        } else {
            this.prevButton.classList.remove(
                "opacity-50",
                "cursor-not-allowed"
            );
        }

        if (currentPage === lastPage) {
            this.nextButton.classList.add("opacity-50", "cursor-not-allowed");
        } else {
            this.nextButton.classList.remove(
                "opacity-50",
                "cursor-not-allowed"
            );
        }
    }

    renderItems(items) {
        this.itemsContainer.innerHTML = items
            .map((item) => this.renderItemRow(item))
            .join("");

        // Add event listeners to add buttons
        const addButtons =
            this.itemsContainer.querySelectorAll(".add-item-btn");
        addButtons.forEach((button) => {
            button.addEventListener("click", () => {
                const itemId = button.dataset.id;
                const item = items.find((i) => i.id === itemId);
                if (item && this.onItemSelect) {
                    this.onItemSelect(item);
                }
            });
        });
    }

    renderItemRow(item) {
        const stockColumn =
            this.type === "product"
                ? `<td class="px-6 py-4">${item.stock}</td>`
                : "";

        return `
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                    ${item.name}
                </td>
                <td class="px-6 py-4">
                    Rp ${new Intl.NumberFormat("id-ID").format(item.price)}
                </td>
                ${stockColumn}
                <td class="px-6 py-4">
                    <button type="button"
                        data-id="${item.id}"
                        class="add-item-btn text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm p-2 text-center inline-flex items-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                </td>
            </tr>
        `;
    }
}

// Export for use in other files
window.AddItemsModal = AddItemsModal;
