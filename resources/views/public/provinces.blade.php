<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Binderbyte API Test - Provinces</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Header Section with a Pexels Background Image -->
    <header class="bg-cover bg-center h-64" style="background-image: url('https://images.pexels.com/photos/261181/pexels-photo-261181.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2');">
        <div class="flex items-center justify-center h-full bg-gray-900 bg-opacity-50">
            <h1 class="text-white text-4xl font-bold">Binderbyte API - Test Page</h1>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="container mx-auto p-4">
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-2xl font-semibold mb-4">Select a Province</h2>
            
            <!-- Alert Box for Error Messages (Hidden by Default) -->
            <div id="alert" class="hidden mb-4 p-4 bg-red-200 text-red-800 rounded"></div>
            
            <!-- Dropdown for Provinces -->
            <div class="mb-4">
                <select id="provinceDropdown" class="w-full border border-gray-300 rounded p-2">
                    <option value="">Loading provinces...</option>
                </select>
            </div>
            
            <!-- Refresh Button -->
            <button id="refreshBtn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Refresh Provinces
            </button>
        </div>
    </main>
    
    <!-- JavaScript to Fetch and Populate Provinces -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const provinceDropdown = document.getElementById('provinceDropdown');
            const alertDiv = document.getElementById('alert');
            const refreshBtn = document.getElementById('refreshBtn');
            
            async function fetchProvinces() {
                try {
                    // Reset dropdown and hide alert
                    provinceDropdown.innerHTML = '<option value="">Loading provinces...</option>';
                    alertDiv.classList.add('hidden');
                    
                    // Call the public API endpoint
                    const response = await fetch("/public/api/provinces");
                    
                    // Check for a successful response status
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    
                    const provinces = await response.json();
                    
                    // Check if the response contains an error property or is empty
                    if (provinces.error) {
                        throw new Error(provinces.error);
                    }
                    
                    if (!Array.isArray(provinces) || provinces.length === 0) {
                        provinceDropdown.innerHTML = '<option value="">No provinces found</option>';
                        return;
                    }
                    
                    // Populate the dropdown with a default placeholder and province options
                    let options = '<option value="">Select a Province</option>';
                    provinces.forEach(province => {
                        options += `<option value="${province.id}">${province.name}</option>`;
                    });
                    provinceDropdown.innerHTML = options;
                } catch (error) {
                    provinceDropdown.innerHTML = '<option value="">Error loading provinces</option>';
                    alertDiv.textContent = error.message;
                    alertDiv.classList.remove('hidden');
                }
            }
            
            // Initial Fetch of Provinces on Page Load
            fetchProvinces();
            
            // Refresh button triggers re-fetch of provinces
            refreshBtn.addEventListener('click', fetchProvinces);
        });
    </script>
</body>
</html>
