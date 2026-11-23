
<?php

use Livewire\Component;
use App\Models\Post;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = 'all';

    public function with(): array
    {
        $query = Post::with('user')->latest();

        // Filter by search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('content', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by status
        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        // Authorization: Authors only see their own posts
        if (auth()->user()->hasRole('author')) {
            $query->where('user_id', auth()->id());
        }

        return [
            'posts' => $query->paginate(10),
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function deletePost(Post $post)
    {
        if (
            auth()->user()->can("delete all posts") ||
            (
                auth()->user()->can("delete own posts") &&
                $post->user_id == auth()->id()
            )
        ) {
            $post->delete();

            session()->flash('success', 'Post deleted successfully!');
        }
    }
};
?>
<div>
    <div class="mb-6">
        <h1 class="text-2xl font_bold text-gray-900">Posts</h1>
        <p class="mt-1 text-sm text-gray-600">Manage your blog posts</p>
    </div>

    {{--filters--}}
    <div class="mb-6 bg-white rounded-lg border border-gray-200 p-4">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search posts..."
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-500 p-2">
            </div>

            <div class="sm:w-48">

            </div>
        </div>
    </div>



</div>
