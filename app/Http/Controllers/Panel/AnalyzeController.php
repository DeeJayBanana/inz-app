<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;

class AnalyzeController extends Controller
{
    public function analyze($uuid) {
        $upload = Upload::where('uuid', $uuid)->firstOrFail();

        $videoPath = "/var/www/storage/app/public/video/{$uuid}/original/{$uuid}.{$upload->extension}";

        try {

            $response = Http::timeout(5)->post('http://python_engine:5000/analyze', [
                'id'   => $uuid,
                'path' => $videoPath
            ]);

            if ($response->successful()) {
                $upload->update(['status' => 'processing']);
                return back()->with('success', 'Analiza AI wystartowała w kontenerze Pythona.');
            } else {
                return back()->with('error', 'Python API odpowiedział błędem: ' . $response->status());
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Nie udało się połączyć z kontenerem AI: ' . $e->getMessage());
        }
    }
}
