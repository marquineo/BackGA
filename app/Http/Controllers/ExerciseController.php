<?php
namespace App\Http\Controllers;
use App\Models\Exercise;
use Illuminate\Http\Request;

class ExerciseController extends Controller {
    public function index() { return Exercise::all(); }
    public function store(Request $request) { return Exercise::create($request->all()); }
    public function show(Exercise $exercise) { return $exercise; }
    public function update(Request $request, Exercise $exercise) { $exercise->update($request->all()); return $exercise; }
    public function destroy(Exercise $exercise) { $exercise->delete(); return response(null, 204); }
}