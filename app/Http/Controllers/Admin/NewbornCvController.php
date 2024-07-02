<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyNewbornCvRequest;
use App\Http\Requests\StoreNewbornCvRequest;
use App\Http\Requests\UpdateNewbornCvRequest;
use App\Models\NewbornCv;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class NewbornCvController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('newborn_cv_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = NewbornCv::query()->select(sprintf('%s.*', (new NewbornCv)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'newborn_cv_show';
                $editGate      = 'newborn_cv_edit';
                $deleteGate    = 'newborn_cv_delete';
                $crudRoutePart = 'newborn-cvs';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('trial_code', function ($row) {
                return $row->trial_code ? $row->trial_code : '';
            });
            $table->editColumn('data_type', function ($row) {
                return $row->data_type ? NewbornCv::DATA_TYPE_SELECT[$row->data_type] : '';
            });
            $table->editColumn('file', function ($row) {
                return $row->file ? '<a href="' . $row->file->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });
            $table->editColumn('notes', function ($row) {
                return $row->notes ? $row->notes : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'file']);

            return $table->make(true);
        }

        return view('admin.newbornCvs.index');
    }

    public function create()
    {
        abort_if(Gate::denies('newborn_cv_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.newbornCvs.create');
    }

    public function store(StoreNewbornCvRequest $request)
    {
        $newbornCv = NewbornCv::create($request->all());

        if ($request->input('file', false)) {
            $newbornCv->addMedia(storage_path('tmp/uploads/' . basename($request->input('file'))))->toMediaCollection('file');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $newbornCv->id]);
        }

        return redirect()->route('admin.newborn-cvs.index');
    }

    public function edit(NewbornCv $newbornCv)
    {
        abort_if(Gate::denies('newborn_cv_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.newbornCvs.edit', compact('newbornCv'));
    }

    public function update(UpdateNewbornCvRequest $request, NewbornCv $newbornCv)
    {
        $newbornCv->update($request->all());

        if ($request->input('file', false)) {
            if (! $newbornCv->file || $request->input('file') !== $newbornCv->file->file_name) {
                if ($newbornCv->file) {
                    $newbornCv->file->delete();
                }
                $newbornCv->addMedia(storage_path('tmp/uploads/' . basename($request->input('file'))))->toMediaCollection('file');
            }
        } elseif ($newbornCv->file) {
            $newbornCv->file->delete();
        }

        return redirect()->route('admin.newborn-cvs.index');
    }

    public function show(NewbornCv $newbornCv)
    {
        abort_if(Gate::denies('newborn_cv_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.newbornCvs.show', compact('newbornCv'));
    }

    public function destroy(NewbornCv $newbornCv)
    {
        abort_if(Gate::denies('newborn_cv_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $newbornCv->delete();

        return back();
    }

    public function massDestroy(MassDestroyNewbornCvRequest $request)
    {
        $newbornCvs = NewbornCv::find(request('ids'));

        foreach ($newbornCvs as $newbornCv) {
            $newbornCv->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('newborn_cv_create') && Gate::denies('newborn_cv_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new NewbornCv();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
