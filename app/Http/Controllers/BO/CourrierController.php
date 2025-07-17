<?php
namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Courrier;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class CourrierController extends Controller
{
    public function showAffectForm(Courrier $courrier)
    {
        $cabUser = User::role('cab')->first();
        $sgUser = User::role('sg')->first();
        $daiUser = User::role('dai')->first();
        $divisionUser = User::role('chef_division')->get();

        return view('affectations.form', [
            'courrier' => $courrier,
            'cabUser' => $cabUser,
            'sgUser' => $sgUser, // leave empty for BO
            'daiUser' => $daiUser, // leave empty for BO
            'divisionUsers' => $divisionUser, // leave empty for BO
        ]);
    }

    public function affectToCAB(Request $request, Courrier $courrier)
    {
        $request->validate([
            'id_affecte_a_utilisateur' => 'required|exists:users,id',
        ]);

        // Optional: Check if already affected
        if ($courrier->affectations()->exists()) {
            return back()->withErrors('This courrier is already assigned.');
        }

        DB::transaction(function () use ($courrier, $request) {
            $courrier->update(['statut' => 'en_cours']);
                if(auth()->user()->role == 'bo'){
            $courrier->affectations()->create([
                        'statut_affectation' => 'en_cours',
                        'date_affectation' => now(),
                        'Instruction' => null,
                        'id_affecte_a_utilisateur' => $request->id_affecte_a_utilisateur,
                    ]);
                }elseif(auth()->user()->role == 'cab'){
                    $courrier->affectations()->create([
                        'statut_affectation' => 'en_cours',
                        'date_affectation' => now(),
                        'Instruction' => $request->Instruction_cab,
                        'id_affecte_a_utilisateur' => $request->id_affecte_a_utilisateur,
                        'id_affecte_par_utilisateur' => auth()->id(),
                    ]);
                }elseif(auth()->user()->role == 'sg'){
                    $courrier->affectations()->create([
                        'statut_affectation' => 'en_cours',
                        'date_affectation' => now(),
                        'Instruction' => $request->Instruction_sg,
                        'id_affecte_a_utilisateur' => $request->id_affecte_a_utilisateur,
                        'id_affecte_par_utilisateur' => auth()->id(),
                    ]);
                }
        });
        return redirect()->route('bo.courriers.index')
                         ->with('success', 'Courrier assigned to CAB successfully.');
    }
}
