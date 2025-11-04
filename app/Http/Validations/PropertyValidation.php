<?php

namespace App\Http\Validations;

use Illuminate\Support\Facades\Validator;

class PropertyValidation extends Validation
{
    public function validateCreateAndUpdate($request)
    {
        return Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',

            'location_id' => 'required|exists:locations,id',
            'property_type_id' => 'required|exists:property_types,id',

            'status' => 'required|in:available,sold,rented,pending',
            'price' => 'required|numeric|min:0',
            'area' => 'required|numeric|min:0',

            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'floors' => 'nullable|integer|min:0',

            'address' => 'required|string|max:500',
            'postal_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'year_built' => 'nullable|integer|min:1800|max:' . date('Y'),

            'contact_id' => 'required|exists:contacts,id',
            'created_by' => 'nullable|exists:users,id',
            'updated_by' => 'nullable|exists:users,id',
        ], [
            // Title
            'title.required' => 'Tiêu đề không được để trống',
            'title.string' => 'Tiêu đề phải là chuỗi',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự',

            // Description
            'description.string' => 'Mô tả phải là chuỗi',

            // Location & Property type
            'location_id.required' => 'Vị trí bắt buộc phải chọn',
            'location_id.exists' => 'Vị trí không hợp lệ',
            'property_type_id.required' => 'Loại bất động sản bắt buộc phải chọn',
            'property_type_id.exists' => 'Loại bất động sản không hợp lệ',

            // Status
            'status.required' => 'Trạng thái bắt buộc phải chọn',
            'status.in' => 'Trạng thái không hợp lệ',

            // Price & Area
            'price.required' => 'Giá không được để trống',
            'price.numeric' => 'Giá phải là số',
            'price.min' => 'Giá không được nhỏ hơn 0',

            'area.required' => 'Diện tích không được để trống',
            'area.numeric' => 'Diện tích phải là số',
            'area.min' => 'Diện tích không được nhỏ hơn 0',

            // Rooms
            'bedrooms.integer' => 'Số phòng ngủ phải là số nguyên',
            'bedrooms.min' => 'Số phòng ngủ không được âm',

            'bathrooms.integer' => 'Số phòng tắm phải là số nguyên',
            'bathrooms.min' => 'Số phòng tắm không được âm',

            'floors.integer' => 'Số tầng phải là số nguyên',
            'floors.min' => 'Số tầng không được âm',

            // Address
            'address.required' => 'Địa chỉ không được để trống',
            'address.string' => 'Địa chỉ phải là chuỗi',
            'address.max' => 'Địa chỉ không được vượt quá 500 ký tự',

            // Postal code
            'postal_code.string' => 'Mã bưu điện phải là chuỗi',
            'postal_code.max' => 'Mã bưu điện không được vượt quá 20 ký tự',

            // Lat/Long
            'latitude.numeric' => 'Vĩ độ phải là số',
            'latitude.between' => 'Vĩ độ phải nằm trong khoảng -90 đến 90',

            'longitude.numeric' => 'Kinh độ phải là số',
            'longitude.between' => 'Kinh độ phải nằm trong khoảng -180 đến 180',

            // Year built
            'year_built.integer' => 'Năm xây dựng phải là số nguyên',
            'year_built.min' => 'Năm xây dựng không được nhỏ hơn 1800',
            'year_built.max' => 'Năm xây dựng không được vượt quá năm hiện tại',

            // Contacts & Users
            'contact_id.required' => 'Thông tin liên hệ bắt buộc phải chọn',
            'contact_id.exists' => 'Liên hệ không hợp lệ',

            'created_by.exists' => 'Người tạo không hợp lệ',
            'updated_by.exists' => 'Người cập nhật không hợp lệ',
        ]);
    }
}
