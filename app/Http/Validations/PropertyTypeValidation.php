<?php

namespace App\Http\Validations;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PropertyTypeValidation extends Validation
{
    public function validatePropertyType(Request $request)
    {
        return Validator::make($request->all(), [
            'type' => 'required|string|max:255',
        ], [
            'type.required' => 'Loại bất động sản là bắt buộc',
            'type.string' => 'Loại bất động sản phải là một chuỗi',
            'type.max' => 'Loại bất động sản không được vượt quá 255 ký tự',
        ]);
    }
    public function validatePropertyTypeCreate(Request $request)
    {
        return Validator::make($request->all(), [
            'type' => 'required|string|unique:property_types,type',
            'name' => 'required|string',
        ], [
            'type.required' => 'Loại bất động sản là bắt buộc',
            'type.string' => 'Loại bất động sản phải là một chuỗi',
            'type.unique' => 'Loại bất động sản đã tồn tại',
            'name.required' => 'Tên loại bất động sản là bắt buộc',
            'name.string' => 'Tên loại bất động sản phải là một chuỗi',
        ]);
    }
    public function validatePropertyTypeUpdate(Request $request, $id)
    {
        $propertyType = \App\Models\PropertyType::find($id);

        return Validator::make(array_merge($request->all(), [
            'id' => $id,
        ]), [
            'id' => [
                'required',
                'integer',
                Rule::exists('property_types', 'id'),
            ],
            'type' => [
                'required',
                'string',
                'max:50',
                function ($attribute, $value, $fail) use ($propertyType) {
                    if ($propertyType && $value !== $propertyType->type) {
                        $fail('Loại bất động sản bắt buộc giữ nguyên.');
                    }
                },
            ],
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:100',
            ],
        ], [
            'id.required' => 'ID là bắt buộc',
            'id.integer' => 'ID phải là số nguyên',
            'id.exists' => 'ID không tồn tại trong dữ liệu',

            'type.required' => 'Loại bất động sản là bắt buộc',
            'type.string' => 'Loại bất động sản phải là một chuỗi',
            'type.max' => 'Loại bất động sản không được vượt quá 50 ký tự',

            'name.required' => 'Tên loại bất động sản là bắt buộc',
            'name.string' => 'Tên loại bất động sản phải là một chuỗi',
            'name.max' => 'Tên loại bất động sản không được vượt quá 100 ký tự',
        ]);
    }
}
