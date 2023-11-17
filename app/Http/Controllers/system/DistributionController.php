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

        $distributions = DistributionHeader::all();

        // Get all drivers excluding those with IDs in the distribution table
        $drivers = Driver::whereNotIn('id_driver', $filteredDistributionDrivers)->get();

        // Get all vehicles excluding those with IDs in the distribution table
        $vehicles = Vehicle::whereNotIn('id_vehicle', $filteredDistributionVehicles)->get();
        return view('system.distributions.index', ['distributions' => $distributions, 'drivers' => $drivers, 'vehicles' => $vehicles]);
    }

    public function import(Request $request)
    {
        $file = $request->file('file');

        // Check if a file was uploaded
        if ($file) {
            // Perform the import logic using Maatwebsite/Laravel-Excel
            Excel::import(new DistributionImport, $file);

            // Redirect back with a success message
            return redirect()->route('distributions')->with('success', 'Data imported successfully.');
        }

        return redirect()->route('distributions')->with('error', 'Please select file.');
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
                    'flag_status' => 'pending'
                ]
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
            ->selectRaw('count(vehicle_fleet.id_vehicle) as truck_count, truck_category.truck_category')
            ->where('vehicle_fleet.status', 'available')
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
        return response()->json(['message' => 'DistributionHeader and related lines deleted']);
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
        return response()->json(['message' => 'Distribution line deleted successfully']);
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
                return response()->json(['error' => 'Line not found'], 404);
            }
            $line->num_bl = $request->nbl;
            $line->name_delivery = $request->livrasion;
            $line->qty_line = $request->qte;
            $line->volume_line = $request->volume;
            $line->save();
            DB::commit();
            return response()->json(['message' => 'Distribution line updated successfully', 'line' => $line]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

}
