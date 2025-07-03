<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Str;

class Index extends Component
{
    public $categories;

    // Untuk modal edit
    public $editModalOpen = false;
    public $categoryId;
    public $name;
    public $slug;

    protected $rules = [
        'name' => 'required|string|min:3|unique:categories,name',
        'slug' => 'required|string|unique:categories,slug',
    ];

    public function mount()
    {
       $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = Category::orderBy('id', 'asc')->get();
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
        $this->loadCategories();
        session()->flash('message', 'Kategori berhasil dihapus.');
    }

    // Buka modal edit dan load data category
    public function openEditModal($id)
    {
        $category = Category::findOrFail($id);
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;

        // Update rule unik dengan kecualikan id saat edit
        $this->rules['name'] = 'required|string|min:3|unique:categories,name,' . $this->categoryId;
        $this->rules['slug'] = 'required|string|unique:categories,slug,' . $this->categoryId;

        $this->editModalOpen = true;
    }

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function update()
    {
        $this->validate();

        $category = Category::findOrFail($this->categoryId);
        $category->name = $this->name;
        $category->slug = $this->slug;
        $category->save();

        session()->flash('message', 'Kategori berhasil diperbarui.');

        $this->editModalOpen = false;
        $this->loadCategories();
    }

    public function render()
    {
        return view('livewire.admin.categories.index')->layout('layouts.app');
    }
}
