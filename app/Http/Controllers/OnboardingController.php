<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    /**
     * Display the mode selection & setup screen.
     */
    public function show()
    {
        $user = Auth::user();
        return view('onboarding', compact('user'));
    }

    /**
     * Choose Personal mode.
     */
    public function selectPersonal()
    {
        $user = Auth::user();
        $user->update([
            'mode' => 'personal',
            'role' => null,
            'company_id' => null,
        ]);

        return redirect('/dashboard')->with('success', 'Personal mode selected! Start tracking your personal finances.');
    }

    /**
     * Create a new company and become its Admin.
     */
    public function createCompany(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $inviteCode = Company::generateUniqueInviteCode();

        $company = Company::create([
            'name' => $validated['name'],
            'invite_code' => $inviteCode,
        ]);

        $user = Auth::user();
        $user->update([
            'mode' => 'company',
            'role' => 'admin',
            'company_id' => $company->id,
        ]);

        return redirect('/dashboard')->with('success', "Company '{$company->name}' created successfully! Your invite code is {$inviteCode}.");
    }

    /**
     * Join an existing company using an invite code.
     */
    public function joinCompany(Request $request)
    {
        $request->validate([
            'invite_code' => 'required|string',
        ]);

        $code = strtoupper(trim($request->input('invite_code')));

        $company = Company::where('invite_code', $code)->first();

        if (!$company) {
            return back()
                ->withErrors(['invite_code' => 'Invalid or expired invite code. Please check with your company administrator.'])
                ->withInput();
        }

        $user = Auth::user();
        $user->update([
            'mode' => 'company',
            'role' => 'employee',
            'company_id' => $company->id,
        ]);

        return redirect('/dashboard')->with('success', "You have joined '{$company->name}' as an Employee!");
    }
}
