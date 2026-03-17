<?php

use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::apiResource('categories', CategoryController::class);
Route::apiResource('notes', NoteController::class);

Route::get('notes/stats/status', [NoteController::class, 'statsByStatus']);

Route::patch('notes/actions/archive-old-drafts', [NoteController::class, 'archiveOldDrafts']);

Route::get('users/{user}/notes', [NoteController::class, 'userNotesWithCategories']);

Route::get('notes-actions/search', [NoteController::class, 'search']);

Route::post('/notes/{id}/pin', [NoteController::class, 'pinNote']);
Route::post('/notes/{id}/unpin', [NoteController::class, 'unpinNote']);

Route::post('/notes/{id}/publish', [NoteController::class, 'publish']);
Route::post('/notes/{id}/archive', [NoteController::class, 'archive']);
