// Fungsi utilitas untuk memformat angka menjadi mata uang Rupiah
function formatRupiah(number) {
    return "Rp " + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Logika pemilihan pelanggan
const customerSelect = document.getElementById("customer_id");
const customerInfo = document.getElementById("customerInfo");
const customerEmail = document.getElementById("customerEmail");
const customerPhone = document.getElementById("customerPhone");
const customerFullAddress = document.getElementById("customerFullAddress");
const customerPostalCode = document.getElementById("customerPostalCode");

customerSelect.addEventListener("change", function () {
    const selectedOption = this.options[this.selectedIndex];
    if (this.value) {
        // Perbarui informasi pelanggan
        customerEmail.textContent =
            selectedOption.getAttribute("data-email") || "-";
        customerPhone.textContent =
            selectedOption.getAttribute("data-contact") || "-";

        // Bangun alamat lengkap
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

        // Tampilkan bagian informasi pelanggan
        customerInfo.classList.remove("hidden");
    } else {
        // Sembunyikan bagian informasi pelanggan jika tidak ada pelanggan yang dipilih
        customerInfo.classList.add("hidden");

        // Reset biaya pengiriman
        shippingCostInput.value = 0;
        shippingCostDisplay.textContent = "Rp 0";
        calculateTotals();
    }
});

// Logika tipe pesanan
const orderTypeSelect = document.getElementById("order_type");
const shippingCostContainer = document.getElementById("shippingCostContainer");
const shippingCostInput = document.getElementById("shipping_cost");
const shippingCostDisplay = document.getElementById("shippingCostDisplay");

orderTypeSelect.addEventListener("change", function () {
    if (this.value === "Pengiriman") {
        shippingCostContainer.classList.remove("hidden");
        checkOngkirBtn.parentElement.classList.remove("hidden");
    } else {
        shippingCostContainer.classList.add("hidden");
        checkOngkirBtn.parentElement.classList.add("hidden");
        shippingCostInput.value = 0;
        shippingCostDisplay.textContent = "Rp 0";
        calculateTotals();
    }
});

// Handler tombol cek ongkir
const checkOngkirBtn = document.getElementById("checkOngkirBtn");
const checkOngkirLoader = document.getElementById("checkOngkirLoader");

checkOngkirBtn.addEventListener("click", async function () {
    try {
        // Tampilkan loader
        checkOngkirLoader.classList.remove("hidden");
        checkOngkirBtn.querySelector("span").textContent = "Mengecek...";
        checkOngkirBtn.disabled = true;

        await updateShippingCost(true);
    } finally {
        // Sembunyikan loader dan reset tombol
        checkOngkirLoader.classList.add("hidden");
        checkOngkirBtn.querySelector("span").textContent = "Cek Ongkir";
        checkOngkirBtn.disabled = false;
    }
});

shippingCostInput.addEventListener("input", function () {
    shippingCostDisplay.textContent = formatRupiah(parseInt(this.value) || 0);
    calculateTotals();
});

// Manajemen daftar produk
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

// Tambahkan produk dari modal ke daftar produk
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

        // Tambahkan baris ke tabel daftar produk
        const tr = document.createElement("tr");
        tr.id = `product-row-${productId}-${Date.now()}`; // Gunakan ID unik untuk setiap baris
        tr.className =
            "bg-white border-b dark:bg-gray-800 dark:border-gray-700";
        const productWeight = row.getAttribute("data-product-weight");
        tr.innerHTML = `
                <td class="px-6 py-4">${productName}</td>
                <td class="px-6 py-4 text-right quantity-cell" data-product-id="${productId}" data-quantity="${quantity}">${quantity}</td>
                <td class="px-6 py-4 text-right">${
                    productWeight * quantity
                }</td>
                <td class="px-6 py-4 text-right">${formatRupiah(
                    productPrice
                )}</td>
                <td class="px-6 py-4 text-right" id="total-${productId}-${Date.now()}">${formatRupiah(
            productPrice * quantity
        )}</td>
                <td class="px-6 py-4 text-center">
                    <button type="button" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800 remove-product-btn" data-product-id="${productId}">
                        🗑️
                    </button>
                </td>
            `;
        productItemsTableBody.appendChild(tr);

        // Tambahkan event listener untuk tombol hapus
        tr.querySelector(".remove-product-btn").addEventListener(
            "click",
            (event) => {
                const rowToRemove = event.target.closest("tr");
                if (rowToRemove) {
                    rowToRemove.remove();
                    calculateTotals();
                }
            }
        );

        calculateTotals();
        addProductModal.classList.add("hidden");
    });
});

// Logika kode promo
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

function clearPromo() {
    promoIdInput.value = "";
    promoTypeInput.value = "";
    promoValueInput.value = "";
    promoInfo.classList.add("hidden");
    calculateTotals();
}

promoCodeInput.addEventListener("input", () => {
    if (!promoCodeInput.value.trim()) {
        clearPromo();
    }
});

applyPromoBtn.addEventListener("click", async () => {
    const code = promoCodeInput.value.trim();
    if (!code) {
        showPromoError("Masukkan kode promo");
        return;
    }

    try {
        const response = await fetch(
            `/api/public/check-promo?code=${encodeURIComponent(
                code
            )}&status=active`,
            {
                headers: {
                    Accept: "application/json",
                },
            }
        );

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || "Gagal memeriksa kode promo");
        }

        const promo = await response.json();
        if (!promo || !promo.is_active) {
            throw new Error("Kode promo tidak valid atau tidak aktif");
        }

        // Simpan data promo
        promoIdInput.value = promo.id;
        promoTypeInput.value = promo.discount_type;
        promoValueInput.value = promo.discount_value;

        // Tampilkan sukses dengan info diskon
        const discountInfo =
            promo.discount_type === "percentage"
                ? `${promo.discount_value}%`
                : `Rp ${parseInt(promo.discount_value).toLocaleString(
                      "id-ID"
                  )}`;
        showPromoSuccess(
            `Promo berhasil diterapkan: ${promo.name} (${discountInfo})`
        );
        calculateTotals();
    } catch (error) {
        console.error("Error applying promo:", error);
        showPromoError(error.message);
        clearPromo();
    }
});

// Hitung total berat
function calculateTotalWeight() {
    let totalWeight = 0;
    document.querySelectorAll("#productItemsTableBody tr").forEach((row) => {
        const weightCell = row.querySelector("td:nth-child(3)");
        const weight = parseInt(weightCell.textContent) || 0;
        totalWeight += weight;
    });
    return totalWeight;
}

// Ambil data tujuan dari kode pos
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

// Hitung biaya pengiriman
async function calculateShippingCost(destination, weight) {
    try {
        console.log("Menghitung ongkir dengan params:", {
            destination,
            weight,
        });

        // Buat data form yang di-URL-encode
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

        // Temukan layanan JNE REG
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

// Perbarui biaya pengiriman
async function updateShippingCost(isButtonClick = false) {
    const checkOngkirBtn = document.getElementById("checkOngkirBtn");
    const checkOngkirLoader = document.getElementById("checkOngkirLoader");

    try {
        // Hanya lanjutkan jika ini adalah klik tombol atau mereset biaya untuk non-pengiriman
        if (!isButtonClick && orderTypeSelect.value === "Pengiriman") {
            return;
        }

        if (orderTypeSelect.value !== "Pengiriman") {
            shippingCostInput.value = 0;
            shippingCostDisplay.textContent = "Rp 0";
            calculateTotals();
            return;
        }

        // Validasi pemilihan pelanggan
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

        // Validasi produk
        const weight = calculateTotalWeight();
        console.log("Total berat:", weight);
        if (weight === 0) {
            alert(
                "Belum ada produk yang dipilih. Silakan tambahkan produk terlebih dahulu."
            );
            return;
        }

        // Tampilkan indikator pemuatan
        if (checkOngkirBtn && checkOngkirLoader) {
            checkOngkirLoader.classList.remove("hidden");
            checkOngkirBtn.disabled = true;
            checkOngkirBtn.querySelector("span").textContent = "Mengecek...";
        }

        try {
            // Ambil data tujuan dan hitung biaya pengiriman
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
            shippingCostDisplay.textContent = `Rp ${shippingCost.toLocaleString(
                "id-ID"
            )}`;
            calculateTotals();
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

        // Reset biaya pengiriman jika terjadi kesalahan
        shippingCostInput.value = 0;
        shippingCostDisplay.textContent = "Rp 0";
        calculateTotals();
    } finally {
        // Sembunyikan indikator pemuatan dan reset tombol
        if (checkOngkirBtn && checkOngkirLoader) {
            checkOngkirLoader.classList.add("hidden");
            checkOngkirBtn.disabled = false;
            checkOngkirBtn.querySelector("span").textContent = "Cek Ongkir";
        }
    }
}

// Fungsi hitung total
function calculateTotals() {
    let subtotal = 0;
    document.querySelectorAll("#productItemsTableBody tr").forEach((row) => {
        const productId = row.id.replace("product-row-", "");
        const quantityCell = row.querySelector(".quantity-cell");
        const qty = parseInt(quantityCell.dataset.quantity);
        const unitPriceText = row.children[3].textContent.replace(/[^\d]/g, ""); // Updated index due to new weight column
        const unitPrice = parseInt(unitPriceText) || 0;
        subtotal += qty * unitPrice;
    });

    // Hitung diskon dari kode promo
    let discount = 0;
    if (promoIdInput.value && promoTypeInput.value && promoValueInput.value) {
        const discountType = promoTypeInput.value;
        const discountValue = parseFloat(promoValueInput.value);

        console.log("Applying promo:", {
            type: discountType,
            value: discountValue,
        });

        if (discountType === "percentage") {
            discount = Math.round(subtotal * (discountValue / 100));
            console.log(`Calculating ${discountValue}% discount:`, discount);
        } else if (discountType === "fixed") {
            discount = discountValue;
            console.log(`Applying fixed discount:`, discount);
        }

        // Batasi diskon pada subtotal
        if (discount > subtotal) {
            console.log(`Capping discount at subtotal:`, subtotal);
            discount = subtotal;
        }
    }

    const shippingCost = parseInt(shippingCostInput.value) || 0;
    const grandTotal = subtotal - discount + shippingCost;

    // Perbarui tampilan
    document.getElementById("subtotalDisplay").textContent =
        formatRupiah(subtotal);
    discountDisplay.textContent = formatRupiah(discount);
    shippingCostDisplay.textContent = formatRupiah(shippingCost);
    document.getElementById("grandTotalDisplay").textContent =
        formatRupiah(grandTotal);

    // Perbarui input tersembunyi untuk item JSON
    const items = [];
    document.querySelectorAll("#productItemsTableBody tr").forEach((row) => {
        const productId = row.id.replace("product-row-", "");
        const quantityCell = row.querySelector(".quantity-cell");
        const qty = parseInt(quantityCell.dataset.quantity);
        const unitPriceText = row.children[3].textContent.replace(/[^\d]/g, "");
        const unitPrice = parseInt(unitPriceText) || 0;
        const total = qty * unitPrice;
        items.push({
            product_id: productId,
            quantity: qty,
            unit_price: unitPrice,
            total: total,
        });
    });
    itemsInput.value = JSON.stringify(items);
}

// Saat formulir disubmit, validasi setidaknya satu produk ditambahkan
document.getElementById("orderForm").addEventListener("submit", function (e) {
    if (document.querySelectorAll("#productItemsTableBody tr").length === 0) {
        e.preventDefault();
        alert("Harap tambahkan setidaknya satu produk ke dalam pesanan.");
    }
});
