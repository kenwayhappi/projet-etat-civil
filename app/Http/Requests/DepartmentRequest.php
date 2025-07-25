<?php

   namespace App\Http\Requests;

   use Illuminate\Foundation\Http\FormRequest;

   class DepartmentRequest extends FormRequest
   {
       public function authorize()
       {
           return auth()->user()->role === 'admin';
       }

       public function rules()
       {
           return [
               'name' => 'required|string|max:255',
               'region_id' => 'required|exists:regions,id',
           ];
       }
   }
