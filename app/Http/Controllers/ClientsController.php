<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\ClientRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ClientsController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();
        $clients = Client::where('user_id', $currentUser->getAuthIdentifier())->get();
        foreach ($clients as $client) {
            $client->append('bookings_count');
        }

        return view('clients.index', ['clients' => $clients]);
    }

    public function create()
    {
        return view('clients.create');
    }

    public function show($clientId)
    {
        $client = Client::with(['bookings' => function ($query) {
            $query->orderBy('start', 'desc');
        }])->with('journals')->where('id', $clientId)->first();

        return view('clients.show', ['client' => $client]);
    }

    public function store(ClientRequest $request): JsonResponse
    {
        $clientData = $request->validated();

        return response()->json(Client::create($clientData));
    }

    public function destroy($client): JsonResponse
    {
        $deleted = Client::where('id', $client)->delete();

        if ($deleted) {
            return response()->json('Deleted', 200);
        }

        return response()->json('Not Found', 404);
    }
}
