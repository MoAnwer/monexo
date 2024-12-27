<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'target_amount', 'current_amount', 'due_date'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function calcRemainingAmount($target_amount, $current_amount) {
        return $target_amount - $current_amount;
    }

    public function calcProgress($target_amount, $current_amount) {
        return round(($current_amount / $target_amount) * 100, 2);
    }
}
