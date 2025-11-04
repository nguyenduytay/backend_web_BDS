<?php

namespace App\Http\Validations;

use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Validation
{
    public function checkValidation($validator)
    {
        if ($validator->fails()) {
            throw new BusinessException($validator->errors()->first());
        }
    }
    public function checkIdValidation($id, $table, $column = 'id')
    {
        return Validator::make(['id' => $id], [
            'id' => [
                'required',
                'integer',
                Rule::exists($table, $column),
            ],
        ], [
            'id.required' => 'ID là bắt buộc',
            'id.integer'  => 'ID phải là số nguyên',
            'id.exists'   => 'ID không tồn tại trong dữ liệu',
        ]);
    }
}
