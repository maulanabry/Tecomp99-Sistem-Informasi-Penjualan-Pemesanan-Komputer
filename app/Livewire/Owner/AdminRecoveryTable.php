<?php

namespace App\Livewire\Owner;

use App\Models\Admin;
use Livewire\Component;
use Livewire\WithPagination;

class AdminRecoveryTable extends Component
{
    use WithPagination;

    // Properties untuk search dan filter
    public $search = '';
    public $roleFilter = '';
    public $perPage = 10;
    public $sortField = 'deleted_at';
    public $sortDirection = 'desc';

    // Properties untuk modal konfirmasi
    public $isRestoreModalOpen = false;
    public $isForceDeleteModalOpen = false;
    public $adminToRestore = null;
    public $adminToForceDelete = null;

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
     * Buka modal konfirmasi restore
     */
    public function openRestoreModal($adminId)
    {
        $this->adminToRestore = $adminId;
        $this->isRestoreModalOpen = true;
    }

    /**
     * Tutup modal konfirmasi restore
     */
    public function closeRestoreModal()
    {
        $this->isRestoreModalOpen = false;
        $this->adminToRestore = null;
    }

    /**
     * Buka modal konfirmasi force delete
     */
    public function openForceDeleteModal($adminId)
    {
        $this->adminToForceDelete = $adminId;
        $this->isForceDeleteModalOpen = true;
    }

    /**
     * Tutup modal konfirmasi force delete
     */
    public function closeForceDeleteModal()
    {
        $this->isForceDeleteModalOpen = false;
        $this->adminToForceDelete = null;
    }

    /**
     * Konfirmasi restore admin
     */
    public function confirmRestore()
    {
        if ($this->adminToRestore) {
            $admin = Admin::withTrashed()->find($this->adminToRestore);

            if ($admin && $admin->trashed()) {
                $admin->restore();
                session()->flash('success', 'Admin berhasil dipulihkan.');
            }
        }

        $this->closeRestoreModal();
    }

    /**
     * Konfirmasi force delete admin
     */
    public function confirmForceDelete()
    {
        if ($this->adminToForceDelete) {
            $admin = Admin::withTrashed()->find($this->adminToForceDelete);

            if ($admin && $admin->trashed()) {
                // Pastikan tidak menghapus diri sendiri
                if ($admin->id === auth('pemilik')->id()) {
                    session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri secara permanen.');
                } else {
                    $admin->forceDelete();
                    session()->flash('success', 'Admin berhasil dihapus secara permanen.');
                }
            }
        }

        $this->closeForceDeleteModal();
    }

    /**
     * Render component
     */
    public function render()
    {
        $query = Admin::onlyTrashed();

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

        // Sorting
        if (in_array($this->sortField, ['name', 'email', 'role', 'deleted_at'])) {
            $query->orderBy($this->sortField, $this->sortDirection);
        } else {
            $query->orderBy('deleted_at', $this->sortDirection);
        }

        $admins = $query->paginate($this->perPage);

        return view('livewire.owner.admin-recovery-table', [
            'admins' => $admins
        ]);
    }
}
