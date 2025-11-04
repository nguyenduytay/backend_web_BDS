<?php

namespace App\Http\Validations;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LocationValidation extends Validation
{
    public function validateLocationCreate(Request $request)
    {
        return Validator::make($request->all(), [
            'city' => 'required|string|max:100',
            'district' => [
                'required',
                'string',
                'max:100',
                Rule::unique('locations', 'district')
                    ->where(function ($query) use ($request) {
                        $query->whereRaw('LOWER(city) = ?', [strtolower($request->city)]);
                    })
            ],
        ], [
            'city.required' => 'Thành phố là bắt buộc',
            'city.string' => 'Thành phố phải là một chuỗi',
            'city.max' => 'Thành phố không được vượt quá 100 ký tự',
            'district.required' => 'Quận/Huyện là bắt buộc',
            'district.string' => 'Quận/Huyện phải là một chuỗi',
            'district.max' => 'Quận/Huyện không được vượt quá 100 ký tự',
            'district.unique' => 'Quận/Huyện đã tồn tại trong thành phố này',
        ]);
    }
    public function validateLocationUpdate(Request $request)
    {
        return Validator::make($request->all(), [
            'id' => [
                'required',
                'integer',
                Rule::exists('locations', 'id')
                    ->where(function ($query) use ($request) {
                        $query->whereRaw('LOWER(city) = ?', [strtolower($request->city)]);
                    }),
            ],
            'city' => 'required|string|max:100',
            'district' => [
                'required',
                'string',
                'max:100'
            ],
        ], [
            'id.required' => 'ID là bắt buộc',
            'id.integer' => 'ID phải là một số nguyên',
            'id.exists' => 'ID không tồn tại',
            'city.required' => 'Thành phố là bắt buộc',
            'city.string' => 'Thành phố phải là một chuỗi',
            'city.max' => 'Thành phố không được vượt quá 100 ký tự',
            'district.required' => 'Quận/Huyện là bắt buộc',
            'district.string' => 'Quận/Huyện phải là một chuỗi',
            'district.max' => 'Quận/Huyện không được vượt quá 100 ký tự',
        ]);
    }
    public function validateLocationDelete(Request $request)
    {
        return Validator::make($request->all(), [
            'id' => [
                'required',
                'integer',
                Rule::exists('locations', 'id')
            ],
        ], [
            'id.required' => 'ID là bắt buộc',
            'id.integer' => 'ID phải là một số nguyên',
            'id.exists' => 'ID không tồn tại',
        ]);
    }
}
