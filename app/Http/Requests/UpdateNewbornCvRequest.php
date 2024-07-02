<?php

namespace App\Http\Requests;

use App\Models\NewbornCv;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateNewbornCvRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('newborn_cv_edit');
    }

    public function rules()
    {
        return [
            'trial_code' => [
                'string',
                'required',
            ],
            'data_type' => [
                'required',
            ],
            'file' => [
                'required',
            ],
            'notes' => [
                'string',
                'nullable',
            ],
        ];
    }
}
