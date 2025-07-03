<?php

namespace App\Livewire\Admin\Posts;

use Livewire\WithFileUploads;
use Livewire\Component;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Create extends Component
{
    use WithFileUploads;

    public $postId;
    public $title = '';
    public $content = '';
    public $status = 'draft';
    public $newImage;
    public $selectedCategories = [];
    public $categories;

    public function mount($postId = null)
    {
        $this->categories = Category::all();

        if ($postId) {
            $post = Post::findOrFail($postId);
            $this->postId = $post->id;
            $this->title = $post->title;
            $this->content = $post->content;
            $this->status = $post->status;
            $this->selectedCategories = $post->categories->pluck('id')->toArray();
        }
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'newImage' => 'nullable|image',
            'selectedCategories' => 'nullable|array',
            'selectedCategories.*' => 'exists:categories,id',
        ];
    }

    public function save()
    {
        $this->validate();

        $post = $this->postId
            ? Post::findOrFail($this->postId)
            : new Post();

        $post->title = $this->title;
        $post->content = $this->content;
        $post->status = $this->status;
        $post->excerpt = Str::limit(strip_tags($this->content), 150);

        if (!$this->postId) {
            $slug = Str::slug($this->title);
            $originalSlug = $slug;
            $i = 1;

            while (Post::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $i++;
            }

            $post->slug = $slug;
        }

        // Handle gambar jika ada upload
        if ($this->newImage) {
            $filename = time() . '.' . $this->newImage->getClientOriginalExtension();
            $this->newImage->storeAs('images/post', $filename, 'public_dir'); 
            $post->image = 'images/post/' . $filename; 
        }

        // Tanggal publish
        if ($this->status === 'published' && !$post->published_at) {
            $post->published_at = now();
        } elseif ($this->status === 'draft') {
            $post->published_at = null;
        }

        $post->save();

        // Sinkronisasi kategori
        $post->categories()->sync($this->selectedCategories ?? []);

        $this->postId = $post->id;

        session()->flash('message', 'Post berhasil disimpan!');
        return redirect()->route('posts');
    }

    public function render()
    {
        return view('livewire.admin.posts.create')->layout('layouts.app');
    }
}
