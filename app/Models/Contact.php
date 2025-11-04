<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
   // use SoftDeletes; // nếu bạn muốn hỗ trợ deleted_at

    protected $table = 'contacts'; // tên bảng

    protected $fillable = [
        'name',
        'phone',
        'email',
        'user_id',
    ];

    // Quan hệ với User (nếu muốn)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
