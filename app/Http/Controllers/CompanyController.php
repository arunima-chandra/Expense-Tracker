<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    /**
     * Regenerate the company's invite code.
     */
    public function regenerateInviteCode(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            abort(404, 'Company not found.');
        }

        $newCode = $company->regenerateInviteCode();

        return back()->with('success', "Invite code regenerated! New code: {$newCode}");
    }

    /**
     * Remove an employee from the company.
     */
    public function removeMember(Request $request, User $user)
    {
        $admin = Auth::user();

        // Ensure the target user belongs to the same company
        if ($user->company_id !== $admin->company_id) {
            abort(403, 'Unauthorized. This user does not belong to your company.');
        }

        // Prevent admin from removing themselves
        if ($user->id === $admin->id) {
            return back()->withErrors(['member_error' => 'You cannot remove yourself as Admin.']);
        }

        $userName = $user->name;

        // Reset the removed user back to personal mode
        $user->update([
            'mode' => 'personal',
            'role' => null,
            'company_id' => null,
        ]);

        return back()->with('success', "Employee '{$userName}' has been removed from the company.");
    }
}
