<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Services\ImageService;
use App\Services\PostServise;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    private string $imagePath = 'blog/images/post/';
    private float $imageRatio = 4 / 3;

    public function __construct(
        private ImageService $imageService,
        private PostServise $postServise,
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->query('search');
        $category = request()->query('category');
        
        $posts = Post::query()
            ->select(['id', 'image', 'slug', 'title', 'preview', 'user_id', 'created_at'])
            ->where('user_id', Auth::user()->id)
            ->when($search, function(Builder $query, $search){
                $query->whereLike('title', '%'.trim($search).'%');
            })
            ->when($category, function(Builder $query, $slug){
                $query->whereHas('categories', function(Builder $query)use($slug){
                    $query->where('slug', $slug);
                });
            })
            ->latest()
            ->paginate(9);

        $categories = Category::query()
            ->pluck('name', 'slug');

        return view('pages.main.home', compact('posts', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::query()
            ->pluck('name', 'id');

        return view('pages.post.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        $image = $this->imageService->storeImage($request->safe()->image, $this->imagePath, $this->imageRatio);

        $content = $this->postServise->sanitizeContent($request->safe()->content);

        $slug = SlugService::createSlug(Post::class, 'slug', $request->safe()->title);

        $post = Post::create($request->safe()->only(['title', 'preview']) + [
            'user_id'   => Auth::user()->id,
            'slug'      => $slug,
            'image'     => $image,
            'content'   => $content,
        ]);

        $post_categories = [];
        $now = now();

        foreach ($request->safe()->categories as $category) {
            $post_categories[] = [
                'post_id'       => $post->id,
                'category_id'   => $category,
                'created_at'    => $now,
            ];
        }

        DB::table('post_categories')->insert($post_categories);

        return redirect()
            ->route('post.show', $slug)
            ->with('success', 'Berhasil disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): View
    {
        $post->load([
            'user:id,name',
            'comments' => function ($query) {
                $query->whereNull('parent_id');
            },
            'comments.user:id,name',
            'comments.replys',
            'comments.replys.user:id,name',
            'categories',
        ]);

        return view('pages.post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post): View
    {
        $categories = Category::query()
            ->withCount([
                'posts' => function ($query) use ($post) {
                    $query->where('posts.id', $post->id);
                }
            ])
            ->get();

        return view('pages.post.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $image = $post->image;
        if ($request->safe()->image) {
            $image = $this->imageService->storeImage($request->safe()->image, $this->imagePath, $this->imageRatio);

            // NOTE: delete previous image saved.
            $this->imageService->deleteImage($post->image);
        }

        $content = $this->postServise->sanitizeContent($request->safe()->content);
        $slug = $post->slug;

        if ($request->safe()->title !== $post->title) {
            $slug = SlugService::createSlug(Post::class, 'slug', $request->safe()->title);
        }

        $post->update($request->safe()->only(['title', 'preview']) + [
            'slug'      => $slug,
            'image'     => $image,
            'content'   => $content,
        ]);

        $post_categories = [];
        $now = now();

        foreach ($request->safe()->categories as $category) {
            $post_categories[] = [
                'post_id'       => $post->id,
                'category_id'   => $category,
                'created_at'    => $now,
            ];
        }

        DB::table('post_categories')->where('post_id', $post->id)->delete();

        DB::table('post_categories')->insert($post_categories);

        return redirect()
            ->route('post.show', $slug)
            ->with('success', 'Berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): RedirectResponse
    {
        // NOTE: delete previous image saved.
        $this->imageService->deleteImage($post->image);

        $post->delete();

        return redirect()
            ->route('post.index')
            ->with('success', 'Berhasil dihapus');
    }
}
