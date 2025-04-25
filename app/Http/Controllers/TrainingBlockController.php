<?php
namespace App\Http\Controllers;
use App\Models\TrainingBlock;
use Illuminate\Http\Request;

class TrainingBlockController extends Controller {
    public function index() { return TrainingBlock::all(); }
    public function store(Request $request) { return TrainingBlock::create($request->all()); }
    public function show(TrainingBlock $trainingBlock) { return $trainingBlock; }
    public function update(Request $request, TrainingBlock $trainingBlock) { $trainingBlock->update($request->all()); return $trainingBlock; }
    public function destroy(TrainingBlock $trainingBlock) { $trainingBlock->delete(); return response(null, 204); }
}