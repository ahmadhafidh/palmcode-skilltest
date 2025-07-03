<?php

namespace App\Livewire\Admin\Categories;

use Illuminate\Support\Str;
use Livewire\Component;
use App\Models\Category; 

class Create extends Component
{
    public $categoryId;
    public $name;
    public $slug;

    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|unique:categories,name,' . $this->categoryId,
            'slug' => 'required|string|unique:categories,slug,' . $this->categoryId,
        ];
    }

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function save()
    {
        $this->validate();

        $category = $this->categoryId
            ? Category::findOrFail($this->categoryId)
            : new Category();

        $category->name = $this->name;
        $category->slug = $this->slug;
        $category->save();

        $this->categoryId = $category->id;

        session()->flash('message', 'Kategori berhasil disimpan!');

        return redirect()->route('categories');
    }

    public function render()
    {
        return view('livewire.admin.categories.create')->layout('layouts.app');
    }
}
