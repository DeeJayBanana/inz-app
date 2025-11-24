<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    public function index() {
        $uploads = Upload::forUser(Auth::id())->get();

        return view('panel.analyse', compact('uploads'));
    }

    public function store(Request $request) {

        $uuid = (string) Str::uuid();
        $fileName = $request->input('fileName');
        $baseName = pathinfo($fileName, PATHINFO_FILENAME);
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        $upload = Upload::create([
            'uuid' => $uuid,
            'user_id' => Auth::id(),
            'original_name' => $baseName,
            'extension' => $extension,
            'status' => 'uploading',
        ]);

        return response()->json(['uuid' => $uuid]);
    }

    public function uploadChunk(Request $request) {
        //        \Log::info('CHUNK', $request->all());

        $uploadId = $request->input('dzuuid');
        $chunkIndex = (int)$request->input('dzchunkindex');

        $tempPath = storage_path("app/chunks/{$uploadId}");
        if(!is_dir($tempPath)) {
            mkdir($tempPath, 0777, true);
        }

        $file = $request->file('file');
        $file->move($tempPath, "chunk_{$chunkIndex}");

        return response()->json(['status' => 'chunk_received']);
    }

    public function finalize(Request $request) {
        $uploadId = $request->input('dzuuid');
        $tempPath = storage_path("app/chunks/{$uploadId}");

        try {
            $finalDir = storage_path("app/public/video");
            if (!is_dir($finalDir)) {
                mkdir($finalDir, 0777, true);
            }

            $uploadFile = Upload::where('uuid', $uploadId)->firstOrFail();

            $finalPath = $finalDir . "/" . $uploadFile->uuid . "." . $uploadFile->extension;
            $totalChunks = (int)$request->input('dztotalchunkcount');

            $out = fopen($finalPath, "ab");
            for ($i = 0; $i < $totalChunks; $i++) {
                $part = $tempPath . "/chunk_{$i}";
                if (!file_exists($part)) {
                    return response()->json(['status' => "Chunk {$i} not found"], 400);
                }

                $in = fopen($part, "rb");
                stream_copy_to_stream($in, $out);
                fclose($in);
            }
            fclose($out);

            array_map('unlink', glob($tempPath . "/*"));
            rmdir($tempPath);

            $sizeGB = round(filesize($finalPath) / (1024 * 1024 * 1024), 2);
            $uploadFile->update([
                'size' => $sizeGB,
                'status' => 'completed'
            ]);

            return response()->json(['success' => 'done']);
        } catch (\Exception $e) {

            $uploadFile->update([
                'status' => 'failed'
            ]);

            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
