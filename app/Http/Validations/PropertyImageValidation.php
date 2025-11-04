<?php

namespace App\Http\Validations;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PropertyImageValidation extends Validation
{
    public function createValidation(Request $request, $propertyId)
    {
        return Validator::make(array_merge($request->all(), ['property_id' => $propertyId]), [
            'property_id' => 'required|exists:properties,id',
            'image_path'  => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_name'  => 'nullable|string|max:255',
            'is_primary'  => 'boolean',
            'sort_order'  => 'integer|min:0',
        ], [
            'property_id.required' => 'Thuộc tính (property_id) là bắt buộc',
            'property_id.exists'   => 'Thuộc tính không tồn tại trong hệ thống',
            'image_path.required'  => 'Ảnh là bắt buộc',
            'image_path.image'     => 'File phải là hình ảnh',
            'image_path.mimes'     => 'Ảnh chỉ được có định dạng jpeg, png, jpg, gif',
            'image_path.max'       => 'Ảnh không được vượt quá 2MB',
            'image_name.string'    => 'Tên ảnh phải là chuỗi',
            'image_name.max'       => 'Tên ảnh không được dài quá 255 ký tự',
            'is_primary.boolean'   => 'is_primary chỉ có thể true hoặc false',
            'sort_order.integer'   => 'sort_order phải là số nguyên',
            'sort_order.min'       => 'sort_order không được nhỏ hơn 0',
        ]);
    }

    public function updateValidation($request, $propertyId, $imageId)
    {
        return Validator::make(array_merge($request->all(), ['property_id' => $propertyId], ['id' => $imageId]), [
            'id' => 'required|exists:property_images,id',
            'property_id' => 'required|exists:properties,id',
            'image_path'  => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_name'  => 'nullable|string|max:255',
            'is_primary'  => 'boolean',
            'sort_order'  => 'integer|min:0',
        ], [
            'id.required' => 'ID là bắt buộc',
            'id.exists'   => 'ID không tồn tại trong hệ thống',
            'property_id.required' => 'Thuộc tính (property_id) là bắt buộc',
            'property_id.exists'   => 'Thuộc tính không tồn tại trong hệ thống',
            'image_path.required'  => 'Ảnh là bắt buộc',
            'image_path.image'     => 'File phải là hình ảnh',
            'image_path.mimes'     => 'Ảnh chỉ được có định dạng jpeg, png, jpg, gif',
            'image_path.max'       => 'Ảnh không được vượt quá 2MB',
            'image_name.string'    => 'Tên ảnh phải là chuỗi',
            'image_name.max'       => 'Tên ảnh không được dài quá 255 ký tự',
            'is_primary.boolean'   => 'is_primary chỉ có thể true hoặc false',
            'sort_order.integer'   => 'sort_order phải là số nguyên',
            'sort_order.min'       => 'sort_order không được nhỏ hơn 0',
        ]);
    }
}
