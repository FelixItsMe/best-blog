<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CategoryService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function pluckId(): Collection
    {
        return Cache::remember('category-pluck-id', 60 * 60 * 1, function () {
            return Category::query()
                ->pluck('name', 'id');
        });
    }

    public function pluckSlug(): Collection
    {
        return Cache::remember('category-pluck-slug', 60 * 60 * 1, function () {
            return Category::query()
                ->pluck('name', 'slug');
        });
    }
}
