<?php

namespace App\Actions;

use App\Models\Note;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShowNote
{
    public function handle(Note $note): array
    {
        if ($note->hasExpired()) {
            throw new NotFoundHttpException();
        }

        if ($note->view_limit) {
            if ($note->view_limit <= 1) {
                $note->delete();
            } else {
                $note->decrement('view_limit');
            }
        }

        return [
            'body' => Crypt::decryptString($note->body)
        ];
    }
}
