<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workout extends Model {
    use HasFactory;
    protected $fillable = ['week_id', 'day_of_week', 'notes'];
    public function week() { return $this->belongsTo(Week::class); }
    public function workoutExercises() { return $this->hasMany(WorkoutExercise::class); }
}