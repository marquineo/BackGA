<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class TrainingBlock extends Model {
    use HasFactory;
    protected $fillable = ['title', 'description', 'trainer_id', 'client_id'];
    public function trainer() { return $this->belongsTo(User::class, 'trainer_id'); }
    public function client() { return $this->belongsTo(User::class, 'client_id'); }
    public function weeks() { return $this->hasMany(Week::class); }
}
