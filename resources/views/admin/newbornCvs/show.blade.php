@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.newbornCv.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.newborn-cvs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.newbornCv.fields.id') }}
                        </th>
                        <td>
                            {{ $newbornCv->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.newbornCv.fields.trial_code') }}
                        </th>
                        <td>
                            {{ $newbornCv->trial_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.newbornCv.fields.data_type') }}
                        </th>
                        <td>
                            {{ App\Models\NewbornCv::DATA_TYPE_SELECT[$newbornCv->data_type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.newbornCv.fields.file') }}
                        </th>
                        <td>
                            @if($newbornCv->file)
                                <a href="{{ $newbornCv->file->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.newbornCv.fields.notes') }}
                        </th>
                        <td>
                            {{ $newbornCv->notes }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.newborn-cvs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection