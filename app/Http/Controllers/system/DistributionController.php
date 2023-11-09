<?php

namespace App\Http\Controllers\system;

use App\Http\Controllers\Controller;
use App\Models\DistributionHeader;
use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DistributionImport;

class DistributionController extends Controller
{
    public function index()
    {
        $distributions = DistributionHeader::all();
        $drivers = Driver::all();
        $vehicles = Vehicle::all();
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
    }

    public function details($distribution_id)
    {
        $distribution = DistributionHeader::where('id_distribution_header', $distribution_id)->with('distributionLines', 'distributionType')->first();
        return response()->json(['distribution' => $distribution]);
    }
}
