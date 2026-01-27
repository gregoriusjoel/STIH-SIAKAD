<?php

namespace App\Services;

class SimplePdfParser
{
    public static function parseText($filename)
    {
        $content = file_get_contents($filename);

        $text = '';

        // Find all objects
        // We look for "stream ... endstream"

        if (preg_match_all('/stream[\r\n]+(.*?)[\r\n]+endstream/s', $content, $matches)) {
            foreach ($matches[1] as $stream) {
                // Try to uncompress
                try {
                    $decoded = @gzuncompress($stream);
                    if ($decoded === false) {
                        $decoded = $stream;
                    }

                    $extracted = self::extractTextFromStream($decoded);
                    if (!empty($extracted)) {
                        \Illuminate\Support\Facades\Log::info("PDF Stream Found: " . substr($extracted, 0, 100));
                    }
                    $text .= $extracted;
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("PDF Stream Error: " . $e->getMessage());
                }
            }
        }

        return $text;
    }

    private static function extractTextFromStream($stream)
    {
        $streamText = '';

        // Extract text in (...) -> (Hello World)
        // Or [...] -> [ (Hello) -10 (World) ]

        // Combined approach: Just find ALL (text) anywhere in the stream

        if (preg_match_all('/\((.*?)\)/', $stream, $matches)) {
            foreach ($matches[1] as $match) {
                // Clean up escaped chars
                $clean = str_replace(['\\(', '\\)'], ['(', ')'], $match);

                // Keep single digits too (important for dates like '1', '9')
                if (strlen($clean) > 0) {
                    // Do NOT add space. Assume PDF has spaces if needed, or we rely on regex leniency.
                    $streamText .= $clean;
                }
            }
        }

        return $streamText;
    }
}
