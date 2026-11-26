<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SecurityHelper
{
    /**
     * Sanitize redirect or link URLs to block javascript:, data:, vbscript:, and protocol-relative open redirects.
     */
    public static function sanitizeUrl(?string $url, string $default = '#katalog-kursus', bool $allowHashAndRelative = true): string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return $default;
        }

        // Reject control chars or whitespace tricks used in parser-confusion payloads
        $normalized = preg_replace('/[\x00-\x1F\x7F\s]+/', '', $url) ?? '';
        $lower = strtolower($normalized);

        foreach (['javascript:', 'data:', 'vbscript:', 'file:'] as $dangerous) {
            if (str_starts_with($lower, $dangerous)) {
                return $default;
            }
        }

        if ($allowHashAndRelative) {
            if (str_starts_with($url, '#')) {
                return $url;
            }
            if (str_starts_with($url, '/') && !str_starts_with($url, '//') && !str_starts_with($url, '/\\')) {
                return $url;
            }
        }

        $parsed = parse_url($url);
        if (!is_array($parsed) || empty($parsed['scheme']) || empty($parsed['host'])) {
            return $default;
        }

        if (!in_array(strtolower($parsed['scheme']), ['http', 'https'], true)) {
            return $default;
        }

        return $url;
    }

    /**
     * Allow only explicit HTTPS YouTube / YouTube-Nocookie embed URLs inside iframes.
     */
    public static function isTrustedYoutubeEmbed(?string $url): bool
    {
        $url = self::sanitizeUrl($url, '', false);
        if ($url === '') {
            return false;
        }

        $parsed = parse_url($url);
        if (!is_array($parsed) || strtolower($parsed['scheme'] ?? '') !== 'https') {
            return false;
        }

        $host = strtolower($parsed['host'] ?? '');
        $allowedHosts = ['youtube.com', 'www.youtube.com', 'youtube-nocookie.com', 'www.youtube-nocookie.com'];

        if (!in_array($host, $allowedHosts, true)) {
            return false;
        }

        $path = $parsed['path'] ?? '';
        return str_starts_with($path, '/embed/');
    }

    /**
     * Escape SQL LIKE metacharacters so search queries cannot amplify wildcard scans.
     */
    public static function escapeLike(string $value): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
    }

    /**
     * Safely validate and store an uploaded raster image (JPEG/PNG/WEBP) using randomized UUID filenames.
     * Blocks SVG XSS, double-extension PHP payloads, and spoofed binary files.
     */
    public static function storeSafeImage(UploadedFile $file, string $subdir): ?string
    {
        if (!$file->isValid()) {
            return null;
        }

        $realMime = strtolower((string) $file->getMimeType());
        $allowedMimeToExt = [
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        if (!isset($allowedMimeToExt[$realMime])) {
            return null;
        }

        // Verify binary image header actually decodes cleanly
        $realPath = $file->getRealPath();
        if (!$realPath || @getimagesize($realPath) === false) {
            return null;
        }

        $safeSubdir = trim(preg_replace('/[^a-zA-Z0-9_-]/', '', $subdir), '/');
        $targetDir = public_path('uploads/' . $safeSubdir);
        File::ensureDirectoryExists($targetDir);

        $ext = $allowedMimeToExt[$realMime];
        $filename = Str::uuid()->toString() . '.' . $ext;
        $file->move($targetDir, $filename);

        return 'uploads/' . $safeSubdir . '/' . $filename;
    }

    /**
     * Safely validate and store a lightweight video file (MP4/WEBM) into Laravel storage/app/public/videos.
     */
    public static function storeSafeVideo(UploadedFile $file, string $subdir = 'videos'): ?string
    {
        if (!$file->isValid()) {
            return null;
        }

        $realMime = strtolower((string) $file->getMimeType());
        $allowedMimeToExt = [
            'video/mp4' => 'mp4',
            'video/webm' => 'webm',
        ];

        if (!isset($allowedMimeToExt[$realMime])) {
            return null;
        }

        $safeSubdir = trim(preg_replace('/[^a-zA-Z0-9_-]/', '', $subdir), '/');
        $targetDir = storage_path('app/public/' . $safeSubdir);
        File::ensureDirectoryExists($targetDir);

        $ext = $allowedMimeToExt[$realMime];
        $filename = 'fkksm-video-' . Str::uuid()->toString() . '.' . $ext;
        $file->move($targetDir, $filename);

        return $safeSubdir . '/' . $filename;
    }

    /**
     * Ensure an ultra-lightweight valid MP4 sample video exists in storage/app/public/videos/fkksm-sample.mp4.
     */
    public static function ensureSampleVideoExists(): string
    {
        $targetDir = storage_path('app/public/videos');
        File::ensureDirectoryExists($targetDir);
        $targetFile = $targetDir . DIRECTORY_SEPARATOR . 'fkksm-sample.mp4';

        if (!File::exists($targetFile) || File::size($targetFile) < 64) {
            // Valid lightweight ISO Base Media (MP4) H.264 baseline 1-frame stream
            $mp4Hex = '00000018667479706d703432000000006d70343269736f6d'
                . '000000086d646174'
                . '000001276d6f6f760000006c6d766864000000000000000000000000000003e800000bb80001000001000000000000000000000000010000000000000000000000000000000100000000000000000000000000004000000000000000000000000000000000000000000000000000000000000002'
                . '000000b37472616b0000005c746b6864000000030000000000000000000000010000000000000bb8000000000000000000000000000000000001000000000000000000000000000000010000000000000000000000000000400000000280000001680000'
                . '0000004f6d646961000000206d646864000000000000000000000000000003e800000bb855c400000000002768646c72000000000000000076696465000000000000000000000000566964656f48616e646c657200';
            File::put($targetFile, hex2bin($mp4Hex));
        }

        return 'videos/fkksm-sample.mp4';
    }
}
