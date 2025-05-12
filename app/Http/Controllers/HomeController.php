<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Services\CategoryService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class HomeController extends Controller
{
    public function __construct(
        private CategoryService $categoryService,
    ) {}

    public function index() : View {
        $search = request()->query('search');
        $category = request()->query('category');
        $sorting = request()->query('sorting');
        
        $posts = Post::query()
            ->select(['id', 'image', 'slug', 'title', 'preview', 'user_id', 'created_at'])
            ->when($search, function(Builder $query, $search){
                $query->whereLike('title', '%'.trim($search).'%')
                    ->orWhereHas('categories', function(Builder $query)use($search){
                        $query->whereLike('name', '%'.trim($search).'%');
                    });
            })
            ->when($category, function(Builder $query, $slug){
                $query->whereHas('categories', function(Builder $query)use($slug){
                    $query->where('slug', $slug);
                });
            })
            ->when($sorting && $sorting == 'oldest', function(Builder $query){
                $query->oldest();
            }, function(Builder $query){
                $query->latest();
            })
            ->paginate(6)
            ->withQueryString();

        $categories = $this->categoryService->pluckSlug();

        return view('pages.main.home', compact('posts', 'categories'));
    }
}
