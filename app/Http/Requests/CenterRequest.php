<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CenterRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->role === 'admin';
    }

    public function rules()
    {
        $center = $this->route('center');
        $centerId = $center ? $center->id : null;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('centers')->where(function ($query) {
                    return $query->where('department_id', $this->input('department_id'));
                })->ignore($centerId),
            ],
            'department_id' => 'required|exists:departments,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Le nom du centre est requis.',
            'name.unique' => 'Un centre avec ce nom existe déjà dans ce département.',
            'department_id.required' => 'Le département est requis.',
            'department_id.exists' => 'Le département sélectionné est invalide.',
        ];
    }
}
