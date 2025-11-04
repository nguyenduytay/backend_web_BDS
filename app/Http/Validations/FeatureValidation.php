<?php

namespace App\Http\Validations;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeatureValidation extends Validation
{
    public function checkIdValidate($id)
    {
        return Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:features,id',
        ], [
            'id.required' => 'ID là bắt buộc',
            'id.integer' => 'ID phải là một số nguyên',
            'id.exists' => 'ID không tồn tại',
        ]);
    }
    public function createValidate(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:features,name',
            'icon' => 'required|string|',
        ], [
            'name.required' => 'Tên là bắt buộc',
            'name.string'   => 'Tên phải là một chuỗi',
            'name.max'      => 'Tên không được vượt quá 100 ký tự',
            'name.unique'   => 'Tên này đã tồn tại',
            'icon.required' => 'Icon là bắt buộc',
        ]);
    }
   public function updateValidate(Request $request, $id){
    // Lấy bản ghi hiện tại
    $feature = \App\Models\Feature::find($id);

    return Validator::make(array_merge($request->all(),['id' => $id]), [
        'id' => [
            'required',
            'integer',
            function($attribute, $value, $fail) use ($feature) {
                if ($feature && $value != $feature->id) {
                    $fail('ID không được thay đổi.');
                }
            }
        ],
        'name' => [
            'required',
            'string',
            'max:100',
            function($attribute, $value, $fail) use ($feature) {
                if ($feature && $value != $feature->name) {
                    $fail('Tên không được thay đổi.');
                }
            }
        ],
        'icon' => 'required|string',
    ], [
        'id.required' => 'ID là bắt buộc',
        'id.integer'  => 'ID phải là một số nguyên',
        'name.required' => 'Tên là bắt buộc',
        'name.string'   => 'Tên phải là một chuỗi',
        'name.max'      => 'Tên không được vượt quá 100 ký tự',
        'icon.required' => 'Icon là bắt buộc',
        'icon.string'   => 'Icon phải là một chuỗi',
    ]);
}

}
