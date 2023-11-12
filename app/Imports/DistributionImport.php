<?php

namespace App\Imports;

use App\Models\City;
use App\Models\Client;
use App\Models\TruckCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use App\Models\DistributionLine;
use App\Models\DistributionHeader;

class DistributionImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collections)
    {
        DB::beginTransaction();

        try {
            // Remove the first row and use it as header
            $headers = $collections->pull(0);

            // Loop through the remaining rows
            $collections->transform(function ($row) use ($headers) {
                // Combine the headers with the row items to create associative array
                return $headers->combine($row);
            });
            $lastMutualiseValue = null; // Tracks the last "Mutualisé ou NN" value
            $currentDistributionHeader = null;
            $i = 0;
            foreach ($collections->toArray() as $index => $collection) {
                if(isset($collection['Mutualisé ou NN']))
                    $client = Client::where('name_client', $collection['Mutualisé ou NN'])->first();
                if(isset($collection['ville']))
                    $city = City::where('city', $collection['ville'])->first();
                if(isset($collection['Type camion']))
                    $truck_category = TruckCategory::where('truck_category', 'Camion')->first();
                // Detect if "Mutualisé ou NN" value has changed (and is not null for the first row)
                    if ($lastMutualiseValue !== $collection['Mutualisé ou NN']) {
                    // Create a new DistributionHeader
                    $currentDistributionHeader = DistributionHeader::create([
                        'qty' => $collection['Total pièce'],
                        'volume' => $collection['Volume total chargé'],
                        'nbr_delivery_points' => $collection['Nbr Point de livraison'],
                        'nbr_expected_days' => $collection['Nbre Jours prévu '],
                        'comments' => $collection['Commentaire'] ?? '',
                        'distance' => $collection['Kilométrage'],
                        'is_mutual' => $collection['Mutualisé ou NN'],
                        'id_client' => $client->id_client,
                        'id_truck_category' => $truck_category->id,
                        'id_city' => $city->id_city,
                        'date_order' => intval($collection['Date Commande'] - 25569) * 86400,
//                        'date_execution' => '2023-11-01',
                        'code_distribution' => ++$i,
                        'id_type_distribution' => 1,
                        'axe_distribution' => $collection['Axe '],
//                        'id_driver' => 1,
//                        'id_vehicule' => 1,
//                        'date_delivery' => '2023-11-01',
                        'id_status_distribution' => 1,
                        'createdby' => Auth::id(),
                        'modifiedby' => Auth::id(),
                    ]);

                    // Update lastMutualiseValue to the current value
                    $lastMutualiseValue = $collection['Mutualisé ou NN'];
                }

                // Assuming that not all rows are headers, check if the row is a line item
                DistributionLine::create([
                    'id_distribution_header' => $currentDistributionHeader->id_distribution_header,
                    'num_bl' => $collection['N°BL'],
                    'name_delivery' => $collection['Nom livraison 1'],
                    'qty_line' => $collection['Quantités à préparer'],
                    'volume_line' => $collection['Volume commande'],
                    'line_order' => $collection['Ordre Livraison'],
                ]);
            }
            DB::commit();

        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }
    }
}
