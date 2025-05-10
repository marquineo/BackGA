<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Week extends Model {
    use HasFactory;
    protected $fillable = ['training_block_id', 'week_number'];
    public function trainingBlock() { return $this->belongsTo(TrainingBlock::class); }
    public function workouts() { return $this->hasMany(Workout::class); }
}
