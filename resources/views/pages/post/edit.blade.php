<x-app-layout>
    @push('styles')
        <style>
            .ck-content h1 {
                font-size: 2em;
                margin: .67em 0
            }

            .ck-content h2 {
                font-size: 1.5em;
                margin: .75em 0
            }

            .ck-content h3 {
                font-size: 1.17em;
                margin: .83em 0
            }

            .ck-content h5 {
                font-size: .83em;
                margin: 1.5em 0
            }

            .ck-content h6 {
                font-size: .75em;
                margin: 1.67em 0
            }

            .ck-content ul {
                list-style: inside !important;
            }

            .ck-content ol {
                list-style-position: inside !important;
            }
        </style>
    @endpush
    <x-slot name="header">
        <h2 class="leading-tight">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a
                        href="{{ route('home') }}">Blog</a>
                </li>
                <li class="breadcrumb-item">
                    <a
                        href="{{ route('post.show', $post->slug) }}">{{ $post->title }}</a>
                </li>
                <li class="breadcrumb-item breadcrumb-active">Edit</li>
            </ol>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-pink-500/10 dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form method="post" action="{{ route('post.update', $post->slug) }}" class="mt-6 space-y-6"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name">{{ __('Gambar') }}</x-input-label>
                            <img src="{{ asset($post->image ?? 'assets/images/default-image.jpg') }}"
                                alt="Preview Image" class="aspect-[4/3] object-cover w-full" id="preview-img">
                        </div>

                        <div>
                            <div class="w-full">
                                <x-input-label for="image">{{ __('File Gambar') }}</x-input-label>
                                <input id="image"
                                    class="block w-full px-3 py-2 text-base font-normal text-gray-700 bg-white dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 border border-solid border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    type="file" accept="image/png, image/jpg, image/jpeg" name="image"
                                    aria-describedby="pictureHelp" />
                                <div id="pictureHelp" class="text-xs text-slate-400 mt-1">Format gambar JPG, JPEG, PNG.
                                    Maks.
                                    2MB</div>
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="title" :value="__('Judul')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                :value="old('title', $post->title)" required autofocus autocomplete="title" />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="categories" :value="__('Kategori')" />
                            <div class="flex flex-row flex-wrap gap-x-3 gap-y-2 mt-1">
                                @foreach ($categories as $category)
                                    <div>
                                        <input type="checkbox" id="category-{{ $category->id }}" name="categories[]"
                                            value="{{ $category->id }}" class="hidden peer"
                                            @checked($category->posts_count > 0)>
                                        <label for="category-{{ $category->id }}"
                                            class="inline-flex items-center justify-between w-full py-1 px-2 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 peer-checked:border-pink-600 dark:peer-checked:border-pink-600 hover:text-gray-600 dark:peer-checked:text-gray-300 peer-checked:text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                                            <div class="block">
                                                <div class="w-full text-sm font-semibold">{{ $category->name }}</div>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('categories')" />
                        </div>

                        <div>
                            <x-input-label for="preview" :value="__('Pratinjau')" />
                            <x-textarea id="preview" class="mt-1 block w-full"
                                name="preview">{{ old('preview', $post->preview) }}</x-textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('preview')" />
                        </div>
                        <div>
                            <x-input-label for="content" :value="__('Postingan')" />
                            <x-textarea id="content" class="mt-1 block w-full"
                                name="content">{{ old('content', $post->content) }}</x-textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('content')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Simpan') }}</x-primary-button>

                            @if (session('status') === 'profile-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('assets/ckeditor5-38.1.0/build/ckeditor.js') }}"></script>
        <script>
            function eventFile(input) {
                // Validate
                if (input.files && input.files[0]) {
                    let fileSize = input.files[0].size / 1024 / 1024; //MB Format
                    let fileType = input.files[0].type;

                    // validate size
                    if (fileSize > 10) {
                        alert('Ukuran File tidak boleh lebih dari 2mb !');
                        input.value = '';
                        return false;
                    }

                    // validate type
                    if (["image/jpeg", "image/jpg", "image/png"].indexOf(fileType) < 0) {
                        alert('Format File tidak valid !');
                        input.value = '';
                        return false;
                    }

                    let reader = new FileReader();

                    reader.onload = function(e) {
                        document.querySelector('#preview-img').setAttribute('src', e.target.result)
                    }

                    reader.readAsDataURL(input.files[0]); // convert to base64 string
                }
            }
            document.addEventListener("DOMContentLoaded", () => {
                console.log("Hello World!");

                ClassicEditor
                    .create(document.querySelector('#content'))
                    .catch(error => {
                        console.error(error);
                    });

                // Handle File upload
                document.querySelector('#image').addEventListener('change', e => {
                    if (e.target.files.length == 0) {
                        // $('.profile').attr('src', defaultImage);
                    } else {
                        eventFile(e.target);
                    }
                })
            })
        </script>
    @endpush
</x-app-layout>
