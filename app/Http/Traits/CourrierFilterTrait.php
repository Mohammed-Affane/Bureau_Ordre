<?php

namespace App\Http\Traits;

use Carbon\Carbon;

trait CourrierFilterTrait
{
    /**
     * Applique les filtres communs à tous les types de courriers
     */
    protected function applyCourrierFilters($query, $typeCourrier)
    {
        return $query->with(['expediteur', 'agent','affectations'])
            ->where('type_courrier', $typeCourrier)
            ->when(request('search'), function($q) {
                $q->where(function($query) {
                    $query->where('reference_arrive', 'like', '%'.request('search').'%')
                        ->orWhere('reference_bo', 'like', '%'.request('search').'%')
                        ->orWhere('reference_visa', 'like', '%'.request('search').'%')
                        ->orWhere('reference_dec', 'like', '%'.request('search').'%')
                        ->orWhere('reference_depart', 'like', '%'.request('search').'%')
                        ->orWhere('objet', 'like', '%'.request('search').'%')
                        ->orWhereHas('expediteur', function($q) {
                            $q->where('nom', 'like', '%'.request('search').'%')
                            ->orWhere('CIN', 'like', '%'.request('search').'%');
                        });
                });
            })
            ->when(request('statut'), function($q) {
                $q->where('statut', request('statut'));
            })
            ->when(request('priorite'), function($q) {
                $q->where('priorite', request('priorite'));
            })
            ->when(request('date_range'), function($q) {
                $this->applyDateFilter($q);
            })
            ->orderBy('date_enregistrement', 'desc');
    }

    /**
     * Applique le filtre de date selon la période sélectionnée
     */
   protected function applyDateFilter($query)
{
    switch (request('date_range')) {
        case 'today':
            $query->where(function($q) {
                $q->whereDate('date_reception', Carbon::today())
                  ->orWhereDate('date_depart', Carbon::today());
            });
            break;
            
        case 'week':
            $query->where(function($q) {
                $q->whereBetween('date_reception', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                  ])
                  ->orWhereBetween('date_depart', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                  ]);
            });
            break;
            
        case 'month':
            $query->where(function($q) {
                $q->whereBetween('date_reception', [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth()
                  ])
                  ->orWhereBetween('date_depart', [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth()
                  ]);
            });
            break;
            
        case 'year':
            $query->where(function($q) {
                $q->whereBetween('date_reception', [
                    Carbon::now()->startOfYear(),
                    Carbon::now()->endOfYear()
                  ])
                  ->orWhereBetween('date_depart', [
                    Carbon::now()->startOfYear(),
                    Carbon::now()->endOfYear()
                  ]);
            });
            break;
            
        case 'custom':
            if (request('date_from')) {
                $query->where(function($q) {
                    $q->whereDate('date_reception', '>=', request('date_from'))
                      ->orWhereDate('date_depart', '>=', request('date_from'));
                });
            }
            if (request('date_to')) {
                $query->where(function($q) {
                    $q->whereDate('date_reception', '<=', request('date_to'))
                      ->orWhereDate('date_depart', '<=', request('date_to'));
                });
            }
            break;
    }
}
}