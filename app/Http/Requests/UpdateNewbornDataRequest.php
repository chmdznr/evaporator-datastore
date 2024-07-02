<?php

namespace App\Http\Requests;

use App\Models\NewbornData;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateNewbornDataRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('newborn_data_edit');
    }

    public function rules()
    {
        return [
            'trial_code' => [
                'string',
                'required',
            ],
            'thermal' => [
                'numeric',
                'required',
            ],
        ];
    }
}
