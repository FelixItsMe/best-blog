<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ request()->routeIs('post.index') ? __('Blog Saya') : __('Blog') }}
            </h2>
            @if (request()->routeIs('post.index'))
                <a href="{{ route('post.create') }}"
                    class="py-2 px-4 bg-pink-500 hover:bg-pink-600 text-white rounded shadow">Buat Postingan</a>
            @endif
        </div>
    </x-slot>

    <div class="py-12 max-sm:pb-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session()->has('success'))
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="mb-4 bg-sky-300 dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden p-6 dark:text-[#A1A09A] text-slate-700">
                    {{ session()->get('success') }}
                </div>
            @endif
            <div class="mb-4">
                <form class="flex flex-col lg:flex-row justify-between max-sm:px-4 gap-2">
                    <div class="flex items-center max-w-sm">
                        <label for="simple-search" class="sr-only">Search</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 5v10M3 5a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm0 10a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm12 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm0 0V6a3 3 0 0 0-3-3H9m1.5-2-2 2 2 2" />
                                </svg>
                            </div>
                            <input type="text" id="simple-search"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                name="search" value="{{ request()->query('search') }}"
                                placeholder="Cari postingan..." />
                        </div>
                        <button type="submit"
                            class="p-2.5 ms-2 text-sm font-medium text-white bg-pink-500 rounded-lg border border-pink-700 hover:bg-pink-800 focus:ring-4 focus:outline-none focus:ring-pink-300 dark:bg-pink-600 dark:hover:bg-pink-700 dark:focus:ring-pink-800">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                            <span class="sr-only">Search</span>
                        </button>
                    </div>
                    <div class="flex flex-col lg:flex-row gap-2">
                        <div class="max-w-sm">
                            <select id="sorting" name="sorting"
                                onchange="event.preventDefault();
                                            this.closest('form').submit();"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="latest" @selected(request()->query('sorting') == 'latest')>Terbaru</option>
                                <option value="oldest" @selected(request()->query('sorting') == 'oldest')>Terlama</option>
                            </select>
                        </div>
                        <div class="max-w-sm">
                            <select id="category" name="category"
                                onchange="event.preventDefault();
                                            this.closest('form').submit();"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $id => $name)
                                    <option value="{{ $id }}" @selected($id == request()->query('category'))>{{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-4">
                @forelse ($posts as $post)
                    <x-blog-card :image="asset($post->image ?? 'assets/images/default-image.jpg')" :title="$post->title" :date="$post->created_at->format('M, d, Y ∘ H:i:s')"
                        :preview_text="$post->preview" link="{{ route('post.show', $post->slug) }}" />
                @empty
                    <div class="p-4 sm:p-8 bg-pink-500/10 dark:bg-gray-800 shadow sm:rounded-lg lg:col-span-3 text-center dark:text-[#A1A09A] text-[#706f6c]">Tidak ada postingan</div>
                @endforelse
            </div>
            
            @if ($posts->hasPages())
                <div class="bg-pink-500/10 dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden p-6">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
