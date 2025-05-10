<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExerciseLog extends Model {
    use HasFactory;
    protected $fillable = ['client_id', 'workout_exercise_id', 'date', 'set_number', 'weight', 'reps_done'];
    public function client() { return $this->belongsTo(User::class); }
    public function workoutExercise() { return $this->belongsTo(WorkoutExercise::class); }
}