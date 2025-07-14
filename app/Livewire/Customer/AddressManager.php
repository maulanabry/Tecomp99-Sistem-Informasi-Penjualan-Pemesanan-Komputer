<?php

namespace App\Livewire\Customer;

use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Auth;
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
    public $loadingCities = false;
    public $loadingDistricts = false;
    public $loadingSubdistricts = false;

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
        try {
            $response = file_get_contents('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
            $this->provinces = json_decode($response, true);
        } catch (\Exception $e) {
            $this->provinces = [];
            session()->flash('error', 'Gagal memuat data provinsi. Silakan coba lagi.');
        }
    }

    public function updatedProvinceId($value)
    {
        if ($value) {
            $this->loadingCities = true;
            $this->cities = [];
            $this->districts = [];
            $this->subdistricts = [];
            $this->city_id = '';
            $this->district_id = '';
            $this->subdistrict_id = '';

            // Set province name
            $province = collect($this->provinces)->firstWhere('id', $value);
            $this->province_name = $province['name'] ?? '';

            $this->loadCities($value);
        }
    }

    public function loadCities($provinceId)
    {
        try {
            $response = file_get_contents("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$provinceId}.json");
            $this->cities = json_decode($response, true);
        } catch (\Exception $e) {
            $this->cities = [];
            session()->flash('error', 'Gagal memuat data kota/kabupaten. Silakan coba lagi.');
        } finally {
            $this->loadingCities = false;
        }
    }

    public function updatedCityId($value)
    {
        if ($value) {
            $this->loadingDistricts = true;
            $this->districts = [];
            $this->subdistricts = [];
            $this->district_id = '';
            $this->subdistrict_id = '';

            // Set city name
            $city = collect($this->cities)->firstWhere('id', $value);
            $this->city_name = $city['name'] ?? '';

            $this->loadDistricts($value);
        }
    }

    public function loadDistricts($cityId)
    {
        try {
            $response = file_get_contents("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$cityId}.json");
            $this->districts = json_decode($response, true);
        } catch (\Exception $e) {
            $this->districts = [];
            session()->flash('error', 'Gagal memuat data kecamatan. Silakan coba lagi.');
        } finally {
            $this->loadingDistricts = false;
        }
    }

    public function updatedDistrictId($value)
    {
        if ($value) {
            $this->loadingSubdistricts = true;
            $this->subdistricts = [];
            $this->subdistrict_id = '';

            // Set district name
            $district = collect($this->districts)->firstWhere('id', $value);
            $this->district_name = $district['name'] ?? '';

            $this->loadSubdistricts($value);
        }
    }

    public function loadSubdistricts($districtId)
    {
        try {
            $response = file_get_contents("https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$districtId}.json");
            $this->subdistricts = json_decode($response, true);
        } catch (\Exception $e) {
            $this->subdistricts = [];
            session()->flash('error', 'Gagal memuat data kelurahan/desa. Silakan coba lagi.');
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

    public function openAddModal()
    {
        $this->resetForm();
        $this->showAddModal = true;
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

            // Load dependent data
            $this->loadCities($this->province_id);
            $this->loadDistricts($this->city_id);
            $this->loadSubdistricts($this->district_id);

            $this->showEditModal = true;
        }
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
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
    }
}
