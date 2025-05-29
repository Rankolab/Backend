<?php

namespace App\Services;

use App\Models\Blog;
use Illuminate\Support\Str;

class SeoAnalyzerService
{
    // Constants for scoring weights
    private const SCORE_TITLE_LENGTH = 15;
    private const SCORE_META_DESC_LENGTH = 15;
    private const SCORE_META_KEYWORDS = 10;
    private const SCORE_BODY_LENGTH = 20;
    private const SCORE_IMAGE_ALT = 10; // Assuming image analysis is basic for now
    private const SCORE_INTERNAL_LINKS = 15;
    private const SCORE_EXTERNAL_LINKS = 15;

    /**
     * Analyze the SEO aspects of a blog post.
     *
     * @param Blog $blog
     * @return array ["score" => int, "recommendations" => array]
     */
    public function analyze(Blog $blog): array
    {
        $score = 0;
        $recommendations = [];

        // --- Gather Content --- 
        $title = $blog->title ?? "";
        $body = $blog->body ?? "";
        $excerpt = $blog->excerpt ?? "";
        $metaKeywords = $blog->meta_keywords ?? "";
        $metaDescription = $blog->meta_description ?? $excerpt; // Use excerpt if meta description is empty
        $coverImage = $blog->cover_image ?? null; // Assuming cover_image stores the URL

        // --- Perform Checks --- 

        // 1. Title Length (Optimal: 40-70 chars)
        $titleLength = Str::length($title);
        if ($titleLength >= 40 && $titleLength <= 70) {
            $score += self::SCORE_TITLE_LENGTH;
        } elseif ($titleLength > 0 && $titleLength < 40) {
            $score += 5;
            $recommendations[] = "Title is a bit short (optimal 40-70 characters).";
        } elseif ($titleLength > 70) {
            $score += 5;
            $recommendations[] = "Title is too long (optimal 40-70 characters).";
        } else {
            $recommendations[] = "Title is missing.";
        }

        // 2. Meta Description Length (Optimal: 120-160 chars)
        $metaDescLength = Str::length($metaDescription);
        if ($metaDescLength >= 120 && $metaDescLength <= 160) {
            $score += self::SCORE_META_DESC_LENGTH;
        } elseif ($metaDescLength > 0 && $metaDescLength < 120) {
            $score += 5;
            $recommendations[] = "Meta description is a bit short (optimal 120-160 characters).";
        } elseif ($metaDescLength > 160) {
            $score += 5;
            $recommendations[] = "Meta description is too long (optimal 120-160 characters).";
        } else {
            $recommendations[] = "Meta description is missing (using excerpt if available).";
        }

        // 3. Meta Keywords Presence
        if (!empty($metaKeywords)) {
            $score += self::SCORE_META_KEYWORDS;
        } else {
            $recommendations[] = "Consider adding meta keywords relevant to the content.";
        }

        // 4. Body Length (Optimal: > 300 words)
        // Simple word count, can be improved
        $wordCount = Str::wordCount(strip_tags($body)); // Strip HTML tags for basic count
        if ($wordCount >= 300) {
            $score += self::SCORE_BODY_LENGTH;
        } elseif ($wordCount > 100) {
            $score += 10;
            $recommendations[] = "Content length is okay, but longer content (300+ words) often ranks better.";
        } else {
             $recommendations[] = "Content is very short. Aim for at least 300 words.";
        }

        // 5. Image Presence & Alt Text (Basic Check)
        // This is a very basic check. A real implementation would parse HTML for <img> tags and check alt attributes.
        if (!empty($coverImage)) { // Check if a cover image URL exists
            // Assume alt text is handled elsewhere or add a field for it
            $score += self::SCORE_IMAGE_ALT; 
        } else {
            $recommendations[] = "Consider adding a cover image to the post.";
            // $recommendations[] = "Ensure all images have descriptive alt text."; // Add this if checking body images
        }

        // 6. Internal Links (Basic Check)
        $appUrl = config("app.url"); // Define $appUrl
        if (empty($appUrl)) {
             $recommendations[] = "APP_URL is not configured. Cannot check internal links accurately.";
             $internalLinks = 0; // Assume no internal links if URL is not set
        } else {
            $escapedAppUrl = preg_quote($appUrl, "/"); // Escape special regex characters in the URL
            $pattern = "/<a\\s+(?:[^>]*?\\s+)?href=([\"\"])(.*?)" . $escapedAppUrl . "(.*?)\\1/i";
            $internalLinks = preg_match_all($pattern, $body);
        }

        if ($internalLinks >= 1) {
            $score += self::SCORE_INTERNAL_LINKS;
        } else {
            $recommendations[] = "Consider adding internal links to relevant content on your site.";
        } // Close else for internal links check

        // 7. External Links (Basic Check)
        // Counts links NOT going to the same domain and not internal anchors/mailto/tel/javascript
        if (empty($appUrl)) {
             $recommendations[] = "APP_URL is not configured. Cannot check external links accurately.";
             $externalLinks = 0; // Assume no external links if URL is not set
        } else {
            // Reuse $escapedAppUrl from internal link check (defined above)
            $pattern_external = "/<a\\s+(?:[^>]*?\\s+)?href=([\"\"])(?!" . $escapedAppUrl . ")(?!(#|mailto:|tel:|javascript:))([^\"\"]+?)\\1/i";
            $externalLinks = preg_match_all($pattern_external, $body);
        }

        if ($externalLinks >= 1) {
            $score += self::SCORE_EXTERNAL_LINKS;
        } else {
            $recommendations[] = "Consider adding relevant external links to authoritative sources.";
        } // Close else for external links check

        // --- Final Score Calculation --- 
        $score = min(100, max(0, $score)); // Cap score between 0 and 100

        return [
            "score" => $score,
            "recommendations" => $recommendations,
        ];
    }
}

