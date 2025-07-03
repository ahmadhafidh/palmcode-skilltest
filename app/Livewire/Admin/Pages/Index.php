<?php

namespace App\Livewire\Admin\Pages;

use App\Models\Pages;
use Illuminate\Support\Str;
use Livewire\Component;

class Index extends Component
{
    public $pages;

    public $editModalOpen = false;

    public $pageId;
    public $title;
    public $slug;
    public $body;
    public $status = 'draft';

    public function mount()
    {
        $this->pages = Pages::orderBy('id', 'asc')->get();
    }

    public function delete($id)
    {
        Pages::findOrFail($id)->delete();
        $this->mount();
        session()->flash('message', 'Pages berhasil dihapus.');
    }

    public function openEditModal($id)
    {
        $page = Pages::findOrFail($id);

        $this->pageId = $page->id;
        $this->title = $page->title;
        $this->slug = $page->slug;
        $this->body = $page->body;
        $this->status = $page->status;

        $this->editModalOpen = true;
    }

    public function updatedTitle($value)
    {
        $this->slug = Str::slug($value);
    }

    public function update()
    {
        $this->validate([
            'title' => 'required|string|min:3',
            'slug' => 'required|unique:pages,slug,' . $this->pageId,
            'body' => 'required',
            'status' => 'required|in:draft,published',
        ]);

        $page = Pages::findOrFail($this->pageId);
        $page->update([
            'title' => $this->title,
            'slug' => $this->slug,
            'body' => $this->body,
            'status' => $this->status,
        ]);

        $this->editModalOpen = false;
        $this->mount();
        session()->flash('message', 'Pages berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.admin.pages.index')->layout('layouts.app');
    }
}
