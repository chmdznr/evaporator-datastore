@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.newbornData.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.newborn-datas.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.newbornData.fields.id') }}
                        </th>
                        <td>
                            {{ $newbornData->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.newbornData.fields.trial_code') }}
                        </th>
                        <td>
                            {{ $newbornData->trial_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.newbornData.fields.thermal') }}
                        </th>
                        <td>
                            {{ $newbornData->thermal }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.newbornData.fields.notes') }}
                        </th>
                        <td>
                            {!! $newbornData->notes !!}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.newborn-datas.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection