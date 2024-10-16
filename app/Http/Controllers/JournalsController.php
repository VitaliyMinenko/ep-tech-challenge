<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\JournalRequest;
use App\Journal;
use Illuminate\Http\JsonResponse;

class JournalsController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function store(JournalRequest $request): JsonResponse
    {
        $journal = $request->validated();

        return response()->json(Journal::create($journal));
    }

    public function destroy($journalId): JsonResponse
    {
        $deleted = Journal::where('id', $journalId)->delete();

        if ($deleted) {
            return response()->json('Deleted', 200);
        }

        return response()->json('Not Found', 404);
    }
}
