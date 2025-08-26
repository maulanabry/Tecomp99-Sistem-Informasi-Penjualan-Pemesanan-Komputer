<?php

namespace App\Livewire\Customer;

use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AddressManager extends Component
{
    // Form fields
    public $province_id = '';
    public $province_name = '';
    public $city_id = '';
    public $city_name = '';
    public $district_id = '';
    public $district_name = '';
    public $subdistrict_id = '';
    public $subdistrict_name = '';
    public $postal_code = '';
    public $detail_address = '';
    public $is_default = false;

    // Modal states
    public $showAddModal = false;
    public $showEditModal = false;
    public $editingAddressId = null;

    // Data arrays for dropdowns
    public $provinces = [];
    public $cities = [];
    public $districts = [];
    public $subdistricts = [];

    // Loading states
    public $loadingProvinces = false;
    public $loadingCities = false;
    public $loadingDistricts = false;
    public $loadingSubdistricts = false;

    // Error states
    public $apiError = '';

    protected $rules = [
        'province_id' => 'required',
        'province_name' => 'required',
        'city_id' => 'required',
        'city_name' => 'required',
        'district_id' => 'required',
        'district_name' => 'required',
        'subdistrict_id' => 'required',
        'subdistrict_name' => 'required',
        'postal_code' => 'required|max:10',
        'detail_address' => 'required|max:500',
    ];

    protected $messages = [
        'province_id.required' => 'Provinsi wajib dipilih.',
        'city_id.required' => 'Kota/Kabupaten wajib dipilih.',
        'district_id.required' => 'Kecamatan wajib dipilih.',
        'subdistrict_id.required' => 'Kelurahan/Desa wajib dipilih.',
        'postal_code.required' => 'Kode pos wajib diisi.',
        'detail_address.required' => 'Alamat lengkap wajib diisi.',
    ];

    public function mount()
    {
        $this->loadProvinces();
    }

    public function render()
    {
        $customer = Auth::guard('customer')->user();
        $addresses = $customer->addresses()->orderBy('is_default', 'desc')->get();

        return view('livewire.customer.address-manager', compact('addresses'));
    }

    public function loadProvinces()
    {
        $this->loadingProvinces = true;
        $this->apiError = '';

        try {
            // Try multiple approaches for better reliability
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Accept' => 'application/json',
                'Accept-Language' => 'id-ID,id;q=0.9,en;q=0.8',
            ])
                ->timeout(30)
                ->retry(3, 1000) // Retry 3 times with 1 second delay
                ->withOptions([
                    'verify' => false, // Disable SSL verification for local development
                ])
                ->get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');

            if ($response->successful()) {
                $data = $response->json();

                Log::info('Raw API Response', ['data' => $data]);

                // Validate data structure - check if it's array or has data property
                if (is_array($data) && !empty($data)) {
                    $this->provinces = $data;
                    Log::info('Provinces loaded successfully', ['count' => count($data)]);
                } elseif (isset($data['data']) && is_array($data['data'])) {
                    // Some APIs wrap data in a 'data' property
                    $this->provinces = $data['data'];
                    Log::info('Provinces loaded successfully from data property', ['count' => count($data['data'])]);
                } else {
                    // Try fallback with static data
                    $this->loadFallbackProvinces();
                }
            } else {
                Log::error('HTTP Error Response', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception('HTTP Error: ' . $response->status() . ' - ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Failed to load provinces', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Try fallback approach
            $this->loadFallbackProvinces();

            if (empty($this->provinces)) {
                $this->apiError = 'Gagal memuat data provinsi. Silakan refresh halaman atau coba lagi nanti.';
                session()->flash('error', $this->apiError);
            }
        } finally {
            $this->loadingProvinces = false;
        }
    }

    private function loadFallbackProvinces()
    {
        // Fallback with some major Indonesian provinces
        $this->provinces = [
            ['id' => '11', 'name' => 'ACEH'],
            ['id' => '12', 'name' => 'SUMATERA UTARA'],
            ['id' => '13', 'name' => 'SUMATERA BARAT'],
            ['id' => '14', 'name' => 'RIAU'],
            ['id' => '15', 'name' => 'JAMBI'],
            ['id' => '16', 'name' => 'SUMATERA SELATAN'],
            ['id' => '17', 'name' => 'BENGKULU'],
            ['id' => '18', 'name' => 'LAMPUNG'],
            ['id' => '19', 'name' => 'KEPULAUAN BANGKA BELITUNG'],
            ['id' => '21', 'name' => 'KEPULAUAN RIAU'],
            ['id' => '31', 'name' => 'DKI JAKARTA'],
            ['id' => '32', 'name' => 'JAWA BARAT'],
            ['id' => '33', 'name' => 'JAWA TENGAH'],
            ['id' => '34', 'name' => 'DI YOGYAKARTA'],
            ['id' => '35', 'name' => 'JAWA TIMUR'],
            ['id' => '36', 'name' => 'BANTEN'],
            ['id' => '51', 'name' => 'BALI'],
            ['id' => '52', 'name' => 'NUSA TENGGARA BARAT'],
            ['id' => '53', 'name' => 'NUSA TENGGARA TIMUR'],
            ['id' => '61', 'name' => 'KALIMANTAN BARAT'],
            ['id' => '62', 'name' => 'KALIMANTAN TENGAH'],
            ['id' => '63', 'name' => 'KALIMANTAN SELATAN'],
            ['id' => '64', 'name' => 'KALIMANTAN TIMUR'],
            ['id' => '65', 'name' => 'KALIMANTAN UTARA'],
            ['id' => '71', 'name' => 'SULAWESI UTARA'],
            ['id' => '72', 'name' => 'SULAWESI TENGAH'],
            ['id' => '73', 'name' => 'SULAWESI SELATAN'],
            ['id' => '74', 'name' => 'SULAWESI TENGGARA'],
            ['id' => '75', 'name' => 'GORONTALO'],
            ['id' => '76', 'name' => 'SULAWESI BARAT'],
            ['id' => '81', 'name' => 'MALUKU'],
            ['id' => '82', 'name' => 'MALUKU UTARA'],
            ['id' => '91', 'name' => 'PAPUA BARAT'],
            ['id' => '94', 'name' => 'PAPUA'],
        ];

        Log::info('Loaded fallback provinces', ['count' => count($this->provinces)]);
    }

    public function updatedProvinceId($value)
    {
        if ($value) {
            // Reset dependent fields
            $this->cities = [];
            $this->districts = [];
            $this->subdistricts = [];
            $this->city_id = '';
            $this->district_id = '';
            $this->subdistrict_id = '';
            $this->city_name = '';
            $this->district_name = '';
            $this->subdistrict_name = '';

            // Set province name
            $province = collect($this->provinces)->firstWhere('id', $value);
            $this->province_name = $province['name'] ?? '';

            // Use faster loading method for edit mode
            if ($this->showEditModal) {
                $this->loadCitiesForEdit($value);
            } else {
                $this->loadCities($value);
            }
        } else {
            // Clear all dependent data when province is cleared
            $this->resetDependentData();
        }
    }

    public function loadCities($provinceId)
    {
        $this->loadingCities = true;
        $this->apiError = '';

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Accept' => 'application/json',
            ])
                ->timeout(30)
                ->retry(2, 1000)
                ->withOptions([
                    'verify' => false,
                ])
                ->get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$provinceId}.json");

            if ($response->successful()) {
                $data = $response->json();

                // Validate data structure
                if (is_array($data) && !empty($data)) {
                    $this->cities = $data;
                    Log::info('Cities loaded successfully', ['province_id' => $provinceId, 'count' => count($data)]);
                } elseif (isset($data['data']) && is_array($data['data'])) {
                    $this->cities = $data['data'];
                    Log::info('Cities loaded successfully from data property', ['province_id' => $provinceId, 'count' => count($data['data'])]);
                } else {
                    throw new \Exception('Invalid data structure received from cities API');
                }
            } else {
                throw new \Exception('HTTP Error: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->cities = [];
            $this->apiError = 'Gagal memuat data kota/kabupaten. Silakan coba lagi.';
            Log::error('Failed to load cities', [
                'province_id' => $provinceId,
                'error' => $e->getMessage()
            ]);

            session()->flash('error', $this->apiError);
        } finally {
            $this->loadingCities = false;
        }
    }

    public function updatedCityId($value)
    {
        if ($value) {
            // Reset dependent fields
            $this->districts = [];
            $this->subdistricts = [];
            $this->district_id = '';
            $this->subdistrict_id = '';
            $this->district_name = '';
            $this->subdistrict_name = '';

            // Set city name
            $city = collect($this->cities)->firstWhere('id', $value);
            $this->city_name = $city['name'] ?? '';

            // Use faster loading method for edit mode
            if ($this->showEditModal) {
                $this->loadDistrictsForEdit($value);
            } else {
                $this->loadDistricts($value);
            }
        } else {
            // Clear dependent data when city is cleared
            $this->districts = [];
            $this->subdistricts = [];
            $this->district_id = '';
            $this->subdistrict_id = '';
            $this->district_name = '';
            $this->subdistrict_name = '';
        }
    }

    public function loadDistricts($cityId)
    {
        $this->loadingDistricts = true;
        $this->apiError = '';

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Accept' => 'application/json',
            ])
                ->timeout(30)
                ->retry(2, 1000)
                ->withOptions([
                    'verify' => false,
                ])
                ->get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$cityId}.json");

            if ($response->successful()) {
                $data = $response->json();

                // Validate data structure
                if (is_array($data) && !empty($data)) {
                    $this->districts = $data;
                    Log::info('Districts loaded successfully', ['city_id' => $cityId, 'count' => count($data)]);
                } elseif (isset($data['data']) && is_array($data['data'])) {
                    $this->districts = $data['data'];
                    Log::info('Districts loaded successfully from data property', ['city_id' => $cityId, 'count' => count($data['data'])]);
                } else {
                    throw new \Exception('Invalid data structure received from districts API');
                }
            } else {
                throw new \Exception('HTTP Error: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->districts = [];
            $this->apiError = 'Gagal memuat data kecamatan. Silakan coba lagi.';
            Log::error('Failed to load districts', [
                'city_id' => $cityId,
                'error' => $e->getMessage()
            ]);

            session()->flash('error', $this->apiError);
        } finally {
            $this->loadingDistricts = false;
        }
    }

    public function updatedDistrictId($value)
    {
        if ($value) {
            // Reset dependent fields
            $this->subdistricts = [];
            $this->subdistrict_id = '';
            $this->subdistrict_name = '';

            // Set district name
            $district = collect($this->districts)->firstWhere('id', $value);
            $this->district_name = $district['name'] ?? '';

            // Use faster loading method for edit mode
            if ($this->showEditModal) {
                $this->loadSubdistrictsForEdit($value);
            } else {
                $this->loadSubdistricts($value);
            }
        } else {
            // Clear dependent data when district is cleared
            $this->subdistricts = [];
            $this->subdistrict_id = '';
            $this->subdistrict_name = '';
        }
    }

    public function loadSubdistricts($districtId)
    {
        $this->loadingSubdistricts = true;
        $this->apiError = '';

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Accept' => 'application/json',
            ])
                ->timeout(30)
                ->retry(2, 1000)
                ->withOptions([
                    'verify' => false,
                ])
                ->get("https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$districtId}.json");

            if ($response->successful()) {
                $data = $response->json();

                // Validate data structure
                if (is_array($data) && !empty($data)) {
                    $this->subdistricts = $data;
                    Log::info('Subdistricts loaded successfully', ['district_id' => $districtId, 'count' => count($data)]);
                } elseif (isset($data['data']) && is_array($data['data'])) {
                    $this->subdistricts = $data['data'];
                    Log::info('Subdistricts loaded successfully from data property', ['district_id' => $districtId, 'count' => count($data['data'])]);
                } else {
                    throw new \Exception('Invalid data structure received from subdistricts API');
                }
            } else {
                throw new \Exception('HTTP Error: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->subdistricts = [];
            $this->apiError = 'Gagal memuat data kelurahan/desa. Silakan coba lagi.';
            Log::error('Failed to load subdistricts', [
                'district_id' => $districtId,
                'error' => $e->getMessage()
            ]);

            session()->flash('error', $this->apiError);
        } finally {
            $this->loadingSubdistricts = false;
        }
    }

    public function updatedSubdistrictId($value)
    {
        if ($value) {
            // Set subdistrict name
            $subdistrict = collect($this->subdistricts)->firstWhere('id', $value);
            $this->subdistrict_name = $subdistrict['name'] ?? '';
        }
    }

    private function resetDependentData()
    {
        $this->cities = [];
        $this->districts = [];
        $this->subdistricts = [];
        $this->city_id = '';
        $this->district_id = '';
        $this->subdistrict_id = '';
        $this->city_name = '';
        $this->district_name = '';
        $this->subdistrict_name = '';
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->showAddModal = true;

        // Ensure provinces are loaded when opening modal
        if (empty($this->provinces)) {
            $this->loadProvinces();
        }
    }

    public function closeAddModal()
    {
        $this->showAddModal = false;
        $this->resetForm();
    }

    public function openEditModal($addressId)
    {
        $address = CustomerAddress::find($addressId);

        if ($address && $address->customer_id === Auth::guard('customer')->user()->customer_id) {
            $this->editingAddressId = $addressId;
            $this->province_id = $address->province_id;
            $this->province_name = $address->province_name;
            $this->city_id = $address->city_id;
            $this->city_name = $address->city_name;
            $this->district_id = $address->district_id;
            $this->district_name = $address->district_name;
            $this->subdistrict_id = $address->subdistrict_id;
            $this->subdistrict_name = $address->subdistrict_name;
            $this->postal_code = $address->postal_code;
            $this->detail_address = $address->detail_address;
            $this->is_default = $address->is_default;

            // Ensure provinces are loaded first
            if (empty($this->provinces)) {
                $this->loadProvinces();
            }

            // Initialize with existing data only - don't load from API immediately
            $this->initializeEditDropdowns();

            $this->showEditModal = true;
        }
    }

    private function initializeEditDropdowns()
    {
        // Initialize dropdowns with existing data to show current values
        // This prevents multiple API calls on modal open

        if ($this->city_id && $this->city_name) {
            $this->cities = [[
                'id' => $this->city_id,
                'name' => $this->city_name
            ]];
        }

        if ($this->district_id && $this->district_name) {
            $this->districts = [[
                'id' => $this->district_id,
                'name' => $this->district_name
            ]];
        }

        if ($this->subdistrict_id && $this->subdistrict_name) {
            $this->subdistricts = [[
                'id' => $this->subdistrict_id,
                'name' => $this->subdistrict_name
            ]];
        }
    }

    public function loadCitiesForEdit($provinceId)
    {
        $this->loadingCities = true;

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Accept' => 'application/json',
            ])
                ->timeout(30)
                ->retry(2, 1000)
                ->withOptions([
                    'verify' => false,
                ])
                ->get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$provinceId}.json");

            if ($response->successful()) {
                $data = $response->json();

                // Validate data structure
                if (is_array($data) && !empty($data)) {
                    $this->cities = $data;
                } elseif (isset($data['data']) && is_array($data['data'])) {
                    $this->cities = $data['data'];
                }

                // If current city is not in the loaded cities, add it to preserve existing data
                if ($this->city_id && $this->city_name) {
                    $cityExists = collect($this->cities)->firstWhere('id', $this->city_id);
                    if (!$cityExists) {
                        $this->cities[] = [
                            'id' => $this->city_id,
                            'name' => $this->city_name
                        ];
                    }
                }

                Log::info('Cities loaded for edit successfully', ['province_id' => $provinceId, 'count' => count($this->cities)]);
            } else {
                // If API fails, at least show the current city
                if ($this->city_id && $this->city_name) {
                    $this->cities = [[
                        'id' => $this->city_id,
                        'name' => $this->city_name
                    ]];
                }
            }
        } catch (\Exception $e) {
            // If API fails, at least show the current city
            if ($this->city_id && $this->city_name) {
                $this->cities = [[
                    'id' => $this->city_id,
                    'name' => $this->city_name
                ]];
            }
            Log::error('Failed to load cities for edit', [
                'province_id' => $provinceId,
                'error' => $e->getMessage()
            ]);
        } finally {
            $this->loadingCities = false;
        }
    }

    public function loadDistrictsForEdit($cityId)
    {
        $this->loadingDistricts = true;

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Accept' => 'application/json',
            ])
                ->timeout(30)
                ->retry(2, 1000)
                ->withOptions([
                    'verify' => false,
                ])
                ->get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$cityId}.json");

            if ($response->successful()) {
                $data = $response->json();

                // Validate data structure
                if (is_array($data) && !empty($data)) {
                    $this->districts = $data;
                } elseif (isset($data['data']) && is_array($data['data'])) {
                    $this->districts = $data['data'];
                }

                // If current district is not in the loaded districts, add it to preserve existing data
                if ($this->district_id && $this->district_name) {
                    $districtExists = collect($this->districts)->firstWhere('id', $this->district_id);
                    if (!$districtExists) {
                        $this->districts[] = [
                            'id' => $this->district_id,
                            'name' => $this->district_name
                        ];
                    }
                }

                Log::info('Districts loaded for edit successfully', ['city_id' => $cityId, 'count' => count($this->districts)]);
            } else {
                // If API fails, at least show the current district
                if ($this->district_id && $this->district_name) {
                    $this->districts = [[
                        'id' => $this->district_id,
                        'name' => $this->district_name
                    ]];
                }
            }
        } catch (\Exception $e) {
            // If API fails, at least show the current district
            if ($this->district_id && $this->district_name) {
                $this->districts = [[
                    'id' => $this->district_id,
                    'name' => $this->district_name
                ]];
            }
            Log::error('Failed to load districts for edit', [
                'city_id' => $cityId,
                'error' => $e->getMessage()
            ]);
        } finally {
            $this->loadingDistricts = false;
        }
    }

    public function loadSubdistrictsForEdit($districtId)
    {
        $this->loadingSubdistricts = true;

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Accept' => 'application/json',
            ])
                ->timeout(30)
                ->retry(2, 1000)
                ->withOptions([
                    'verify' => false,
                ])
                ->get("https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$districtId}.json");

            if ($response->successful()) {
                $data = $response->json();

                // Validate data structure
                if (is_array($data) && !empty($data)) {
                    $this->subdistricts = $data;
                } elseif (isset($data['data']) && is_array($data['data'])) {
                    $this->subdistricts = $data['data'];
                }

                // If current subdistrict is not in the loaded subdistricts, add it to preserve existing data
                if ($this->subdistrict_id && $this->subdistrict_name) {
                    $subdistrictExists = collect($this->subdistricts)->firstWhere('id', $this->subdistrict_id);
                    if (!$subdistrictExists) {
                        $this->subdistricts[] = [
                            'id' => $this->subdistrict_id,
                            'name' => $this->subdistrict_name
                        ];
                    }
                }

                Log::info('Subdistricts loaded for edit successfully', ['district_id' => $districtId, 'count' => count($this->subdistricts)]);
            } else {
                // If API fails, at least show the current subdistrict
                if ($this->subdistrict_id && $this->subdistrict_name) {
                    $this->subdistricts = [[
                        'id' => $this->subdistrict_id,
                        'name' => $this->subdistrict_name
                    ]];
                }
            }
        } catch (\Exception $e) {
            // If API fails, at least show the current subdistrict
            if ($this->subdistrict_id && $this->subdistrict_name) {
                $this->subdistricts = [[
                    'id' => $this->subdistrict_id,
                    'name' => $this->subdistrict_name
                ]];
            }
            Log::error('Failed to load subdistricts for edit', [
                'district_id' => $districtId,
                'error' => $e->getMessage()
            ]);
        } finally {
            $this->loadingSubdistricts = false;
        }
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function retryLoadProvinces()
    {
        $this->apiError = '';
        $this->loadProvinces();
    }

    public function testApiConnection()
    {
        try {
            $response = Http::timeout(10)->get('https://httpbin.org/json');
            if ($response->successful()) {
                session()->flash('success', 'Koneksi internet berhasil. Mencoba memuat ulang data provinsi...');
                $this->loadProvinces();
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Tidak ada koneksi internet. Periksa koneksi Anda.');
        }
    }

    public function forceReloadProvinces()
    {
        $this->provinces = [];
        $this->cities = [];
        $this->districts = [];
        $this->subdistricts = [];
        $this->apiError = '';
        $this->loadProvinces();
    }

    public function saveAddress()
    {
        $this->validate();

        $customer = Auth::guard('customer')->user();

        CustomerAddress::create([
            'customer_id' => $customer->customer_id,
            'province_id' => $this->province_id,
            'province_name' => $this->province_name,
            'city_id' => $this->city_id,
            'city_name' => $this->city_name,
            'district_id' => $this->district_id,
            'district_name' => $this->district_name,
            'subdistrict_id' => $this->subdistrict_id,
            'subdistrict_name' => $this->subdistrict_name,
            'postal_code' => $this->postal_code,
            'detail_address' => $this->detail_address,
            'is_default' => $this->is_default,
        ]);

        $this->closeAddModal();
        session()->flash('success', 'Alamat berhasil ditambahkan!');
    }

    public function updateAddress()
    {
        $this->validate();

        $address = CustomerAddress::find($this->editingAddressId);

        if ($address && $address->customer_id === Auth::guard('customer')->user()->customer_id) {
            $address->update([
                'province_id' => $this->province_id,
                'province_name' => $this->province_name,
                'city_id' => $this->city_id,
                'city_name' => $this->city_name,
                'district_id' => $this->district_id,
                'district_name' => $this->district_name,
                'subdistrict_id' => $this->subdistrict_id,
                'subdistrict_name' => $this->subdistrict_name,
                'postal_code' => $this->postal_code,
                'detail_address' => $this->detail_address,
                'is_default' => $this->is_default,
            ]);

            $this->closeEditModal();
            session()->flash('success', 'Alamat berhasil diperbarui!');
        }
    }

    public function deleteAddress($addressId)
    {
        $customer = Auth::guard('customer')->user();
        $address = CustomerAddress::find($addressId);

        if ($address && $address->customer_id === $customer->customer_id) {
            // Tidak bisa hapus alamat default jika masih ada alamat lain
            if ($address->is_default && $customer->addresses()->count() > 1) {
                session()->flash('error', 'Tidak dapat menghapus alamat utama. Silakan jadikan alamat lain sebagai alamat utama terlebih dahulu.');
                return;
            }

            $address->delete();
            session()->flash('success', 'Alamat berhasil dihapus!');
        }
    }

    public function setAsDefault($addressId)
    {
        $customer = Auth::guard('customer')->user();
        $address = CustomerAddress::find($addressId);

        if ($address && $address->customer_id === $customer->customer_id) {
            $address->setAsDefault();
            session()->flash('success', 'Alamat utama berhasil diperbarui!');
        }
    }

    private function resetForm()
    {
        $this->province_id = '';
        $this->province_name = '';
        $this->city_id = '';
        $this->city_name = '';
        $this->district_id = '';
        $this->district_name = '';
        $this->subdistrict_id = '';
        $this->subdistrict_name = '';
        $this->postal_code = '';
        $this->detail_address = '';
        $this->is_default = false;
        $this->editingAddressId = null;

        $this->cities = [];
        $this->districts = [];
        $this->subdistricts = [];
        $this->apiError = '';

        // Reset loading states
        $this->loadingCities = false;
        $this->loadingDistricts = false;
        $this->loadingSubdistricts = false;
    }
}
