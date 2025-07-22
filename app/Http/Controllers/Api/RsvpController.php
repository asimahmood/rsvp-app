<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guest;
use App\Models\SubGuest;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RsvpController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'guests' => 'array',
            'guests.*' => 'nullable|string',
            'interests' => 'nullable|string'
        ]);
    
        // Check for existing guest
        $existing = Guest::where('name', $request->name)
            ->where('phone', $request->phone)
            ->first();
    
        if ($existing) {
            return response()->json([
                'message' => 'You have already RSVP’d.',
                'qr_code' => 'data:image/png;base64,' . base64_encode(
                    QrCode::format('png')->size(250)->generate($existing->qr_token)
                ),
                'qr_token' => $existing->qr_token
            ]);
        }
    
        // Generate new token
        $qrToken = Str::uuid();
    
        // Create new guest
        $guest = Guest::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'interests' => $request->interests,
            'qr_token' => $qrToken,
        ]);
    
        // Create sub-guests if any
        if ($request->guests) {
            foreach ($request->guests as $guestName) {
                if (!empty($guestName)) {
                    SubGuest::create([
                        'guest_id' => $guest->id,
                        'name' => $guestName
                    ]);
                }
            }
        }
    
        $qrCode = base64_encode(
            QrCode::format('png')->size(250)->errorCorrection('H')->generate($qrToken)
        );
    
        return response()->json([
            'message' => 'RSVP successful!',
            'qr_code' => 'data:image/png;base64,' . $qrCode,
            'qr_token' => $qrToken,
        ]);
    }
    

    public function validateQr($token)
{
    $guest = Guest::where('qr_token', $token)->with('subGuests')->first();

    if (!$guest) {
        return response()->json(['message' => 'Invalid QR Code'], 404);
    }

    return response()->json([
        'name' => $guest->name,
        'phone' => $guest->phone,
        'checked_in' => $guest->checked_in,
        'guests' => $guest->subGuests->pluck('name'),
    ]);
}

public function markEntry($token)
{
    $guest = Guest::where('qr_token', $token)->first();

    if (!$guest) {
        return response()->json(['message' => 'Invalid QR Code'], 404);
    }

    if ($guest->checked_in) {
        return response()->json(['message' => 'Already checked in'], 400);
    }

    $guest->checked_in = true;
    $guest->save();

    return response()->json(['message' => 'Entry marked successfully']);
}

public function storeIndividual(Request $request)
{
    return $this->storeGeneric($request, 'individual');
}

public function storeCouple(Request $request)
{
    return $this->storeGeneric($request, 'couple');
}

public function storeVip(Request $request)
{
    return $this->storeGeneric($request, 'vip');
}

private function storeGeneric(Request $request, $type)
{
    $request->validate([
        'name' => 'required|string',
        'phone' => 'required|string',
        'interests' => 'nullable|string',
        'guests' => 'array',
        'guests.*' => 'nullable|string',
    ]);

    $existing = Guest::where('name', $request->name)
        ->where('phone', $request->phone)
        ->first();

    if ($existing) {
        return response()->json([
            'message' => 'You have already RSVP’d.',
            'qr_code' => 'data:image/png;base64,' . base64_encode(
                QrCode::format('png')->size(250)->generate($existing->qr_token)
            ),
            'qr_token' => $existing->qr_token
        ]);
    }

    $qrToken = Str::uuid()->toString();

    $guest = Guest::create([
        'name' => $request->name,
        'phone' => $request->phone,
        'interests' => $request->interests,
        'qr_token' => $qrToken,
        'type' => $type
    ]);

    if ($type === 'vip' && $request->guests) {
        foreach ($request->guests as $guestName) {
            if ($guestName) {
                SubGuest::create([
                    'guest_id' => $guest->id,
                    'name' => $guestName
                ]);
            }
        }
    }

    $qrCode = base64_encode(QrCode::format('png')->size(250)->generate($qrToken));

    return response()->json([
        'message' => 'RSVP successful!',
        'qr_code' => 'data:image/png;base64,' . $qrCode,
        'qr_token' => $qrToken,
    ]);
}


}
