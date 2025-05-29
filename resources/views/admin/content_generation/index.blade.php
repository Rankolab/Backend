@extends("layouts.admin")

@section("content")
<div class="container-fluid">
    <h1 class="mt-4">Content Generation</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Content Generation</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-robot me-1"></i>
            Generate Content using AI
        </div>
        <div class="card-body">
            @include("partials.flash_messages")
            @include("partials.validation_errors")

            <p>Use this tool to generate various types of content based on your inputs and selected AI models.</p>
            
            <form action="{{ route("admin.content.generation.generate") }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="content_type" class="form-label">Content Type</label>
                    <select class="form-control" id="content_type" name="content_type" required>
                        <option value="">Select Type...</option>
                        <option value="blog_post" {{ old("content_type") == "blog_post" ? "selected" : "" }}>Blog Post</option>
                        <option value="product_description" {{ old("content_type") == "product_description" ? "selected" : "" }}>Product Description</option>
                        <option value="social_media_post" {{ old("content_type") == "social_media_post" ? "selected" : "" }}>Social Media Post</option>
                        <option value="ad_copy" {{ old("content_type") == "ad_copy" ? "selected" : "" }}>Ad Copy</option>
                        {{-- Add more content types as needed --}}
                    </select>
                </div>

                <div class="mb-3">
                    <label for="prompt" class="form-label">Prompt / Topic</label>
                    <textarea class="form-control" id="prompt" name="prompt" rows="3" placeholder="Enter the main topic, keywords, or a detailed prompt..." required>{{ old("prompt") }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="tone" class="form-label">Tone of Voice</label>
                    <select class="form-control" id="tone" name="tone">
                        <option value="professional" {{ old("tone", "professional") == "professional" ? "selected" : "" }}>Professional</option>
                        <option value="casual" {{ old("tone") == "casual" ? "selected" : "" }}>Casual</option>
                        <option value="informative" {{ old("tone") == "informative" ? "selected" : "" }}>Informative</option>
                        <option value="witty" {{ old("tone") == "witty" ? "selected" : "" }}>Witty</option>
                        {{-- Add more tones --}}
                    </select>
                </div>
                
                {{-- Add other options like target audience, length, AI model selection etc. --}}

                <button class="btn btn-primary" type="submit">Generate Content</button>
            </form>

            {{-- Placeholder for displaying generated content or progress --}}
            <div id="generated-content" class="mt-4">
                {{-- Results will be loaded here --}}
                @if (session("generated_content"))
                    <div class="alert alert-success">
                        <h5>Generated Content:</h5>
                        <pre>{{ session("generated_content") }}</pre>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Optional: List recent generation history --}}
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-history me-1"></i>
            Generation History
        </div>
        <div class="card-body">
            {{-- TODO: Fetch and display recent content generation records --}}
            <p class="text-muted">Content generation history will be shown here.</p>
        </div>
    </div>

</div>
@endsection

