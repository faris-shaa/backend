<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use App\Models\Module;
use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use ZipArchive;
use ZanySoft\Zip\Facades\Zip;


class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modules = Module::all();
        $admin = Setting::first();
        return view('admin.module.index', compact('modules', 'admin'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('module_add'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $request->validate([
            'upload_file' => 'bail|required',
        ]);
        $is_valid = Zip::check($request->upload_file);
        if ($is_valid) {
            $module  = Module::find($request->module_id);
            $modul_name = $module->module;
            if ($modul_name == "Seatmap" || $modul_name == "BankPayout") {
                if ($request->hasFile('upload_file')) {
                    $zipFile = $request->file('upload_file');
                    $tempFilePath = tempnam(sys_get_temp_dir(), 'zip_temp_');

                    try {
                        if (!$zipFile->storeAs('/', basename($tempFilePath), 'local')) {
                            throw new \Exception('Failed to store ZIP file.');
                        }

                        $zip = new \ZipArchive();
                        if ($zip->open($tempFilePath) !== true) {
                            throw new \Exception('Invalid ZIP file.');
                        }

                        $fileName = $zipFile->getClientOriginalName();
                        $destinationPath = public_path('modules') . '/' . $fileName;

                        if (!$zipFile->move(public_path('modules'), $fileName)) {
                            throw new \Exception('Failed to move ZIP file.');
                        }

                        $unzipPath = base_path('Modules/');
                        if (!file_exists($unzipPath)) {
                            mkdir($unzipPath, 0777, true);
                        }

                        if ($zip->open($destinationPath) === true) {
                            $zip->extractTo($unzipPath);
                            $zip->close();
                        } else {
                            throw new \Exception('Failed to extract ZIP file.');
                        }

                        Artisan::call('module:use', ['module' => $modul_name]);
                        Artisan::call('module:migrate', ['module' => $modul_name]);
                        Artisan::call('module:publish', ['module' => $modul_name]);

                        $module = Module::where('module', $modul_name)->first();
                        $module->is_install = 1;
                        $module->is_enable = 1;
                        $module->save();

                        if (file_exists($tempFilePath)) {
                            unlink($tempFilePath);
                        }
                        return redirect()->route('module.index')->with('status', __('Module has been added successfully.'));
                    } catch (\Exception $e) {
                        return redirect()->back()->withErrors(['error' => __($e)]);
                    }
                }
            }
        }else{
            return redirect()->back()->withErrors(['error' => __('Invalid file format')]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $module = Module::find($id);
        return view('admin.module.install', compact('module'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        abort_if(Gate::denies(['module_enable', 'module_disable']), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $module = Module::find($id);
        if ($module) {
            $currentIsEnable = $module->is_enable;
            $module->update(['is_enable' => !$currentIsEnable]);
            if (!$currentIsEnable) {
                Session::flash('status', 'Module enabled successfully');
                return response()->json(['success' => true]);
            } else {
                Session::flash('status', 'Module disabled successfully');
                return response()->json(['success' => true]);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Module not found']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('module_remove'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            \DB::statement('SET FOREIGN_KEY_CHECKS=0');
            $module = Module::findOrFail($id);
            $module_name = $module->module;
            $extractedDirectory =  base_path('/Modules' . '/' . $module_name);
            $filePath = public_path('modules/' . $module_name . '.zip');
            if (File::isDirectory($extractedDirectory)) {
                Artisan::call('module:migrate-rollback', ['module' => $module_name]);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
                File::deleteDirectory($extractedDirectory);
                Module::where('id', $id)->update(['is_install' => 0, 'is_enable' => 0]);
            } else {
                return response()->json(['success' => false, 'message' => 'File not found']);
            }
            \DB::statement('SET FOREIGN_KEY_CHECKS=1');
            Session::flash('status', 'Module removed successfully');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Not found']);
        }
    }

    private function checkZip($zipFile)
    {
        $zip = new ZipArchive;
        $zipFilePath = $zipFile->getPathname();
        if ($zip->open($zipFilePath) === TRUE) {
            $zip->close();
            return true;
        } else {
            return false;
        }
    }
}
