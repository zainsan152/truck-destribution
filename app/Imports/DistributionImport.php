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
use DateTime;


class DistributionImport implements ToCollection
{
    /**
     * @param Collection $collection
     */

    protected $missingClients;

    public function __construct(&$missingClients)
    {
        $this->missingClients = &$missingClients;
    }

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
//            $lastMutualiseValue = null; // Tracks the last "Mutualisé ou NN" value
            $currentDistributionHeader = null;
            $latestCode = DistributionHeader::latest('id_distribution_header')->first();
            if ($latestCode) {
                $i = $latestCode->code_distribution;
            } else {
                $i = 0;
            }
            foreach ($collections->toArray() as $index => $collection) {
                $existingHeader = DistributionHeader::where('axe_distribution', $collection['Axe '])->where('is_mutual', $collection['Mutualisé ou NN'])->first();
                if (isset($collection['Mutualisé ou NN']))
                    $client = Client::where('name_client', $collection['Mutualisé ou NN'])->first();
                if (isset($collection['ville']))
                    $city = City::where('city', $collection['ville'])->first();
                if (isset($collection['Type camion']))
                    $truck_category = TruckCategory::where('truck_category', 'Camion')->first();
                // Detect if "Mutualisé ou NN" value has changed (and is not null for the first row)
                if ($client) {
                    if (!$existingHeader/*&& $lastMutualiseValue !== $collection['Mutualisé ou NN']*/) {
                        
                        //$dateOrder = new DateTime($collection['Date Commande']);
                        $excelDate = $collection['Date Commande'];
                        // Excel stores dates as the number of days since 1900-01-01 (Excel's base date)
                        $unixTimestamp = ($excelDate - 25569) * 86400; // Convert Excel date to Unix timestamp

                        $dateOrder = new DateTime();
                        $dateOrder->setTimestamp($unixTimestamp);

                        $formattedDateOrder = $dateOrder->format('Y-m-d'); // Format the date as per your database field's format
                                      
                        // Create a new DistributionHeader
                        $existingHeader = DistributionHeader::create([
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
                            //$table->dateTime('start_at');
                            
                            'date_order' => $formattedDateOrder,
                            //'date_order' => $dateOrder->format('Y-m-d'), // Format the DateTime object as per your database field's format
                            //'date_order' => dateTime($collection['Date Commande']),
                            //'date_order' => intval($collection['Date Commande'] - 25569) * 86400,
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

                        // Adding lines in the db
                        foreach ($collections as $lineItem) {
                            if ($lineItem['Mutualisé ou NN'] == $collection['Mutualisé ou NN'])
                                DistributionLine::create([
                                    'id_distribution_header' => $existingHeader->id_distribution_header,
                                    'num_bl' => $lineItem['N°BL'],
                                    'name_delivery' => $lineItem['Nom livraison 1'],
                                    'qty_line' => $lineItem['Quantités à préparer'],
                                    'volume_line' => $lineItem['Volume commande'],
                                    'line_order' => $lineItem['Ordre Livraison'],
                                ]);
                        }
                        // Update lastMutualiseValue to the current value
//                    $lastMutualiseValue = $collection['Mutualisé ou NN'];
                    }
                } else {
                    $this->missingClients[] = $collection['Mutualisé ou NN'];
                }
            }
            DB::commit();

        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }
    }
}
