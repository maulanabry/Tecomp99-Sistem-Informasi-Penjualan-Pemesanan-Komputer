<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class DeleteConfirmationModal extends Component
{
    public $modalId;
    public $isOpen = false;
    public $itemId = null;
    public $title = 'Konfirmasi Hapus';
    public $message = 'Apakah Anda yakin ingin menghapus item ini?';
    public $itemName = null;

    public function mount($id = null)
    {
        $this->modalId = $id;
    }

    protected $listeners = ['showDeleteModal' => 'showModal'];

    public function showModal($data)
    {
        if (is_array($data)) {
            $this->itemId = $data['itemId'] ?? null;
            $this->itemName = $data['itemName'] ?? null;
        } else {
            $this->itemId = $data;
        }
        $this->isOpen = true;
    }

    public function confirmDeletion()
    {
        if (!$this->itemId) {
            return;
        }
        $this->dispatch('deleteConfirmed', $this->itemId);
        $this->isOpen = false;
        $this->reset('itemId', 'itemName');
    }

    public function cancel()
    {
        $this->isOpen = false;
        $this->reset('itemId', 'itemName');
    }

    public function render()
    {
        return view('livewire.admin.delete-confirmation-modal');
    }
}
