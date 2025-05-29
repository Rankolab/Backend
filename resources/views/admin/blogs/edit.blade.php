@extends("layouts.admin") {{-- Assuming admin layout --}}

@section("title", "Edit Blog Post: " . $blog->title)

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
    /* SEO Score Bar */
    .seo-score-bar {
        height: 8px;
        border-radius: 9999px;
        background-color: #e5e7eb; /* gray-200 */
    }
    .dark .seo-score-bar {
        background-color: #4b5563; /* gray-600 */
    }
    .seo-score-bar-inner {
        height: 100%;
        border-radius: 9999px;
        transition: width 0.5s ease-in-out;
    }
</style>
@endpush

@section("content")
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit Blog Post</h1>
        <nav class="text-sm font-medium text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex space-x-2">
                <li><a href="{{ route("admin.dashboard") }}" class="hover:text-gray-700 dark:hover:text-gray-200">Dashboard</a></li>
                <li><span>&gt;</span></li>
                <li><a href="{{ route("admin.blogs.index") }}" class="hover:text-gray-700 dark:hover:text-gray-200">Content</a></li>
                <li><span>&gt;</span></li>
                <li class="text-gray-700 dark:text-gray-200" aria-current="page">Edit: {{ Str::limit($blog->title, 30) }}</li>
            </ol>
        </nav>
    </div>

    <!-- Session Messages -->
    @include("partials.session_messages")

    <!-- Validation Errors -->
    @include("partials.validation_errors")

    <!-- Edit Blog Form -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <form method="POST" action="{{ route("admin.blogs.update", $blog->id) }}" enctype="multipart/form-data" id="edit-blog-form"> {{-- Added ID --}}
            @csrf
            @method("PUT")

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Main Content Area --}}
                <div class="md:col-span-2 space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old("title", $blog->title) }}" required
                               class="mt-1 block w-full px-3 py-2 border {{ $errors->has("title") ? "border-red-500" : "border-gray-300 dark:border-gray-600" }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                        @error("title") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Body (Rich Text Editor) -->
                    <div>
                        <label for="body" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Body <span class="text-red-500">*</span></label>
                        <textarea name="body" id="body" class="mt-1">{{ old("body", $blog->body) }}</textarea> {{-- Removed classes handled by EasyMDE --}}
                        @error("body") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                     <!-- Excerpt -->
                    <div>
                        <label for="excerpt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Excerpt (Optional)</label>
                        <textarea name="excerpt" id="excerpt" rows="3"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">{{ old("excerpt", $blog->excerpt) }}</textarea>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">A short summary of the post, used for previews and meta descriptions if not specified below.</p>
                        @error("excerpt") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- SEO Section -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">SEO Analysis & Settings</h3>
                        
                        {{-- SEO Score Display --}}
                        @php
                            // Get analysis from session flash if available (after update), otherwise from controller
                            $currentSeoAnalysis = session("seoAnalysis") ?? $seoAnalysis;
                            $seoScore = $currentSeoAnalysis["score"] ?? 0;
                            $scoreColor = "bg-red-500"; // Default red
                            if ($seoScore >= 75) {
                                $scoreColor = "bg-green-500"; // Green for good
                            } elseif ($seoScore >= 50) {
                                $scoreColor = "bg-yellow-500"; // Yellow for okay
                            }
                        @endphp
                        <div class="mt-4">
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">SEO Score</span>
                                <span class="text-sm font-medium {{ $seoScore >= 75 ? "text-green-600 dark:text-green-400" : ($seoScore >= 50 ? "text-yellow-600 dark:text-yellow-400" : "text-red-600 dark:text-red-400") }}">{{ $seoScore }}/100</span>
                            </div>
                            <div class="seo-score-bar w-full">
                                <div class="seo-score-bar-inner {{ $scoreColor }}" style="width: {{ $seoScore }}%;"></div>
                            </div>
                        </div>

                        {{-- SEO Recommendations --}}
                        @if(!empty($currentSeoAnalysis["recommendations"]))
                            <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-400 dark:border-yellow-600 rounded-r-lg">
                                <h4 class="text-sm font-semibold text-yellow-800 dark:text-yellow-200 mb-2">Recommendations:</h4>
                                <ul class="list-disc list-inside space-y-1 text-sm text-yellow-700 dark:text-yellow-300">
                                    @foreach($currentSeoAnalysis["recommendations"] as $recommendation)
                                        <li>{{ $recommendation }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @elseif($seoScore > 0) {{-- Show success if score > 0 and no recommendations --}}
                             <div class="mt-4 p-4 bg-green-50 dark:bg-green-900 border-l-4 border-green-400 dark:border-green-600 rounded-r-lg">
                                <p class="text-sm font-medium text-green-700 dark:text-green-300">Looking good! No major SEO recommendations.</p>
                            </div>
                        @endif

                        <div class="mt-6 space-y-4">
                            <!-- Meta Keywords -->
                            <div>
                                <label for="meta_keywords" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Keywords (Optional)</label>
                                <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old("meta_keywords", $blog->meta_keywords) }}"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Comma-separated keywords relevant to the post.</p>
                                @error("meta_keywords") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <!-- Meta Description -->
                            <div>
                                <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Description (Optional)</label>
                                <textarea name="meta_description" id="meta_description" rows="2"
                                          class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">{{ old("meta_description", $blog->meta_description) }}</textarea>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">A brief description for search engines (defaults to excerpt if empty).</p>
                                @error("meta_description") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
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
                                <option value="draft" {{ old("status", $blog->status) == "draft" ? "selected" : "" }}>Draft</option>
                                <option value="published" {{ old("status", $blog->status) == "published" ? "selected" : "" }}>Published</option>
                            </select>
                            @error("status") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <!-- Author -->
                        <div class="mt-4">
                            <label for="author_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Author <span class="text-red-500">*</span></label>
                            <select name="author_id" id="author_id" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border {{ $errors->has("author_id") ? "border-red-500" : "border-gray-300 dark:border-gray-600" }} focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-900 dark:text-white">
                                <option value="" disabled>Select an author...</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}" {{ old("author_id", $blog->author_id) == $author->id ? "selected" : "" }}>{{ $author->name }}</option>
                                @endforeach
                            </select>
                            @error("author_id") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <!-- Form Actions -->
                        <div class="mt-6 flex justify-between items-center">
                            <a href="{{ route("admin.blogs.index") }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">Cancel</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Update Post
                            </button>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <label for="categories" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Categories</label>
                        <select name="categories[]" id="categories" multiple="multiple" class="select2-multiple block w-full text-sm dark:text-white">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ in_array($category->id, old("categories", $blog->categories->pluck("id")->toArray())) ? "selected" : "" }}>{{ $category->name }}</option>
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
                                <option value="{{ $tag->id }}" {{ in_array($tag->id, old("tags", $blog->tags->pluck("id")->toArray())) ? "selected" : "" }}>{{ $tag->name }}</option>
                            @endforeach
                        </select>
                        @error("tags") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        @error("tags.*") <p class="mt-2 text-sm text-red-600">Invalid tag selected.</p> @enderror
                    </div>

                    <!-- Cover Image -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <label for="cover_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cover Image</label>
                        @if($blog->cover_image)
                            <div class="mt-2 mb-2">
                                <img src="{{ $blog->cover_image }}" alt="Current Cover Image" class="max-h-40 rounded shadow">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Current image</p>
                            </div>
                        @endif
                        <input type="file" name="cover_image" id="cover_image"
                               class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800"/>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Upload a new image to replace the current one (JPG, PNG, GIF, SVG). Max 2MB.</p>
                        @error("cover_image") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        
                        {{-- Automatic Image Generation Button --}}
                        <div class="mt-4 border-t border-gray-200 dark:border-gray-600 pt-4">
                            <button type="button" id="generate-image-btn" 
                                    class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" id="generate-spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span id="generate-btn-text">Generate Cover Image</span>
                            </button>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Automatically generate a cover image based on the title/content. This will replace any uploaded image if successful.</p>
                            <form id="generate-image-form" method="POST" action="{{ route("admin.blogs.generate_image", $blog->id) }}" class="hidden">
                                @csrf
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </form>

        <!-- Delete Form -->
        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
             <form method="POST" action="{{ route("admin.blogs.destroy", $blog->id) }}" onsubmit="return confirm("Are you sure you want to delete this post?");">
                @csrf
                @method("DELETE")
                <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium">
                    Delete this post
                </button>
            </form>
        </div>
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

        // Image Generation Button Handler
        const generateBtn = document.getElementById("generate-image-btn");
        const generateForm = document.getElementById("generate-image-form");
        const spinner = document.getElementById("generate-spinner");
        const btnText = document.getElementById("generate-btn-text");

        if (generateBtn && generateForm) {
            generateBtn.addEventListener("click", function() {
                // Show spinner, disable button
                spinner.classList.remove("hidden");
                btnText.textContent = "Generating...";
                generateBtn.disabled = true;

                // Submit the hidden form
                generateForm.submit();
            });
        }
    });
</script>
@endpush

