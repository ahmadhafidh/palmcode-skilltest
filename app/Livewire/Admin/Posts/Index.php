<?php

namespace App\Livewire\Admin\Posts;

use App\Models\Post;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Index extends Component
{
    use WithFileUploads;

    public $posts;
    public $categories;
    public $editModalOpen = false;
    public $postId;
    public $title = '';
    public $content = '';
    public $status = 'draft';
    public $selectedCategories = [];
    public $image;    
    public $newImage; 
    public $search = '';

    protected $listeners = [];

    public function mount()
    {
        $this->loadData();
        $this->categories = Category::all();
    }

    public function loadData()
    {
        $this->posts = Post::with('categories')->latest()->get();
    }

    public function openEditModal($id)
    {
        $post = Post::with('categories')->findOrFail($id);

        $this->postId = $post->id;
        $this->title = $post->title;
        $this->content = $post->content;
        $this->status = $post->status;
        $this->selectedCategories = $post->categories->pluck('id')->toArray();
        $this->image = $post->image;

        $this->editModalOpen = true;
    }

    public function update()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'selectedCategories' => 'nullable|array',
            'selectedCategories.*' => 'exists:categories,id',
            'newImage' => 'nullable|image',
        ]);

        $post = Post::findOrFail($this->postId);

        $post->title = $this->title;
        $post->content = $this->content;
        $post->status = $this->status;
        $post->excerpt = Str::limit(strip_tags($this->content), 150);

        // Ganti gambar jika ada gambar baru
        if ($this->newImage) {
            // Hapus gambar lama jika ada
            if ($post->image && file_exists(public_path($post->image))) {
                unlink(public_path($post->image));
            }

            // Simpan gambar baru ke public/images/post
            $filename = 'images/post/' . time() . '.' . $this->newImage->getClientOriginalExtension();
            $this->newImage->storeAs('images/post', basename($filename), 'public_dir'); // pakai disk 'public_dir'

            $post->image = $filename; // simpan path relatif
        }

        // Atur published_at
        if ($this->status === 'published' && !$post->published_at) {
            $post->published_at = now();
        } elseif ($this->status === 'draft') {
            $post->published_at = null;
        }

        $post->save();
        $post->categories()->sync($this->selectedCategories ?? []);

        $this->editModalOpen = false;
        $this->loadData();

        session()->flash('message', 'Post berhasil diperbarui.');
        return redirect()->route('posts');
    }

    public function delete($id)
    {
        Post::findOrFail($id)->delete();
        $this->loadData();
        session()->flash('message', 'Post berhasil dihapus.');
    }
    public function render()
    {
        return view('livewire.admin.posts.index')->layout('layouts.app');
    }
}
