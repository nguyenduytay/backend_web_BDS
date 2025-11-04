<?php

namespace App\Http\Validations;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ContactValidation extends Validation
{
    public function searchValidation($request)
    {
        return Validator::make($request->all(), [
            'id' => 'nullable|integer|exists:contacts,id',
        ], [
            'id.integer' => 'ID phải là một số nguyên',
            'id.exists' => 'ID không tồn tại',
        ]);
    }
    public function createValidation($request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'user_id' => [
                'nullable',
                Rule::exists('users', 'id'), // Kiểm tra user_id có tồn tại trong bảng users
                Rule::unique('contacts', 'user_id')->ignore($request->user_id), // Kiểm tra chưa gán cho contact khác
            ],
        ], [
            'name.required' => 'Tên là bắt buộc',
            'name.string' => 'Tên phải là một chuỗi',
            'name.max' => 'Tên không được vượt quá 255 ký tự',
            'phone.required' => 'Số điện thoại là bắt buộc',
            'phone.string' => 'Số điện thoại phải là một chuỗi',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự',
            'email.email' => 'Email không hợp lệ',
            'email.max' => 'Email không được vượt quá 255 ký tự',
            'user_id.exists' => 'Người dùng không tồn tại',
            'user_id.unique' => 'Người dùng này đã được gán cho liên hệ khác',
        ]);
    }
    public function updateValidation($request, $id)
{
    $contact = \App\Models\Contact::find($id);

    return Validator::make(array_merge($request->all(), ['id' => $id]), [
        'id' => 'required|integer|exists:contacts,id',
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'email' => 'nullable|email|max:255',
        'user_id' => [
            'required',
            Rule::exists('users', 'id'),
            function ($attribute, $value, $fail) use ($contact) {
                if ($contact && $contact->user_id != $value) {
                    $fail('User ID không được phép thay đổi');
                }
            },
        ],
    ], [
        'id.required' => 'ID là bắt buộc',
        'id.integer' => 'ID phải là một số nguyên',
        'id.exists' => 'Liên hệ không tồn tại',
        'name.required' => 'Tên là bắt buộc',
        'name.string' => 'Tên phải là một chuỗi',
        'name.max' => 'Tên không được vượt quá 255 ký tự',
        'phone.required' => 'Số điện thoại là bắt buộc',
        'phone.string' => 'Số điện thoại phải là một chuỗi',
        'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự',
        'email.email' => 'Email không hợp lệ',
        'email.max' => 'Email không được vượt quá 255 ký tự',
        'user_id.required' => 'Người dùng là bắt buộc',
        'user_id.exists' => 'Người dùng không tồn tại',
    ]);
}

}
