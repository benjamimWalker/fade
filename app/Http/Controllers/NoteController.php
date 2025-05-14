<?php

namespace App\Http\Controllers;

use App\Actions\CreateNote;
use App\Actions\ShowNote;
use App\Http\Requests\NoteRequest;
use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class NoteController extends Controller
{
    public function store(NoteRequest $request, CreateNote $createNote): JsonResponse
    {
        $note = $createNote->handle($request->validated());

        return response()->json([
            'access_url' => $note->accessUrl,
        ], Response::HTTP_CREATED);
    }

    public function show(Note $note, ShowNote $showNote): JsonResponse
    {
        return response()->json($showNote->handle($note));
    }
}
