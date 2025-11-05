<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $password = $value;
        
        // Kiểm tra độ dài tối thiểu
        if (strlen($password) < 8) {
            $fail('Mật khẩu phải có ít nhất 8 ký tự.');
            return;
        }
        
        // Kiểm tra có chữ hoa
        if (!preg_match('/[A-Z]/', $password)) {
            $fail('Mật khẩu phải chứa ít nhất một chữ cái viết hoa.');
            return;
        }
        
        // Kiểm tra có chữ thường
        if (!preg_match('/[a-z]/', $password)) {
            $fail('Mật khẩu phải chứa ít nhất một chữ cái viết thường.');
            return;
        }
        
        // Kiểm tra có số
        if (!preg_match('/[0-9]/', $password)) {
            $fail('Mật khẩu phải chứa ít nhất một chữ số.');
            return;
        }
        
        // Kiểm tra có ký tự đặc biệt
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $fail('Mật khẩu phải chứa ít nhất một ký tự đặc biệt.');
            return;
        }
        
        // Kiểm tra không chứa thông tin cá nhân phổ biến
        $commonPatterns = [
            'password', '123456', 'qwerty', 'abc123', 'admin',
            'user', 'test', 'demo', 'guest', 'root'
        ];
        
        foreach ($commonPatterns as $pattern) {
            if (stripos($password, $pattern) !== false) {
                $fail('Mật khẩu không được chứa các từ phổ biến như: ' . implode(', ', $commonPatterns));
                return;
            }
        }
        
        // Kiểm tra không có chuỗi lặp lại
        if (preg_match('/(.)\1{2,}/', $password)) {
            $fail('Mật khẩu không được chứa các ký tự lặp lại liên tiếp.');
            return;
        }
    }
}
