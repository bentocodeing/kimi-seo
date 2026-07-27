<?php

namespace App\Http\Controllers;

class MediaController extends Controller
{
    /**
     * Directories under the repository root that may be served.
     */
    private const ALLOWED_DIRS = ['assets', 'screenshots'];

    private const MIME_TYPES = [
        'svg' => 'image/svg+xml',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'webp' => 'image/webp',
        'ico' => 'image/x-icon',
        'mp4' => 'video/mp4',
    ];

    /**
     * Serve a media file from the whitelisted repo-root directories.
     * Paths are resolved and containment-checked, so traversal outside
     * the whitelist (or outside the repo) is impossible.
     */
    public function show(string $path)
    {
        $root = realpath(base_path('..'));
        $full = $root ? realpath($root.DIRECTORY_SEPARATOR.$path) : false;

        abort_if($root === false || $full === false || ! is_file($full), 404);

        $allowed = array_filter(array_map(
            fn (string $dir) => realpath($root.DIRECTORY_SEPARATOR.$dir) ?: null,
            self::ALLOWED_DIRS,
        ));

        $contained = false;
        foreach ($allowed as $dir) {
            if (str_starts_with($full, $dir.DIRECTORY_SEPARATOR)) {
                $contained = true;
                break;
            }
        }

        abort_unless($contained, 404);

        $mime = self::MIME_TYPES[strtolower(pathinfo($full, PATHINFO_EXTENSION))] ?? null;

        abort_if($mime === null, 404);

        return response()->file($full, [
            'Content-Type' => $mime,
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
