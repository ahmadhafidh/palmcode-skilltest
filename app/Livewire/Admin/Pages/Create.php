<?php

namespace App\Livewire\Admin\Pages;

use App\Models\Pages;
use Illuminate\Support\Str;
use Livewire\Component;

class Create extends Component
{
    public $title = '';
    public $slug = '';
    public $body = '';
    public $status = 'draft';

    protected function rules()
    {
        return [
            'title' => 'required|string|min:3',
            'slug' => 'required|unique:pages,slug',
            'body' => 'required|string',
            'status' => 'required|in:draft,published',
        ];
    }

    public function updatedTitle($value)
    {
        $this->slug = Str::slug($value);
    }

    public function save()
    {
        \Log::info('BODY CONTENT:', ['body' => $this->body]);
        $this->validate();

        Pages::create([
            'title' => $this->title,
            'slug' => $this->slug,
            'body' => $this->body,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Page berhasil disimpan!');
        return redirect()->route('pages');  // pastikan route 'pages' sudah ada
    }

    public function render()
    {
        return view('livewire.admin.pages.create')->layout('layouts.app');
    }
}
