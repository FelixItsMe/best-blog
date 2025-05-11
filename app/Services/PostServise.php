<?php

namespace App\Services;

class PostServise
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    
    public function sanitizeContent(string $content) : string {
        $allowedTags = '<h1><h2><h3><h4><h5><h6><p><i><strong><ul><ol><li><a><blockquote>';

        // Remove disallowed tags and attributes
        $strip_content = strip_tags($content, $allowedTags);
        $strip_content = preg_replace('/<(.*?)>/i', '<$1>', $strip_content);

        return $strip_content;
    }
}
