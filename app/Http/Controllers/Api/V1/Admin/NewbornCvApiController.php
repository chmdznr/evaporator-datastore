<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreNewbornCvRequest;
use App\Http\Requests\UpdateNewbornCvRequest;
use App\Http\Resources\Admin\NewbornCvResource;
use App\Models\NewbornCv;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NewbornCvApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('newborn_cv_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NewbornCvResource(NewbornCv::all());
    }

    public function store(StoreNewbornCvRequest $request)
    {
        $newbornCv = NewbornCv::create($request->all());

        if ($request->input('file', false)) {
            $newbornCv->addMedia(storage_path('tmp/uploads/' . basename($request->input('file'))))->toMediaCollection('file');
        }

        return (new NewbornCvResource($newbornCv))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(NewbornCv $newbornCv)
    {
        abort_if(Gate::denies('newborn_cv_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NewbornCvResource($newbornCv);
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

        return (new NewbornCvResource($newbornCv))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(NewbornCv $newbornCv)
    {
        abort_if(Gate::denies('newborn_cv_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $newbornCv->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
