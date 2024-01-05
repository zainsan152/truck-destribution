<?php

namespace App\Http\Controllers\system;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlanifiedRequest;
use App\Models\OtHeader;
use App\Models\OtLine;
use App\Models\Driver;
use App\Models\TruckCategory;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use SebastianBergmann\Diff\Line;

class OtController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Get all OT records to extract driver IDs
        $otDrivers = OtHeader::pluck('id_driver')->all();
        $filteredotDrivers = array_filter($otDrivers, function ($value) {
            return $value !== null;
        });

        // Get all OTs records to extract vehicle IDs
        $otVehicles = OtHeader::pluck('id_vehicule')->all();
        $filteredotVehicles = array_filter($otVehicles, function ($value) {
            return $value !== null;
        });

        $header_ids = DB::table('mapping_driver_vehicle')->pluck('id_distribution_header')->toArray();
        $ots = OtHeader::whereIn('numero_ot', $header_ids)->get();

        // Get all drivers excluding those with IDs in the OT table
        $drivers = Driver::whereIn('id_driver', $filteredotDrivers)->get();

        // Get all vehicles excluding those with IDs in the OT table
        $vehicles = Vehicle::whereIn('id_vehicle', $filteredotVehicles)->get();
        return view('system.ots.index', ['ots' => $ots, 'drivers' => $drivers, 'vehicles' => $vehicles]); 

        //return view('system.ots.index');
    }

    public function ot_details($ot_id)
    {
        $ot = OtHeader::where('id_ot_header', $ot_id)->with('otLines', 'otType')->first();
        return response()->json(['ot' => $ot]);
    }



    public function get_ot_line(Request $request)
    {
        $lines = OtLine::findOrFail($request->id);
        return response()->json(['data' => $lines]);
    }


}
