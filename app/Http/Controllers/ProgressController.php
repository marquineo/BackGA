<?php
namespace App\Http\Controllers;
use App\Models\ExerciseLog;
use Illuminate\Http\Request;

class ProgressController extends Controller {
    public function index() { return ExerciseLog::all(); }
    public function store(Request $request) { return ExerciseLog::create($request->all()); }
    public function show(ExerciseLog $progress) { return $progress; }
    public function update(Request $request, ExerciseLog $progress) { $progress->update($request->all()); return $progress; }
    public function destroy(ExerciseLog $progress) { $progress->delete(); return response(null, 204); }
}