<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyNewbornDataRequest;
use App\Http\Requests\StoreNewbornDataRequest;
use App\Http\Requests\UpdateNewbornDataRequest;
use App\Models\NewbornData;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class NewbornDataController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('newborn_data_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = NewbornData::query()->select(sprintf('%s.*', (new NewbornData)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'newborn_data_show';
                $editGate      = 'newborn_data_edit';
                $deleteGate    = 'newborn_data_delete';
                $crudRoutePart = 'newborn-datas';

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
            $table->editColumn('thermal', function ($row) {
                return $row->thermal ? $row->thermal : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.newbornDatas.index');
    }

    public function create()
    {
        abort_if(Gate::denies('newborn_data_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.newbornDatas.create');
    }

    public function store(StoreNewbornDataRequest $request)
    {
        $newbornData = NewbornData::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $newbornData->id]);
        }

        return redirect()->route('admin.newborn-datas.index');
    }

    public function edit(NewbornData $newbornData)
    {
        abort_if(Gate::denies('newborn_data_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.newbornDatas.edit', compact('newbornData'));
    }

    public function update(UpdateNewbornDataRequest $request, NewbornData $newbornData)
    {
        $newbornData->update($request->all());

        return redirect()->route('admin.newborn-datas.index');
    }

    public function show(NewbornData $newbornData)
    {
        abort_if(Gate::denies('newborn_data_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.newbornDatas.show', compact('newbornData'));
    }

    public function destroy(NewbornData $newbornData)
    {
        abort_if(Gate::denies('newborn_data_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $newbornData->delete();

        return back();
    }

    public function massDestroy(MassDestroyNewbornDataRequest $request)
    {
        $newbornDatas = NewbornData::find(request('ids'));

        foreach ($newbornDatas as $newbornData) {
            $newbornData->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('newborn_data_create') && Gate::denies('newborn_data_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new NewbornData();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
