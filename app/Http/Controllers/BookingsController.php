<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Booking;
use Illuminate\Http\JsonResponse;

class BookingsController extends Controller
{
    public function destroy($bookingId): JsonResponse
    {
        $deleted = Booking::where('id', $bookingId)->delete();

        if ($deleted) {
            return response()->json('Deleted', 200);
        }

        return response()->json('Not Found', 404);
    }
}
