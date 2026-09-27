<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'level_number',
        'name',
        'required_directs',
        'min_investment',
        'monthly_salary',
        'is_active',
        'description',
    ];

    protected $casts = [
        'level_number' => 'integer',
        'required_directs' => 'integer',
        'min_investment' => 'decimal:2',
        'monthly_salary' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function claims()
    {
        return $this->hasMany(SalaryClaim::class);
    }

    /**
     * Scope active levels ordered by required directs ascending.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
