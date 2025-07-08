<?php

namespace App\Livewire\Owner;

use App\Models\Admin;
use Livewire\Component;
use Livewire\WithPagination;

class ManajemenPenggunaTable extends Component
{
    use WithPagination;

    // Properties untuk search dan filter
    public $search = '';
    public $roleFilter = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Properties untuk modal konfirmasi hapus
    public $isDeleteModalOpen = false;
    public $adminToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'roleFilter' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    /**
     * Reset pagination ketika search berubah
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Reset pagination ketika filter berubah
     */
    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    /**
     * Reset pagination ketika perPage berubah
     */
    public function updatingPerPage()
    {
        $this->resetPage();
    }

    /**
     * Sorting data
     */
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
        $this->resetPage();
    }

    /**
     * Buka modal konfirmasi hapus
     */
    public function openDeleteModal($adminId)
    {
        $this->adminToDelete = $adminId;
        $this->isDeleteModalOpen = true;
    }

    /**
     * Tutup modal konfirmasi hapus
     */
    public function closeDeleteModal()
    {
        $this->isDeleteModalOpen = false;
        $this->adminToDelete = null;
    }

    /**
     * Konfirmasi hapus admin
     */
    public function confirmDelete()
    {
        if ($this->adminToDelete) {
            $admin = Admin::find($this->adminToDelete);

            if ($admin) {
                // Pastikan tidak menghapus diri sendiri
                if ($admin->id === auth('pemilik')->id()) {
                    session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
                } else {
                    $admin->delete();
                    session()->flash('success', 'Admin berhasil dihapus.');
                }
            }
        }

        $this->closeDeleteModal();
    }

    /**
     * Restore admin yang dihapus
     */
    public function restoreAdmin($adminId)
    {
        $admin = Admin::withTrashed()->find($adminId);

        if ($admin) {
            $admin->restore();
            session()->flash('success', 'Admin berhasil dipulihkan.');
        }
    }

    /**
     * Render component
     */
    public function render()
    {
        $query = Admin::query();

        // Filter berdasarkan search
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Filter berdasarkan role
        if (!empty($this->roleFilter)) {
            $query->where('role', $this->roleFilter);
        }

        // Exclude role pemilik dari hasil
        $query->where('role', '!=', 'pemilik');

        // Include soft deleted records
        $query->withTrashed();

        // Sorting
        if ($this->sortField === 'name' || $this->sortField === 'email' || $this->sortField === 'role') {
            $query->orderBy($this->sortField, $this->sortDirection);
        } else {
            $query->orderBy('created_at', $this->sortDirection);
        }

        $admins = $query->paginate($this->perPage);

        return view('livewire.owner.manajemen-pengguna-table', [
            'admins' => $admins
        ]);
    }
}
