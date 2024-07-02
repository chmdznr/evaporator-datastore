<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreNewbornDataRequest;
use App\Http\Requests\UpdateNewbornDataRequest;
use App\Http\Resources\Admin\NewbornDataResource;
use App\Models\NewbornData;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NewbornDataApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('newborn_data_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NewbornDataResource(NewbornData::all());
    }

    public function store(StoreNewbornDataRequest $request)
    {
        $newbornData = NewbornData::create($request->all());

        return (new NewbornDataResource($newbornData))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(NewbornData $newbornData)
    {
        abort_if(Gate::denies('newborn_data_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NewbornDataResource($newbornData);
    }

    public function update(UpdateNewbornDataRequest $request, NewbornData $newbornData)
    {
        $newbornData->update($request->all());

        return (new NewbornDataResource($newbornData))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(NewbornData $newbornData)
    {
        abort_if(Gate::denies('newborn_data_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $newbornData->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
