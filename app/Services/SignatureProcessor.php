<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class SignatureProcessor
{
    /**
     * Remove the white/near-white background from a signature image using GD.
     *
     * Each pixel's luminance is mapped to alpha: white (255) becomes fully
     * transparent, black (0) stays fully opaque. The result is saved as PNG
     * so the transparency is preserved. The original file is deleted if it
     * had a different extension.
     *
     * Returns the storage-relative path of the processed PNG.
     */
    public static function removeBackground(string $storagePath): string
    {
        $fullPath = Storage::disk('public')->path($storagePath);
        $src      = self::loadGdImage($fullPath);

        if (!$src) {
            return $storagePath; // GD couldn't open it — keep original
        }

        $w   = imagesx($src);
        $h   = imagesy($src);
        $out = imagecreatetruecolor($w, $h);

        imagealphablending($out, false);
        imagesavealpha($out, true);

        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgba  = imagecolorat($src, $x, $y);
                $r     = ($rgba >> 16) & 0xFF;
                $g     = ($rgba >> 8)  & 0xFF;
                $b     =  $rgba        & 0xFF;

                // Perceptual luminance: white=255 → alpha=127 (transparent)
                //                      black=0   → alpha=0   (opaque)
                $luma  = (int) round(0.299 * $r + 0.587 * $g + 0.114 * $b);
                $alpha = (int) round($luma / 255 * 127);

                imagesetpixel($out, $x, $y, imagecolorallocatealpha($out, $r, $g, $b, $alpha));
            }
        }

        imagedestroy($src);

        // Always save as PNG to preserve transparency
        $pngRelPath  = preg_replace('/\.[^.]+$/', '.png', $storagePath);
        $pngFullPath = Storage::disk('public')->path($pngRelPath);

        imagepng($out, $pngFullPath);
        imagedestroy($out);

        // Remove the original if extension changed (e.g. .jpg → .png)
        if ($pngRelPath !== $storagePath) {
            Storage::disk('public')->delete($storagePath);
        }

        return $pngRelPath;
    }

    private static function loadGdImage(string $path): \GdImage|false|null
    {
        if (!file_exists($path)) {
            return null;
        }

        return match (mime_content_type($path)) {
            'image/jpeg'          => @imagecreatefromjpeg($path),
            'image/png'           => @imagecreatefrompng($path),
            'image/gif'           => @imagecreatefromgif($path),
            'image/webp'          => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : null,
            'image/bmp',
            'image/x-bmp',
            'image/x-ms-bmp'      => function_exists('imagecreatefrombmp') ? @imagecreatefrombmp($path) : null,
            default               => null,
        };
    }
}
