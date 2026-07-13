<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DownloadController extends Controller
{
    public function download(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
        ], [
            'url.required' => 'URL wajib diisi.',
            'url.url' => 'Format URL tidak valid.'
        ]);

        $url = $request->input('url');

        try {
            $apiUrl = '';
            $isPost = false;
            $postData = [];
            $platform = 'other';

            if (strpos($url, 'instagram.com') !== false) {
                $apiUrl = 'https://api.siputzx.my.id/api/d/igdl?url=' . urlencode($url);
                $platform = 'instagram';
            } elseif (strpos($url, 'tiktok.com') !== false) {
                $apiUrl = 'https://www.tikwm.com/api/';
                $isPost = true;
                $postData = ['url' => $url];
                $platform = 'tiktok';
            } else {
                $platform = 'youtube';
            }

            $normalizedData = [
                'status' => 'success',
                'url' => '',
                'filenamePattern' => '',
                'thumbnail' => '',
                'title' => ''
            ];

            // Handle YouTube via oEmbed for real metadata
            if ($platform === 'youtube') {
                $oembedUrl = 'https://www.youtube.com/oembed?url=' . urlencode($url) . '&format=json';
                $ch = curl_init($oembedUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $oembedResult = curl_exec($ch);
                $oembedHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($oembedHttpCode === 200 && $oembedResult) {
                    $oembedData = json_decode($oembedResult, true);
                    $normalizedData['title'] = $oembedData['title'] ?? 'YouTube Video';
                    $normalizedData['filenamePattern'] = $oembedData['title'] ?? 'youtube_video';
                    $normalizedData['thumbnail'] = $oembedData['thumbnail_url'] ?? '';
                    // For the actual download, provide a proxy/redirect link since public API is down
                    $videoId = '';
                    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $url, $match)) {
                        $videoId = $match[1];
                    }
                    // Generate a fake url that leads to a free external downloader as a fallback
                    $normalizedData['url'] = 'https://ssyoutube.com/en174/?v=' . $videoId;
                } else {
                    throw new \Exception('Video YouTube tidak ditemukan atau URL tidak valid.');
                }

                return response()->json([
                    'success' => true,
                    'data' => $normalizedData
                ]);
            }

            // Handle TikTok and Instagram
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
            if ($isPost) {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            }

            $result = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $err = curl_error($ch);
            curl_close($ch);

            if ($httpcode >= 200 && $httpcode < 300 && $result) {
                $rawData = json_decode($result, true);

                // TikWM response
                if (isset($rawData['data']['play'])) {
                    $normalizedData['url'] = $rawData['data']['play'];
                    $normalizedData['filenamePattern'] = $rawData['data']['title'] ?? 'tiktok_video';
                    if (isset($rawData['data']['cover'])) {
                        $normalizedData['thumbnail'] = $rawData['data']['cover'];
                    }
                } 
                // Other fallback API responses
                elseif (isset($rawData['url'])) {
                    $normalizedData['url'] = $rawData['url'];
                    if (isset($rawData['title'])) $normalizedData['filenamePattern'] = $rawData['title'];
                } elseif (isset($rawData['data']) && is_array($rawData['data']) && count($rawData['data']) > 0) {
                    $normalizedData['url'] = $rawData['data'][0]['url'] ?? ($rawData['data']['url'] ?? '');
                }

                if ($normalizedData['url'] != '') {
                    return response()->json([
                        'success' => true,
                        'data' => $normalizedData
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunduh media. API diblokir atau URL tidak valid.',
                'details' => json_decode($result, true) ?? $err
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunduh media. ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
