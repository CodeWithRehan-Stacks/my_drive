<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;


class DriveController extends Controller
{

    public function index()
    {
        $files = File::orderBy('name')->get();
        $folders = Folder::orderBy('name')->get();

        return response()->json([
            'files' => $files,
            'folders' => $folders,
        ]);
    }

    public function upload(Request $request)
    {

        $user = $request->user();
        $file = $request->file('file');

        $fileName = $request->name ?? $file->getClientOriginalName();
        $uniqueName = uniqid() . '_' . $fileName;
        $filePath = $file->storeAs("user_files/{$user->id}", $uniqueName, 'public');

        $fileRecord = \App\Models\File::create([
            'user_id' => $user->id,
            'folder_id' => $request->folder_id ?? null,
            'name' => $fileName,
            'path' => $filePath,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        return response()->json([
            'message' => 'File uploaded successfully!',
            'file' => $fileRecord
        ], 201);
    }

    public function destroyFile(Request $request)
    {
        $request->validate([
            'file_id' => 'required|integer|exists:files,id',
        ]);

        try {
            $user = $request->user();

            // Find file that belongs to this user
            $file = File::where('id', $request->file_id)
                ->where('user_id', $user->id)
                ->first();

            if (!$file) {
                return response()->json([
                    'error' => 'File not found or unauthorized'
                ], 404);
            }

            // Delete file from storage (public disk)
            if (Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
            }

            // Delete record from database
            $file->delete();

            return response()->json([
                'message' => 'File deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Delete failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createFolder(Request $request)
    {
        try {
            $folder = \App\Models\Folder::create([
                'name' => $request['name'],
                'parent_id' => $request['parent_id'] ?? null,
                'user_id' => $request['user_id'],
                'path' => $request['path'] ?? null,
            ]);

            return response()->json([
                'message' => 'Folder created successfully!',
                'folder' => $folder
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create folder: ' . $e->getMessage()
            ], 500);
        }
    }


    public function destroyFolder(Request $request)
    {
        $request->validate([
            'folder_id' => 'required|integer|exists:folders,id',
        ]);

        $user = $request->user();

        $folder = \App\Models\Folder::where('id', $request->folder_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$folder) {
            return response()->json([
                'error' => 'Folder not found or unauthorized'
            ], 404);
        }

        $folder->delete();

        return response()->json([
            'message' => 'Folder deleted successfully'
        ]);
    }
}
