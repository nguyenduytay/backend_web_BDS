<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StrongPassword implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $password = $value;

        // Kiểm tra độ dài tối thiểu
        if (strlen($password) < 8) {
            return false;
        }

        // Kiểm tra có chữ hoa
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }

        // Kiểm tra có chữ thường
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }

        // Kiểm tra có số
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }

        // Kiểm tra có ký tự đặc biệt
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            return false;
        }

        // Kiểm tra không chứa thông tin cá nhân phổ biến
        $commonPatterns = [
            'password', '123456', 'qwerty', 'abc123', 'admin',
            'user', 'test', 'demo', 'guest', 'root'
        ];

        foreach ($commonPatterns as $pattern) {
            if (stripos($password, $pattern) !== false) {
                return false;
            }
        }

        // Kiểm tra không có chuỗi lặp lại
        if (preg_match('/(.)\1{2,}/', $password)) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt.';
    }
}
