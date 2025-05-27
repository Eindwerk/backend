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
        return view('emails.invite-admin-form', compact('user'));
    }


    public function storePassword(Request $request, User $user)
    {
        Log::info('Wachtwoord-formulier ontvangen', $request->all());

        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $hashed = Hash::make($request->password);
        Log::info('Gegenereerd hash:', ['hash' => $hashed]);

        $updated = $user->update([
            'password' => $hashed,
        ]);

        Log::info('Gebruiker geüpdatet?', ['result' => $updated]);

        return redirect('/admin/login')->with('success', 'Wachtwoord ingesteld. Je kan nu inloggen.');
    }
}
