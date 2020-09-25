<?php

namespace Dcat\Admin\Extension\Media\Http\Controllers;

use Dcat\Admin\Layout\Content;
use Dcat\Admin\Extension\Media\Media;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index(Content $content, Request $request)
    {

        $path = $request->get('path', '/');
        $view = $request->get('view', 'index');
        $manager = new Media($path);

        return $content
            ->title('Media')
            ->body(view("media::$view", [
                'list'   => $manager->ls(),
                'nav'    => $manager->navigation(),
                'url'    => $manager->urls(),
            ]));
    }

    public function download(Request $request)
    {
        $file = $request->get('file');

        $manager = new Media($file);

        return $manager->download();
    }

    public function upload(Request $request)
    {
        $files = $request->file('files');
        $dir = $request->get('dir', '/');

        $manager = new Media($dir);

        try {
            if ($manager->upload($files)) {
                admin_toastr(trans('admin.upload_succeeded'));
            }
        } catch (\Exception $e) {
            admin_toastr($e->getMessage(), 'error');
        }

        return back();
    }

    public function delete(Request $request)
    {
        $files = $request->get('files');

        $manager = new Media();

        try {
            if ($manager->delete($files)) {
                return response()->json([
                    'status'  => true,
                    'message' => trans('admin.delete_succeeded'),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => true,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function move(Request $request)
    {
        $path = $request->get('path');
        $new = $request->get('new');

        $manager = new Media($path);

        try {
            if ($manager->move($new)) {
                return response()->json([
                    'status'  => true,
                    'message' => trans('admin.move_succeeded'),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => true,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function newFolder(Request $request)
    {
        $dir = $request->get('dir');
        $name = $request->get('name');

        $manager = new Media($dir);

        try {
            if ($manager->newFolder($name)) {
                return response()->json([
                    'status'  => true,
                    'message' => trans('admin.move_succeeded'),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => true,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
