<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'salary_level_id',
        'level_number',
        'required_directs',
        'qualifying_directs',
        'amount',
        'claim_period',
        'claimed_at',
        'transaction_id',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'level_number' => 'integer',
        'required_directs' => 'integer',
        'qualifying_directs' => 'integer',
        'amount' => 'decimal:2',
        'claimed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salaryLevel()
    {
        return $this->belongsTo(SalaryLevel::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
