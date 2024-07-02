<?php

namespace App\Http\Requests;

use App\Models\NewbornData;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyNewbornDataRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('newborn_data_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:newborn_datas,id',
        ];
    }
}
