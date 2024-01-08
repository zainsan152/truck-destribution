<?php

namespace App\Http\Controllers\system;

use App\Http\Controllers\Controller;
use App\Imports\ArrivalImport;
use App\Models\Arrival;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ArrivalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $arrivals = Arrival::with('clients', 'arrival_types', 'cities')->get();
        return view('system.arrivals.index', get_defined_vars());
    }

    public function import(Request $request)
    {
        $file = $request->file('file');

        // Check if a file was uploaded
        if ($file) {
            // Perform the import logic using Maatwebsite/Laravel-Excel
            Excel::import(new ArrivalImport(), $file);

            // Redirect back with a success message
            return redirect()->route('arrivals')->with('success', 'Données importées avec succès.');
        }
        return redirect()->route('arrivals')->with('error', 'Merci de sélectionner un fichier.');
    }
}
