@extends("layouts.admin") {{-- Assuming admin layout --}}

@section("title", "Create New Blog Post")

@push("styles")
{{-- Add any specific styles for this page if needed --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* EasyMDE Styles */
    .editor-toolbar, .EasyMDEContainer .CodeMirror {
        border-color: #d1d5db; /* Match Tailwind gray-300 */
    }
    .editor-toolbar button {
        color: #374151; /* Match Tailwind gray-700 */
    }
    .editor-toolbar button.active, .editor-toolbar button:hover {
        background: #e5e7eb; /* Match Tailwind gray-200 */
        border-color: #9ca3af; /* Match Tailwind gray-400 */
    }
    .CodeMirror {
        min-height: 300px; /* Ensure editor has decent height */
        background-color: #fff;
        color: #111827;
    }
    /* Dark mode adjustments */
    .dark .editor-toolbar, .dark .EasyMDEContainer .CodeMirror {
        border-color: #4b5563; /* Match Tailwind dark:border-gray-600 */
    }
    .dark .editor-toolbar button {
        color: #d1d5db; /* Match Tailwind dark:text-gray-300 */
    }
    .dark .editor-toolbar button.active, .dark .editor-toolbar button:hover {
        background: #374151; /* Match Tailwind dark:bg-gray-700 */
        border-color: #6b7280; /* Match Tailwind dark:border-gray-500 */
    }
    .dark .CodeMirror {
        background-color: #374151; /* Match Tailwind dark:bg-gray-700 */
        color: #f3f4f6; /* Match Tailwind dark:text-gray-100 */
    }
    .dark .editor-preview, .dark .editor-preview-side {
        background-color: #1f2937; /* Match Tailwind dark:bg-gray-800 */
        color: #f3f4f6;
    }
    /* Select2 Styles */
    .select2-container .select2-selection--multiple {
        min-height: 38px; /* Match input height */
        border-color: #d1d5db; /* Match Tailwind gray-300 */
        background-color: #fff;
    }
    .dark .select2-container .select2-selection--multiple {
        border-color: #4b5563; /* Match Tailwind dark:border-gray-600 */
        background-color: #374151; /* Match Tailwind dark:bg-gray-700 */
    }
    .select2-container .select2-selection--multiple .select2-selection__choice {
        background-color: #e0e7ff; /* Indigo-100 */
        border-color: #c7d2fe; /* Indigo-200 */
        color: #3730a3; /* Indigo-800 */
    }
    .dark .select2-container .select2-selection--multiple .select2-selection__choice {
        background-color: #4338ca; /* Indigo-700 */
        border-color: #5b21b6; /* Indigo-600 */
        color: #e0e7ff; /* Indigo-100 */
    }
    .select2-container .select2-search--inline .select2-search__field {
        margin-top: 5px;
        color: #111827;
    }
    .dark .select2-container .select2-search--inline .select2-search__field {
        color: #f3f4f6;
    }
    .select2-dropdown {
        border-color: #d1d5db;
        background-color: #fff;
    }
    .dark .select2-dropdown {
        border-color: #4b5563;
        background-color: #374151;
    }
    .select2-results__option {
        color: #111827;
    }
    .dark .select2-results__option {
        color: #f3f4f6;
    }
    .select2-results__option--highlighted {
        background-color: #6366f1; /* Indigo-500 */
        color: white;
    }
    .dark .select2-results__option--highlighted {
        background-color: #4f46e5; /* Indigo-600 */
    }
</style>
@endpush

@section("content")
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Create New Blog Post</h1>
        <nav class="text-sm font-medium text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex space-x-2">
                <li><a href="{{ route("admin.dashboard") }}" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a></li>
                <li><span>&gt;</span></li>
                <li><a href="{{ route("admin.blogs.index") }}" class="hover:text-gray-700 dark:hover:text-gray-200">Content</a></li>
                <li><span>&gt;</span></li>
                <li class="text-gray-700 dark:text-gray-200" aria-current="page">Create Post</li>
            </ol>
        </nav>
    </div>

    <!-- Validation Errors -->
    @include("partials.validation_errors")

    <!-- Create Blog Form -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <form method="POST" action="{{ route("admin.blogs.store") }}" enctype="multipart/form-data"> {{-- Added enctype for file uploads --}}
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Main Content Area --}}
                <div class="md:col-span-2 space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old("title") }}" required
                               class="mt-1 block w-full px-3 py-2 border {{ $errors->has("title") ? "border-red-500" : "border-gray-300 dark:border-gray-600" }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                        @error("title") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Body (Rich Text Editor) -->
                    <div>
                        <label for="body" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Body <span class="text-red-500">*</span></label>
                        <textarea name="body" id="body" class="mt-1">{{ old("body") }}</textarea> {{-- Removed classes handled by EasyMDE --}}
                        @error("body") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                     <!-- Excerpt -->
                    <div>
                        <label for="excerpt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Excerpt (Optional)</label>
                        <textarea name="excerpt" id="excerpt" rows="3"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">{{ old("excerpt") }}</textarea>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">A short summary of the post, used for previews and meta descriptions if not specified below.</p>
                        @error("excerpt") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- SEO Section -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">SEO Settings</h3>
                        <div class="mt-4 space-y-4">
                            <!-- Meta Keywords -->
                            <div>
                                <label for="meta_keywords" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Keywords (Optional)</label>
                                <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old("meta_keywords") }}"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Comma-separated keywords relevant to the post.</p>
                                @error("meta_keywords") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <!-- Meta Description -->
                            <div>
                                <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Description (Optional)</label>
                                <textarea name="meta_description" id="meta_description" rows="2"
                                          class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">{{ old("meta_description") }}</textarea>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">A brief description for search engines (defaults to excerpt if empty).</p>
                                @error("meta_description") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            {{-- Placeholder for SEO Score --}}
                            {{-- <div class="mt-4">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">SEO Score:</p>
                                <div class="mt-1 h-2 bg-gray-200 dark:bg-gray-600 rounded-full">
                                    <div class="h-2 bg-green-500 rounded-full" style="width: 75%;"></div>
                                </div>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Good (75/100)</p>
                            </div> --}}
                        </div>
                    </div>
                </div>

                {{-- Sidebar Area --}}
                <div class="md:col-span-1 space-y-6">
                    <!-- Publish Box -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">Publish</h3>
                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status <span class="text-red-500">*</span></label>
                            <select name="status" id="status" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border {{ $errors->has("status") ? "border-red-500" : "border-gray-300 dark:border-gray-600" }} focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-900 dark:text-white">
                                <option value="draft" {{ old("status", "draft") == "draft" ? "selected" : "" }}>Draft</option>
                                <option value="published" {{ old("status") == "published" ? "selected" : "" }}>Published</option>
                            </select>
                            @error("status") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <!-- Author -->
                        <div class="mt-4">
                            <label for="author_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Author <span class="text-red-500">*</span></label>
                            <select name="author_id" id="author_id" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border {{ $errors->has("author_id") ? "border-red-500" : "border-gray-300 dark:border-gray-600" }} focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-900 dark:text-white">
                                <option value="" disabled {{ old("author_id") ? "" : "selected" }}>Select an author...</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}" {{ old("author_id", auth()->id()) == $author->id ? "selected" : "" }}>{{ $author->name }}</option>
                                @endforeach
                            </select>
                            @error("author_id") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <!-- Form Actions -->
                        <div class="mt-6 flex justify-between items-center">
                            <a href="{{ route("admin.blogs.index") }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">Cancel</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Create Post
                            </button>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <label for="categories" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Categories</label>
                        <select name="categories[]" id="categories" multiple="multiple" class="select2-multiple block w-full text-sm dark:text-white">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ in_array($category->id, old("categories", [])) ? "selected" : "" }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error("categories") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        @error("categories.*") <p class="mt-2 text-sm text-red-600">Invalid category selected.</p> @enderror
                    </div>

                    <!-- Tags -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <label for="tags" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tags</label>
                        <select name="tags[]" id="tags" multiple="multiple" class="select2-multiple block w-full text-sm dark:text-white">
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" {{ in_array($tag->id, old("tags", [])) ? "selected" : "" }}>{{ $tag->name }}</option>
                            @endforeach
                        </select>
                        @error("tags") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        @error("tags.*") <p class="mt-2 text-sm text-red-600">Invalid tag selected.</p> @enderror
                    </div>

                    <!-- Cover Image -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <label for="cover_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cover Image (Optional)</label>
                        <input type="file" name="cover_image" id="cover_image"
                               class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800"/>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Upload an image (JPG, PNG, GIF, SVG). Max 2MB.</p>
                        @error("cover_image") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Placeholder for Automatic Image Generation --}}
                    {{-- <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">Generate Image</h3>
                        <button type="button" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Generate Relevant Image
                        </button>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Automatically generate a cover image based on the title/content.</p>
                    </div> --}}

                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push("scripts")
<script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> {{-- Select2 requires jQuery --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function(){
        // Initialize EasyMDE Editor
        const easyMDE = new EasyMDE({
            element: document.getElementById("body"),
            spellChecker: false,
            status: false, // Can enable if desired: ["lines", "words", "cursor"]
            toolbar: [
                "bold", "italic", "heading", "|", 
                "quote", "unordered-list", "ordered-list", "|", 
                "link", "image", "table", "|", 
                "preview", "side-by-side", "fullscreen", "|", 
                "guide"
            ],
            // You might want to configure image upload handling here if needed
            // See EasyMDE documentation for imageUploadEndpoint, etc.
        });

        // Initialize Select2 for Categories and Tags
        $(".select2-multiple").select2({
            placeholder: "Select options",
            allowClear: true,
            // theme: "classic" // Optional theme
        });
    });
</script>
@endpush

