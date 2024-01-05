<?php

namespace App\Http\Controllers\system;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlanifiedRequest;
use App\Models\DistributionHeader;
use App\Models\DistributionLine;
use App\Models\Driver;
use App\Models\TruckCategory;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DistributionImport;
use SebastianBergmann\Diff\Line;
use Illuminate\Support\Facades\Auth;

class DistributionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Get all distribution records to extract driver IDs
        $distributionDrivers = DistributionHeader::pluck('id_driver')->all();
        $filteredDistributionDrivers = array_filter($distributionDrivers, function ($value) {
            return $value !== null;
        });

        // Get all distribution records to extract vehicle IDs
        $distributionVehicles = DistributionHeader::pluck('id_vehicule')->all();
        $filteredDistributionVehicles = array_filter($distributionVehicles, function ($value) {
            return $value !== null;
        });

        $header_ids = DB::table('mapping_driver_vehicle')->pluck('id_distribution_header')->toArray();
        $distributions = DistributionHeader::whereNotIn('id_distribution_header', $header_ids)->get();

        // Get all drivers excluding those with IDs in the distribution table
        $drivers = Driver::whereNotIn('id_driver', $filteredDistributionDrivers)->get();

        // Get all vehicles excluding those with IDs in the distribution table
        $vehicles = Vehicle::whereNotIn('id_vehicle', $filteredDistributionVehicles)->get();
        return view('system.distributions.index', ['distributions' => $distributions, 'drivers' => $drivers, 'vehicles' => $vehicles]);
    }

    public function distributionsplanifiees()
    {
        // Get all distribution records to extract driver IDs
        $distributionDrivers = DistributionHeader::pluck('id_driver')->all();
        $filteredDistributionDrivers = array_filter($distributionDrivers, function ($value) {
            return $value !== null;
        });

        // Get all distribution records to extract vehicle IDs
        $distributionVehicles = DistributionHeader::pluck('id_vehicule')->all();
        $filteredDistributionVehicles = array_filter($distributionVehicles, function ($value) {
            return $value !== null;
        });

        $header_ids = DB::table('mapping_driver_vehicle')->pluck('id_distribution_header')->toArray();
        $distributions = DistributionHeader::whereIn('id_distribution_header', $header_ids)->get();

        // Get all drivers excluding those with IDs in the distribution table
        $drivers = Driver::whereNotIn('id_driver', $filteredDistributionDrivers)->get();

        // Get all vehicles excluding those with IDs in the distribution table
        $vehicles = Vehicle::whereNotIn('id_vehicle', $filteredDistributionVehicles)->get();
        return view('system.distributions.distributionsplanifiees', ['distributionsplanifiees' => $distributions, 'drivers' => $drivers, 'vehicles' => $vehicles]);
    }

    public function import(Request $request)
    {
        $file = $request->file('file');
        $missingClients = [];

        // Check if a file was uploaded
        if ($file) {
            // Perform the import logic using Maatwebsite/Laravel-Excel
            Excel::import(new DistributionImport($missingClients), $file);
            // Check if there are missing clients
            if (!empty($missingClients)) {
                // You can use session to store the missing clients and display them in your Blade view
                session()->flash('missing_clients', array_unique($missingClients));
            }

            // Redirect back with a success message
            return redirect()->route('distributions')->with('success', 'Données importées avec succès.');
        }

        return redirect()->route('distributions')->with('error', 'Merci de sélectionner un fichier.');
    }

    public function details($distribution_id)
    {
        $distribution = DistributionHeader::where('id_distribution_header', $distribution_id)->with('distributionLines', 'distributionType')->first();
        return response()->json(['distribution' => $distribution]);
    }

    public function planify_distribution(PlanifiedRequest $request)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validated();
            $planifiedDistribution = DistributionHeader::where('id_distribution_header', $validatedData['distribution_id'])->whereNotNull('id_driver')->whereNotNull('id_vehicule')->exists();

            if ($planifiedDistribution) {
                return response()->json(['message' => 'Votre distribution est déjà planifiée'], 409);
            }
            $distributions = DistributionHeader::where('id_distribution_header', $validatedData['distribution_id'])->update(
                [
                    'date_execution' => $validatedData['execution_date'],
                    'id_driver' => $validatedData['driver_id'],
                    'id_vehicule' => $validatedData['vehicle_id'],
                ]
            );
            DB::table('mapping_driver_vehicle')->insert(
                [
                    'id_driver' => $validatedData['driver_id'],
                    'id_vehicle' => $validatedData['vehicle_id'],
                    'id_distribution_header' => $validatedData['distribution_id'],
                    'flag_status' => 'Planifié'
                ]
            );

    // Insert data into the ot_header table
     /*     
            DB::table('ot_header')->insert(
                [
                    'id_client' => 1,
                    'numero_ot' => $validatedData['axe_distribution'],
                    'id_type_ot' => 1,
                    'reference_ot' => $validatedData['distribution_id'],
                    'reference_company' => null,
                    'volume' => floatval($validatedData['volume']),
                    'qty' => floatval($validatedData['qty']),
                    'nbr_delivery_points' => 1,
                    'nbr_expected_days' => 1,
                    'requested_execution_date' => '2023-02-02',
                    'execution_date' => '2023-02-02',
                    'ot_closing_date' => null,
                    'pod' => null,
                    'estimated_days_count' => null,
                    'comments' => null,
                    'distance' => 0,
                    'id_city' => 1,
                    'is_mutual' => $validatedData['client_id'],
                    'id_truck_category' => null,
                    'date_order' => null,
                    'date_execution' => null,
                    'id_driver' => $validatedData['driver_id'],
                    'id_vehicule' => $validatedData['vehicle_id'],
                    'date_delivery' => null,
                    'ot_status' => 1,                
                    'createdby' => 1,
                    'modifiedby' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ); 
            */       
            
            // Get User ID
            $currentUserId = Auth::id();

            // Insert into ot_header based on distribution_header data
            DB::statement("
                INSERT INTO ot_header (id_client, numero_ot, id_type_ot, reference_ot, reference_company, volume, qty, nbr_delivery_points, nbr_expected_days, requested_execution_date, execution_date, ot_closing_date, pod, estimated_days_count, comments, distance, id_city, is_mutual, id_truck_category, date_order, date_execution, id_driver, id_vehicule, date_delivery, ot_status, createdby, modifiedby, created_at, updated_at)
                SELECT 
                    id_client, 
                    id_distribution_header,
                    1 as id_type_ot, -- Setting id_type_ot as 1
                    axe_distribution,
                    null as reference_company, -- Setting ireference_companyd_type_ot as NULL
                    volume,
                    qty,
                    nbr_delivery_points,
                    nbr_expected_days,
                    date_execution,
                    date_execution,
                    date_execution,
                    null as pod, -- Setting pod as NULL
                    null as estimated_days_count, -- Setting estimated_days_count as NULL
                    comments, 
                    distance, 
                    id_city, 
                    is_mutual, 
                    id_truck_category, 
                    date_order, 
                    date_execution, 
                    id_driver, 
                    id_vehicule,
                    date_order, -- Setting date_delivery as distribution date_order                   
                    1 as ot_status, -- Setting ot_status as 1
                    $currentUserId as createdby, -- User ID for createdby
                    $currentUserId as modifiedby, -- User ID for modifiedby
                    NOW() as created_at, -- Current timestamp for created_at
                    NOW() as updated_at -- Current timestamp for updated_at
                FROM distribution_header
                WHERE id_distribution_header = :id", ['id' => $validatedData['distribution_id']]
            );

            // Fetch the last inserted ID from ot_header table
            $newlyGeneratedId = DB::getPdo()->lastInsertId();

            // Insert into ot_lines based on distribution_lines data
            DB::statement("
                INSERT INTO ot_lines (id_ot_header, num_bl, name_delivery, qty_line, volume_line, line_order, created_at, updated_at)
                SELECT 
                    $newlyGeneratedId,
                    num_bl,
                    name_delivery,
                    qty_line,
                    volume_line,
                    line_order,
                    NOW() as created_at, -- Current timestamp for created_at
                    NOW() as updated_at -- Current timestamp for updated_at         
                FROM distribution_lines
                WHERE id_distribution_header = :id", ['id' => $validatedData['distribution_id']]
            );

            Vehicle::where('id_vehicle', $validatedData['vehicle_id'])->update(['status' => 'unavailable']);
            DB::commit();
            return response()->json(['message' => 'Votre distribution a été planifiée avec succès']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function planning()
    {
        $categories = DB::table('truck_category')
    ->leftJoin('vehicle_fleet', 'vehicle_fleet.id_truck_category', 'truck_category.id')
    ->selectRaw('IFNULL(COUNT(CASE WHEN vehicle_fleet.status = "available" THEN vehicle_fleet.id_vehicle END), 0) as truck_count, truck_category.truck_category')
    ->groupBy('truck_category.id')
    ->get();


        $driverMappings = DB::table('mapping_driver_vehicle')->get();
        $driverArray = DB::table('mapping_driver_vehicle')->pluck('id_driver');
        $DistributionsArray = DB::table('mapping_driver_vehicle')->pluck('id_distribution_header');
        $drivers = Driver::whereIn('id_driver', $driverArray)->get();
        $distributions = DistributionHeader::whereIn('id_distribution_header', $DistributionsArray)->get();

        return view('system.distributions.planning', compact('drivers', 'distributions', 'driverMappings', 'categories'));
    }


    public function delete_distribution(Request $request)
    {
        $header = DistributionHeader::findOrFail($request->id);
        // Delete related lines
        $header->distributionLines()->delete();
        // Then delete the header
        $header->delete();
        return response()->json(['message' => 'Distribution supprimée avec succès']);
    }

    public function edit_distribution_lines($id)
    {
        $lines = DistributionLine::where('id_distribution_header', $id)->get();
        return view('system.distributions.lines.edit', compact('lines'));
    }

    public function delete_distribution_lines(Request $request)
    {
        $line = DistributionLine::findorFail($request->id);

        $header = DistributionHeader::where('id_distribution_header', $line->id_distribution_header)->first();
        $header->update([
            'volume' => $header->volume - $line->volume_line,
            'qty' => $header->qty - $line->qty_line,
        ]);
        $line->delete();
        return response()->json(['message' => 'Ligne distribution supprimée avec succès', 'header' => $header]);
    }

    public function get_distribution_line(Request $request)
    {
        $lines = DistributionLine::findOrFail($request->id);
        return response()->json(['data' => $lines]);
    }

    public function update_distribution_line(Request $request)
    {
        try {
            DB::beginTransaction();
            $line = DistributionLine::findOrFail($request->line_id);
            if (!$line) {
                return response()->json(['error' => 'Ligne non trouvée'], 404);
            }
            $line->num_bl = $request->nbl;
            $line->name_delivery = $request->livrasion;
            $line->qty_line = $request->qte;
            $line->volume_line = $request->volume;
            $line->save();
            DB::commit();
            return response()->json(['message' => 'Ligne distribution mise à jour avec succès', 'line' => $line]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

}
