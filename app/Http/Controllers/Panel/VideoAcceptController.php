<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use Illuminate\Http\Request;

class VideoAcceptController extends Controller
{
    public function __invoke(Request $request) {
        $uploads = Upload::forAdmin('awaiting')->get();
        return view('panel.videos', compact('uploads'));
    }

    public function accept($uuid) {
        $upload = Upload::where('uuid', $uuid)->firstOrFail();
        $upload->status = 'approved';
        $upload->save();

        return redirect()->back()->with('success', 'Nagranie zaakceptowano');
    }

    public function reject($uuid) {
        $upload = Upload::where('uuid', $uuid)->firstOrFail();
        $upload->status = 'rejected';
        $upload->save();

        return redirect()->back()->with('success', 'Nagranie odrzucono');
    }
}
