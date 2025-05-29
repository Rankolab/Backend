<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Services\SeoAnalyzerService;
use App\Services\ImageGenerationService;
use App\Services\SummarizationService; // Added
use App\Services\KeywordExtractionService; // Added
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    protected $seoAnalyzer;
    protected $imageGenerator;
    protected $summarizer; // Added
    protected $keywordExtractor; // Added

    // Inject services via constructor
    public function __construct(
        SeoAnalyzerService $seoAnalyzer,
        ImageGenerationService $imageGenerator,
        SummarizationService $summarizer, // Added
        KeywordExtractionService $keywordExtractor // Added
    ) {
        $this->seoAnalyzer = $seoAnalyzer;
        $this->imageGenerator = $imageGenerator;
        $this->summarizer = $summarizer; // Added
        $this->keywordExtractor = $keywordExtractor; // Added
    }

    public function index()
    {
        // Include view_count in the retrieval and display
        $blogs = Blog::with(["author", "categories", "tags"])->latest()->paginate(15);
        // Pass view count to the view (it's already on the model)
        return view("admin.blogs.index", compact("blogs"));
    }

    public function create()
    {
        $authors = User::all();
        $categories = Category::all();
        $tags = Tag::all();
        $seoAnalysis = ["score" => 0, "recommendations" => []];
        return view("admin.blogs.create", compact("authors", "categories", "tags", "seoAnalysis"));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            "title" => "required|string|max:255",
            "excerpt" => "nullable|string|max:500",
            "body" => "required|string",
            "status" => "required|in:draft,published",
            "author_id" => "required|exists:users,id",
            "categories" => "nullable|array",
            "categories.*" => "exists:categories,id",
            "tags" => "nullable|array",
            "tags.*" => "exists:tags,id",
            "cover_image" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "meta_keywords" => "nullable|string|max:255",
            "meta_description" => "nullable|string|max:500",
        ]);

        $data = $request->only(["title", "excerpt", "body", "status", "author_id", "meta_keywords", "meta_description"]);
        $data["slug"] = Str::slug($request->title);

        // --- AI Content Generation (if fields are empty) ---
        $blogBody = strip_tags($data["body"]); // Use cleaned body for AI

        // Generate Excerpt if empty
        if (empty($data["excerpt"])) {
            Log::info("Attempting to generate excerpt for new blog post.");
            $generatedExcerpt = $this->summarizer->summarizeText($blogBody, 150, 50); // Adjust lengths as needed
            if ($generatedExcerpt) {
                $data["excerpt"] = $generatedExcerpt;
                Log::info("Generated excerpt successfully.");
            } else {
                 Log::warning("Failed to generate excerpt.");
            }
        }

        // Generate Meta Description if empty (use excerpt or generate new)
        if (empty($data["meta_description"])) {
            $data["meta_description"] = $data["excerpt"]; // Default to excerpt
            // Optionally, generate specifically for meta description:
            // $generatedMetaDesc = $this->summarizer->summarizeText($blogBody, 160, 60);
            // if ($generatedMetaDesc) $data["meta_description"] = $generatedMetaDesc;
        }

        // Generate Meta Keywords if empty
        if (empty($data["meta_keywords"])) {
            Log::info("Attempting to generate keywords for new blog post.");
            $generatedKeywords = $this->keywordExtractor->extractKeywords($blogBody);
            if (!empty($generatedKeywords)) {
                $data["meta_keywords"] = implode(", ", $generatedKeywords);
                Log::info("Generated keywords successfully: " . $data["meta_keywords"]);
            } else {
                 Log::warning("Failed to generate keywords.");
            }
        }
        // --- End AI Content Generation ---

        if ($request->hasFile("cover_image")) {
            $path = $request->file("cover_image")->store("public/blog_covers");
            $data["cover_image"] = Storage::url($path);
        }

        $blog = Blog::create($data);

        if ($request->has("categories")) {
            $blog->categories()->sync($request->categories);
        }
        if ($request->has("tags")) {
            $blog->tags()->sync($request->tags);
        }

        $seoAnalysis = $this->seoAnalyzer->analyze($blog);
        session()->flash("seoAnalysis", $seoAnalysis);

        return redirect()->route("admin.blogs.index")->with("success", "Blog post created successfully (AI enhancements applied where needed).");
    }

    public function show(Blog $blog)
    {
        $blog->load(["author", "categories", "tags"]);
        $seoAnalysis = $this->seoAnalyzer->analyze($blog);
        // Pass view count (already on model)
        return view("admin.blogs.show", compact("blog", "seoAnalysis"));
    }

    public function edit(Blog $blog)
    {
        $authors = User::all();
        $categories = Category::all();
        $tags = Tag::all();
        $blog->load(["categories", "tags"]);
        $seoAnalysis = $this->seoAnalyzer->analyze($blog);
        // Pass view count (already on model)
        return view("admin.blogs.edit", compact("blog", "authors", "categories", "tags", "seoAnalysis"));
    }

    public function update(Request $request, Blog $blog)
    {
        $validatedData = $request->validate([
            "title" => "required|string|max:255",
            "excerpt" => "nullable|string|max:500",
            "body" => "required|string",
            "status" => "required|in:draft,published",
            "author_id" => "required|exists:users,id",
            "categories" => "nullable|array",
            "categories.*" => "exists:categories,id",
            "tags" => "nullable|array",
            "tags.*" => "exists:tags,id",
            "cover_image" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "meta_keywords" => "nullable|string|max:255",
            "meta_description" => "nullable|string|max:500",
        ]);

        $data = $request->only(["title", "excerpt", "body", "status", "author_id", "meta_keywords", "meta_description"]);

        if ($blog->title !== $request->title) {
             $data["slug"] = Str::slug($request->title);
        }

        // --- AI Content Generation on Update (if fields are empty) ---
        $blogBody = strip_tags($data["body"]);

        if (empty($data["excerpt"])) {
            Log::info("Attempting to generate excerpt during update for blog ID: " . $blog->id);
            $generatedExcerpt = $this->summarizer->summarizeText($blogBody, 150, 50);
            if ($generatedExcerpt) $data["excerpt"] = $generatedExcerpt;
        }
        if (empty($data["meta_description"])) {
             $data["meta_description"] = $data["excerpt"]; // Default to excerpt
        }
        if (empty($data["meta_keywords"])) {
            Log::info("Attempting to generate keywords during update for blog ID: " . $blog->id);
            $generatedKeywords = $this->keywordExtractor->extractKeywords($blogBody);
            if (!empty($generatedKeywords)) $data["meta_keywords"] = implode(", ", $generatedKeywords);
        }
        // --- End AI Content Generation ---

        if ($request->hasFile("cover_image")) {
            if ($blog->cover_image) {
                $oldPath = str_replace(Storage::url(""), "public/", $blog->cover_image);
                Storage::delete($oldPath);
            }
            $path = $request->file("cover_image")->store("public/blog_covers");
            $data["cover_image"] = Storage::url($path);
        }

        $blog->update($data);

        $blog->categories()->sync($request->input("categories", []));
        $blog->tags()->sync($request->input("tags", []));

        $seoAnalysis = $this->seoAnalyzer->analyze($blog->fresh());
        session()->flash("seoAnalysis", $seoAnalysis);

        return redirect()->route("admin.blogs.edit", $blog->id)->with("success", "Blog post updated successfully (AI enhancements applied where needed).");
    }

    public function destroy(Blog $blog)
    {
        if ($blog->cover_image) {
            $oldPath = str_replace(Storage::url(""), "public/", $blog->cover_image);
            Storage::delete($oldPath);
        }

        $blog->categories()->detach();
        $blog->tags()->detach();

        $blog->delete();
        return redirect()->route("admin.blogs.index")->with("success", "Blog post deleted successfully.");
    }

    /**
     * Generate a cover image for the blog post using the configured service.
     */
    public function generateImage(Request $request, Blog $blog)
    {
        Log::info("Attempting to generate image for blog ID: " . $blog->id);
        $imageUrl = $this->imageGenerator->generateCoverImage($blog);

        if ($imageUrl) {
            $blog->cover_image = $imageUrl;
            $blog->save();
            Log::info("Successfully generated and saved image for blog ID: " . $blog->id . " URL: " . $imageUrl);
            return back()->with("success", "Cover image generated and saved successfully.");
        } else {
            Log::error("Failed to generate cover image for blog ID: " . $blog->id);
            return back()->with("error", "Failed to generate cover image. Check logs for details (model might be loading or API key needed).");
        }
    }

    // --- Optional: Add dedicated endpoints for AI generation on demand --- 
    /*
    public function generateExcerpt(Request $request, Blog $blog)
    {
        $generatedExcerpt = $this->summarizer->summarizeText(strip_tags($blog->body));
        if ($generatedExcerpt) {
            return response()->json(["success" => true, "excerpt" => $generatedExcerpt]);
        } else {
            return response()->json(["success" => false, "message" => "Failed to generate excerpt."], 500);
        }
    }

    public function generateKeywords(Request $request, Blog $blog)
    {
        $generatedKeywords = $this->keywordExtractor->extractKeywords(strip_tags($blog->body));
        if ($generatedKeywords !== null) { // Check for null specifically
            return response()->json(["success" => true, "keywords" => implode(", ", $generatedKeywords)]);
        } else {
            return response()->json(["success" => false, "message" => "Failed to generate keywords."], 500);
        }
    }
    */
}

