<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Customer;
use Livewire\WithPagination;

class CustomerSelectionModal extends Component
{
    use WithPagination;

    public $show = false;
    public $searchQuery = '';
    public $selectedCustomer = null;
    public $selectedCustomerId = '';

    protected $listeners = ['openCustomerModal' => 'open'];

    public function mount()
    {
        $this->resetPage();
    }

    public function updatedSearchQuery()
    {
        $this->resetPage();
    }

    public function open()
    {
        $this->show = true;
    }

    public function close()
    {
        $this->show = false;
        $this->reset(['searchQuery']);
        $this->resetPage();
    }

    public function selectCustomer($customerId)
    {
        $customer = Customer::with(['defaultAddress', 'addresses'])
            ->find($customerId);

        if ($customer) {
            $this->selectedCustomer = $customer;
            $this->selectedCustomerId = $customerId;

            // Dispatch event to parent component with customer data
            $this->dispatch('customerSelected', [
                'customer_id' => $customer->customer_id,
                'name' => $customer->name,
                'email' => $customer->email,
                'contact' => $customer->contact,
                'address' => $customer->defaultAddress ? $customer->defaultAddress->detail_address : '',
                'postal_code' => $customer->defaultAddress ? $customer->defaultAddress->postal_code : '',
                'city' => $customer->defaultAddress ? $customer->defaultAddress->city_name : '',
                'province' => $customer->defaultAddress ? $customer->defaultAddress->province_name : '',
                'service_orders_count' => $customer->service_orders_count,
                'product_orders_count' => $customer->product_orders_count,
                'total_points' => $customer->total_points,
            ]);
        }

        $this->close();
    }

    public function getCustomersProperty()
    {
        return Customer::query()
            ->with(['defaultAddress'])
            ->when($this->searchQuery, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('contact', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('email', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('customer_id', 'like', '%' . $this->searchQuery . '%');
                });
            })
            ->orderBy('name')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.admin.customer-selection-modal', [
            'customers' => $this->customers,
        ]);
    }
}
