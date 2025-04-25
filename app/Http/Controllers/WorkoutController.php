<?php
namespace App\Http\Controllers;
use App\Models\Workout;
use Illuminate\Http\Request;

class WorkoutController extends Controller {
    public function index() { return Workout::all(); }
    public function store(Request $request) { return Workout::create($request->all()); }
    public function show(Workout $workout) { return $workout; }
    public function update(Request $request, Workout $workout) { $workout->update($request->all()); return $workout; }
    public function destroy(Workout $workout) { $workout->delete(); return response(null, 204); }
}
