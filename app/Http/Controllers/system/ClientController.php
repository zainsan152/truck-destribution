<?php

namespace App\Http\Controllers\system;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Models\City;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index()
    {
        $cities = City::all();
        $clients = Client::all();
        return view('system.clients.index', get_defined_vars());
    }

    public function store(ClientRequest $request)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validated();
            // Get the last used client code from the database
            $lastClient = Client::orderBy('id_client', 'desc')->first();

            // Generate the new client code by incrementing the last one
            $newCode = ($lastClient) ? ++$lastClient->code_client : 'C001';
            $client = Client::create([
                'code_client' => $newCode,
                'name_client' => $validatedData['client_name'],
                'adresse' => $validatedData['address'],
                'id_city' => $validatedData['city_id'],
            ]);
            $client->load('city');
            DB::commit();
            return response()->json(['message' => 'Client added successfully', 'client' => $client]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function show(Request $request)
    {
        $client = Client::findOrFail($request->id);
        return response()->json(['data' => $client]);
    }

    public function update(ClientRequest $request)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validated();

            // Retrieve the client record by its ID
            $client = Client::find($request->client_id);

            if (!$client) {
                return response()->json(['error' => 'Client not found'], 404);
            }

            // Update the client attributes
            $client->name_client = $validatedData['client_name'];
            $client->adresse = $validatedData['address'];
            $client->id_city = $validatedData['city_id'];

            $client->save(); // Save the changes to the database

            $client->load('city');

            DB::commit();
            return response()->json(['message' => 'Client updated successfully', 'client' => $client]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request)
    {
        $client = Client::findorFail($request->id);
        $client->delete();
        return response()->json(['message' => 'Client deleted successfully']);
    }

}
