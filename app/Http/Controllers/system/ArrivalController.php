<?php

namespace App\Http\Controllers\system;

use App\Http\Controllers\Controller;
use App\Imports\ArrivalImport;
use App\Models\Arrival;
use App\Models\ArrivalLine;
use App\Models\ArrivalLineType;
use App\Models\ArrivalType;
use App\Models\City;
use App\Models\Client;
use App\Models\DeliveryPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $arrivalTypes = ArrivalType::all();
        $clients = Client::all();
        $cities = City::all();
        $lineTypes = ArrivalLineType::all();
        $deliveryPoints = DeliveryPoint::all();
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

    public function store_arrival(Request $request)
    {
        try {
            DB::beginTransaction();
            $arrivalData = $request->arrival;
            $arrival = new Arrival();
            $arrival->client_id = $arrivalData['client_id'];
            $arrival->arrival_type_id = $arrivalData['arrival_type_id'];
            $arrival->dossier_tegic = $arrivalData['dossier_tegic'];
            $arrival->status = 'cree';
            $arrival->dossier_client = $arrivalData['dossier_client'];
            $arrival->shipping_compagnie = $arrivalData['shipping_compagnie'];
            $arrival->city_id = $arrivalData['city_id'];
            $arrival->eta = $arrivalData['eta'];
            $arrival->ata = $arrivalData['ata'];
            $arrival->lieu_de_chargement = $arrivalData['lieu_de_chargement'];
            $arrival->lieu_de_dechargement = $arrivalData['lieu_de_dechargement'];
            $arrival->lieu_de_restitution = $arrivalData['lieu_de_restitution'];
            $arrival->date_bae_Previsionnelle = $arrivalData['date_bae_Previsionnelle'];
            $arrival->date_magasinage = $arrivalData['date_magasinage'];
            $arrival->date_surestaries = $arrivalData['date_surestaries'];
            $arrival->created_by = Auth::id();
            $arrival->save();

            // Store arrival lines
            foreach ($request->arrival_lines as $arrival_line_data) {
                $arrivalLine = new ArrivalLine(); // Assuming ArrivalLine is your model
                $arrivalLine->arrival_id = $arrival->id;
                $arrivalLine->numero = $arrival_line_data['numero'];
                $arrivalLine->arrival_line_type_id = $arrival_line_data['arrival_line_type_id'];
                $arrivalLine->nb_de_pieces = $arrival_line_data['nb_de_pieces'];
                $arrivalLine->poids = $arrival_line_data['poids'];
                $arrivalLine->volume = $arrival_line_data['volume'];
                $arrivalLine->save();
            }
            $arrival->load(['clients', 'arrival_types', 'cities']);
            DB::commit();
            return response()->json(['message' => 'Vos arrivage ont ete ajoutes avec success', 'arrival' => $arrival]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function details($arrival_id)
    {
        $arrival = Arrival::where('id', $arrival_id)->with('arrival_lines', 'clients', 'cities', 'arrival_types')->first();
        return response()->json(['arrival' => $arrival]);
    }

    public function delete_arrival(Request $request)
    {
        $header = Arrival::findOrFail($request->id);
        // Delete related lines
        $header->arrival_lines()->delete();
        // Then delete the header
        $header->delete();
        return response()->json(['message' => 'Arrivage supprimée avec succès']);
    }

    public function update_arrival(Request $request)
    {
        try {
            DB::beginTransaction();
            $arrivalData = $request->arrival;
            $arrival = Arrival::find($arrivalData['arrival_id']);
            $arrival->arrival_lines()->delete();
            $arrival->client_id = $arrivalData['client_id'];
            $arrival->arrival_type_id = $arrivalData['arrival_type_id'];
            $arrival->dossier_tegic = $arrivalData['dossier_tegic'];
            $arrival->status = 'cree';
            $arrival->dossier_client = $arrivalData['dossier_client'];
            $arrival->shipping_compagnie = $arrivalData['shipping_compagnie'];
            $arrival->city_id = $arrivalData['city_id'];
            $arrival->eta = $arrivalData['eta'];
            $arrival->ata = $arrivalData['ata'];
            $arrival->lieu_de_chargement = $arrivalData['lieu_de_chargement'];
            $arrival->lieu_de_dechargement = $arrivalData['lieu_de_dechargement'];
            $arrival->lieu_de_restitution = $arrivalData['lieu_de_restitution'];
            $arrival->date_bae_Previsionnelle = $arrivalData['date_bae_Previsionnelle'];
            $arrival->date_magasinage = $arrivalData['date_magasinage'];
            $arrival->date_surestaries = $arrivalData['date_surestaries'];
            $arrival->created_by = Auth::id();
            $arrival->save();

            // Store arrival lines
            foreach ($request->arrival_lines as $arrival_line_data) {
                $arrivalLine = new ArrivalLine(); // Assuming ArrivalLine is your model
                $arrivalLine->arrival_id = $arrivalData['arrival_id'];
                $arrivalLine->numero = $arrival_line_data['numero'];
                $arrivalLine->arrival_line_type_id = $arrival_line_data['arrival_line_type_id'];
                $arrivalLine->nb_de_pieces = $arrival_line_data['nb_de_pieces'];
                $arrivalLine->poids = $arrival_line_data['poids'];
                $arrivalLine->volume = $arrival_line_data['volume'];
                $arrivalLine->save();
            }
            $arrival->load(['clients', 'arrival_types', 'cities']);
            DB::commit();
            return response()->json(['message' => 'Vos arrivage ont ete ajoutes avec success', 'arrival' => $arrival]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function validerBae(Request $request)
    {
        try {
            DB::beginTransaction();
            $arrival = Arrival::findOrFail($request->arrival_id);
            $arrival->date_remise = $request->date_remise;
            $arrival->status = 'main_levee';
            $arrival->save();
            DB::commit();
            return response()->json(['message' => 'Voter BAE est remis avec succes']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
