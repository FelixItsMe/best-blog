<x-app-layout>
    @push('styles')
    @endpush
    <x-slot name="header">
        <h2 class="leading-tight">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a
                        href="{{ route('home') }}">Blog</a>
                </li>
                <li class="breadcrumb-item breadcrumb-active">{{ $post->title }}</li>
            </ol>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session()->has('success'))
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="mb-4 bg-sky-300/75 dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden p-6 dark:text-[#A1A09A] text-slate-700">
                    {{ session()->get('success') }}
                </div>
            @endif
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="w-full overflow-hidden lg:col-span-2 sm:rounded-sm">
                    <img class="w-full" src="{{ asset($post->image ?? 'assets/images/default-image.jpg') }}"
                        alt="{{ $post->slug }}">
                    <div class="bg-white dark:bg-gray-900 p-4 mx-6 -translate-y-12">
                        <div>
                            <div class="flex flex-row justify-between mb-4">
                                <span class="dark:text-[#A1A09A] text-[#706f6c] text-sm">by
                                    {{ $post->user->name }}</span>
                                <span
                                    class="dark:text-[#A1A09A] text-[#706f6c] text-sm">{{ $post->created_at->format('M, d, Y H:i') }}</span>
                            </div>
                            <h1 class="text-4xl font-bold dark:text-white mb-4">{{ $post->title }}</h1>
                        </div>
                        <article class="prose dark:text-[#A1A09A] text-[#706f6c] dark:prose-headings:text-white">
                            {!! $post->content !!}
                        </article>
                    </div>
                    <div class="py-4 border-t max-sm:px-4">
                        <h1 class="text-4xl text-pink-500 mb-4">KOMENTAR</h1>
                        <div class="flex flex-col space-y-6">
                            @forelse ($post->comments as $comment)
                                <div>
                                    <div class="flex flex-row justify-between">
                                        <span class="text-lg font-bold text-pink-500">{{ $comment->user->name }}</span>
                                        @auth
                                            <button type="button" class="dark:text-[#A1A09A]"
                                                onclick="reply({{ $comment->id }}, '{{ $comment->user->name }}')">REPLY</button>
                                        @endauth
                                    </div>
                                    <span
                                        class="text-xs dark:text-[#A1A09A] text-[#706f6c]">{{ $comment->created_at->format('M, d, Y H:i:s') }}</span>
                                    <p class="dark:text-[#A1A09A] text-slate-800">{{ $comment->content }}</p>
                                </div>
                                @foreach ($comment->replys as $reply)
                                    <div class="ml-12">
                                        <div class="flex flex-row justify-between">
                                            <span
                                                class="text-lg font-bold text-pink-500">{{ $reply->user->name }}</span>
                                        </div>
                                        <span
                                            class="text-xs dark:text-[#A1A09A] text-[#706f6c]">{{ $reply->created_at->format('M, d, Y H:i:s') }}</span>
                                        <p class="dark:text-[#A1A09A] text-slate-800">{{ $reply->content }}</p>
                                    </div>
                                @endforeach
                            @empty
                                <div class="flex flex-row justify-center">
                                    <h3 class="dark:text-[#A1A09A] text-[#706f6c]">Belum ada komentar.</h3>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    @auth
                        <div class="py-4 border-t">
                            <h1 class="text-4xl text-pink-500 mb-4">Buat Komentar</h1>
                            <form method="POST" action="{{ route('post.comment.store', $post->slug) }}">
                                @csrf
                                <input type="hidden" name="parent_id">
                                <div class="flex flex-row items-end gap-2">
                                    <button type="button" id="clear-reply" onclick="clearReply()"
                                        class="hidden bg-red-500 text-white py-0.5 px-2">X</button>
                                    <span class="dark:text-[#A1A09A] text-[#706f6c]" id="reply-message"></span>
                                </div>
                                <x-textarea id="content" class="mt-1 block w-full" name="content"
                                    placeholder="Isi pesan...">{{ old('content') }}</x-textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('parent_id')" />
                                <x-input-error class="mt-2" :messages="$errors->get('content')" />
                                <div class="flex justify-end mt-2">
                                    <x-primary-button>Simpan</x-primary-button>
                                </div>
                            </form>
                        </div>
                    @endauth
                </div>
                <div>
                    <div class="sticky top-4">
                        <div class="flex flex-col">
                            @auth
                                @if (auth()->user()->id == $post->user_id)
                                    <div class="flex flex-row gap-2 mb-4 max-sm:px-4">
                                        <a href="{{ route('post.edit', $post->slug) }}"
                                            class="font-bold py-2 px-4 bg-yellow-500 hover:bg-yellow-600 text-white rounded shadow">EDIT</a>
                                        <button type="button" onclick="destroyData()"
                                            class="font-bold py-2 px-4 bg-red-500 hover:bg-red-600 text-white rounded shadow">HAPUS</button>
                                    </div>
                                @endif
                            @endauth
                            <div class="p-4 bg-pink-500 dark:bg-gray-800 mb-4 sm:rounded">
                                <h3 class="text-lg font-bold text-white">Kategori</h3>
                            </div>
                            <ul class="flex flex-row flex-wrap gap-2 max-sm:px-4 text-sm dark:text-[#A1A09A] text-[#706f6c]">
                                @foreach ($post->categories as $category)
                                    <li class="border-2 border-pink-500 py-2 px-4 rounded">{{ $category->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('post.destroy', $post->slug) }}" id="form-delete">
        @csrf
        @method('DELETE')
    </form>
    @push('scripts')
        <script>
            function reply(id, name) {
                document.querySelector('#reply-message').textContent = "Membalas komentar " + name
                document.querySelector('[name="parent_id"]').value = id
                document.querySelector('#clear-reply').classList.remove("hidden");
            }

            function clearReply() {
                document.querySelector('#reply-message').textContent = null
                document.querySelector('[name="parent_id"]').value = null
                document.querySelector('#clear-reply').classList.add("hidden");
            }

            function destroyData() {
                const isDelete = confirm('Apakah anda yakin ingin menghapus postingan anda?');

                if(!isDelete) return false

                document.querySelector('#form-delete').submit()
            }

            document.addEventListener("DOMContentLoaded", () => {
                console.log("Hello World!");
            })
        </script>
    @endpush
</x-app-layout>
