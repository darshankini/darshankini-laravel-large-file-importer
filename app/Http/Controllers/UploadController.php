<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use App\Jobs\ProcessImportJob;
use Exception;
use Illuminate\Support\Facades\Log;
use App\DataTables\EmployeeDataTable;
use App\Models\UploadModel;
use Yajra\DataTables\DataTables;

class UploadController extends Controller
{
    public $headers;

    public function __construct()
    {
        $this->headers = [];
    }

    public function list(){
        return view('dashboard');
    }

    public function getEmployeeList(Request $request)
    {
        if ($request->ajax()) {
            $data = UploadModel::query();
            return DataTables::of($data)
                ->make(true);
        }
    }

    public function uploadForm()
    {
        return view('uploadForm');
    }

    public function upload(Request $request)
    {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
        if (!$receiver->isUploaded()) {
            return [
                'done' => 'File not uploaded',
                'status' => false
            ];
        }

        $fileReceived = $receiver->receive();
        if ($fileReceived->isFinished()) {
            try {
                $file = $fileReceived->getFile();
                $extension = $file->getClientOriginalExtension();
                $fileName = str_replace('.'.$extension, '', $file->getClientOriginalName());
                $fileName .= '_' . md5(time()) . '.' . $extension;
                $path = Storage::disk(config('filesystems.default'))->put('public/csv', $file);
                $handle = fopen(storage_path('app/'.$path), 'r');
                ProcessImportJob::dispatch($path, $handle);
                unlink($file->getPathname());
                return [
                    'path' => asset('storage/' . $path),
                    'filename' => $fileName
                ];
            } catch(Exception $e) {
                Log::error('An error occurred: ' . $e->getMessage());
            }
        }

        $handler = $fileReceived->handler();
        return [
            'done' => $handler->getPercentageDone(),
            'status' => true
        ];
    }

}
