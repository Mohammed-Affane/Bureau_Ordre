<?php

namespace App\Http\Controllers\Affectations;

use App\Models\Courrier;
use App\Http\Controllers\Controller;
use App\Models\Affectation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AffectationController extends Controller
{
    public function create($courrierId)
    {
        $courrier = Courrier::findOrFail($courrierId);
        $currentUser = Auth::user();

        if (!$currentUser) {
            abort(403, 'Unauthorized');
        }

        $currentUserRole = $currentUser->roles->first()->name ?? null;
        if (!$currentUserRole) {
            abort(403, 'User has no assigned role');
        }

        $assignableRoles = $this->getAssignableRoles($currentUserRole);
        $users = User::whereHas('roles', function($q) use ($assignableRoles) {
            $q->whereIn('name', $assignableRoles);
        })->get();

        // Determine which instruction field to show based on current user's role
        $showCabInstruction = $currentUserRole === 'cab';
        $showSgInstruction = $currentUserRole === 'sg';

        return view('affectations.create', compact(
            'courrier',
            'users',
            'showCabInstruction',
            'showSgInstruction'
        ));
    }

public function store(Request $request, $courrierId)
{
    $request->validate([
        'id_affecte_a_utilisateur' => 'required|array',
        'id_affecte_a_utilisateur.*' => 'exists:users,id',
        'instruction_cab' => 'nullable|string|max:255',
        'instruction_sg' => 'nullable|string|max:255',
    ]);

    $courrier = Courrier::findOrFail($courrierId);
    $currentUser = Auth::user();

    if (!$currentUser) {
        abort(403, 'Unauthorized');
    }

    $currentUserRole = $currentUser->roles->first()->name ?? null;
    if (!$currentUserRole) {
        abort(403, 'User has no assigned role');
    }

    $assignableRoles = $this->getAssignableRoles($currentUserRole);
    $errors = [];

    foreach ($request->id_affecte_a_utilisateur as $userId) {
        $affecteA = User::findOrFail($userId);
        $affecteARole = $affecteA->roles->first()->name ?? null;


        if (!$affecteARole || !in_array($affecteARole, $assignableRoles)) {
            $errors[] = "Vous ne pouvez pas affecter à l'utilisateur {$affecteA->name} (rôle: {$affecteARole}).";
            continue;
        }

        // Store the appropriate instruction based on current user's role
        $instruction = '';
        if ($currentUserRole === 'cab' && $request->instruction_cab) {
            $instruction = "CAB: " . $request->instruction_cab;
        } elseif ($currentUserRole === 'sg' && $request->instruction_sg) {
            $instruction = "SG: " . $request->instruction_sg;
        }

        // Determine status based on current user's role and target role
$status_affectation = null;

if ($currentUserRole === 'bo') {
    $status_affectation = 'a_cab';
} elseif ($currentUserRole === 'cab') {
    if ($affecteARole === 'sg') {
        $status_affectation = 'a_sg';
    }
    elseif (in_array($affecteARole, ['chef_division', 'dai'])) {
        $status_affectation = 'a_div';
    }

} elseif ($currentUserRole === 'sg') {
    if (in_array($affecteARole, ['chef_division', 'dai'])) {
        $status_affectation = 'a_div';
    }
}
        Affectation::create([
            'id_courrier' => $courrier->id,
            'id_affecte_a_utilisateur' => $affecteA->id,
            'id_affecte_par_utilisateur' => $currentUser->id,
            'Instruction' => $instruction,
            'statut_affectation' => $status_affectation,
            'date_affectation' => now(),
        ]);
    }

    if (!empty($errors)) {
        return back()->withErrors($errors);
    }

    // Update courrier status
    if (($currentUserRole === 'sg' && $status_affectation === 'a_div' )||($currentUserRole === 'cab' && $status_affectation === 'a_div')) {
        $courrier->update(['statut' => 'arriver']);
    }
     else {
        $courrier->update(['statut' => 'en_cours']);
    }

    return redirect()->route("courriers.$courrier->type_courrier")
        ->with('success', 'Courrier affecté avec succès à ' . count($request->id_affecte_a_utilisateur) . ' utilisateur(s).');
}

    private function getAssignableRoles(string $role): array
    {
        return match ($role) {
            'bo' => ['cab'],
            'cab' => ['dai', 'sg', 'chef_division'],
            'sg' => ['dai', 'chef_division'],
            'admin'=>['cab','dai', 'sg', 'chef_division'],
            default => [],
        };
    }
}