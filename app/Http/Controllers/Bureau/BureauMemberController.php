<?php

namespace App\Http\Controllers\Bureau;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BureauMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query()->with('teams');

        $search = $request->input('search');
        //Recherche globale(nom,email,pseudo)
        if ($search) {

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")->orWhere('pseudo', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
        });
        }


        //Filtrage par rôle
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        //Filtrage par date de création
        //Pour formater les dates
        $startDate = $request->filled('registered_from') ? Carbon::parse($request->input('registered_from'))->startOfDay() : null;

        $endDate = $request->filled('registered_to') ? Carbon::parse($request->input('registered_to'))->endOfDay() : null;

        //Si il y a deux date renseignées , on filtre entre les deux dates
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) { //Sinon si il y a que la date de début on filtre à partir de cette date
            $query->whereDate('created_at', '>=', $startDate);
        } elseif($endDate){
            $query->whereDate('created_at','<=', $endDate);
        }

        //Filtre par statut(actif/inactif)
       if ($request->filled('status')) {
            $status = $request->input('status');
            $query->where('is_active', $status === 'active');
        }

        //filtre par équipe (via pivot team_user)
        if($request->filled('team_id')){
            $query->whereHas('teams',function($q) use ($request){
                $q->where('teams.id',$request->team_id);
            });
        }

        //Pagination
        $perPage = $request->input('perPage', 10);
        $members = $query->paginate($perPage)
                         ->appends($request->query());

        //On retourne les filtres au front

        return Inertia::render('Bureau/IndexBureau',[
            'members' => $members,
            'teams' => Team::select('id','name')->get(),

            'filters' => [
                'search' => $request->search ?? '',
                'role' => $request->role ?? '',
                'team_id' => $request->team_id ?? '',
                'status' => $request->status ?? '',
                'registered_from' => $request->$request->registered_from ?? '',
                'registered_to' => $request->registered_to ?? '',
                'perPage' => $perPage,

            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
