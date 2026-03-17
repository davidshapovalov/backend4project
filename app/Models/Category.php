<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'color',
    ];

    protected $casts = [
        'name' => 'string',
        'color' => 'string',
    ];

    public function notes()
    {
        return $this->belongsToMany(Note::class, 'note_category', 'category_id', 'note_id')->withTimestamps();
    }
}
