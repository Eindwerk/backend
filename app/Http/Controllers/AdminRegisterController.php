<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminRegisterController extends Controller
{
    public function showForm(User $user)
    {
        // Optional: prevent reuse if password is already set
        if ($user->password) {
            return redirect('/login')->with('error', 'Dit account is al geactiveerd.');
        }

        return view('emails.invite-admin-form', compact('user'));
    }

    public function storePassword(Request $request, User $user)
    {
        Log::info('Wachtwoordformulier verstuurd voor user ID: ' . $user->id);

        $validated = $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user->password = Hash::make($validated['password']);
        $user->save();

        Log::info("Wachtwoord ingesteld voor gebruiker ID: {$user->id}");

        return redirect('/login')->with('success', 'Wachtwoord ingesteld. Je kan nu inloggen.');
    }
}
