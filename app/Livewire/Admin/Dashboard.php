<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Post;
use App\Models\Pages;
use App\Models\Category;

class Dashboard extends Component
{
    public $postCount;
    public $pageCount;
    public $categoryCount;

    public function mount()
    {
        $this->postCount = Post::count();
        $this->pageCount = Pages::count();
        $this->categoryCount = Category::count();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
