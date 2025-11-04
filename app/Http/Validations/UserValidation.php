<?php

namespace App\Http\Validations;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserValidation
{
    public function validateAuthLoginRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
        ], [
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email phải là một địa chỉ email hợp lệ',
            'email.max' => 'Email không được vượt quá 255 ký tự',
            'password.required' => 'Mật khẩu là bắt buộc',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
        ]);
    }
    public static function validateCreate(Request $request)
    {
        return Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|string|same:password',
            'role'     => 'required|in:admin,user,agent'
        ], [
            'name.required' => 'Name là bắt buộc',
            'name.string' => 'Name phải là một chuỗi',
            'name.max' => 'Name không được vượt quá 255 ký tự',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email phải là một địa chỉ email hợp lệ',
            'email.unique' => 'Email đã tồn tại',
            'password.required' => 'Mật khẩu là bắt buộc',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password_confirmation.required' => 'Nhập lại mật khẩu là bắt buộc',
            'password_confirmation.same' => 'Mật khẩu nhập lại không khớp',
            'role.required' => 'Vai trò là bắt buộc',
            'role.in' => 'Vai trò phải là admin hoặc user hoặc agent'
        ]);
    }
   public function validateGetUser(Request $request)
    {
        return Validator::make(
            $request->all(),
            [
                'id' => ['required', 'integer', 'exists:users,id'],
            ],
            [
                'id.required' => __('user.id_required'),
                'id.integer'  => __('user.id_integer'),
                'id.exists'   => __('user.id_exists'),
            ]
        );
    }
    public function validateUpdate(Request $request){
        return Validator::make($request->all(), [
            'name' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|max:255',
            'password' => 'sometimes|string|min:8',
            'password_confirmation' => 'sometimes|required|string|same:password',
        ], [
            'name.max' => 'Tên tối đa 100 ký tự',
            'email.email' => 'Email không hợp lệ',
            'password.min' => 'Mật khẩu tối thiểu 8 ký tự',
            'password_confirmation.required' => 'Nhập lại mật khẩu là bắt buộc',
            'password_confirmation.same' => 'Mật khẩu nhập lại không khớp',
        ]);
    }
}
