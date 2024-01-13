<?php

namespace App\Imports;

use App\Models\Arrival;
use App\Models\ArrivalLine;
use App\Models\ArrivalLineType;
use App\Models\ArrivalType;
use App\Models\City;
use App\Models\Client;
use App\Models\DistributionHeader;
use App\Models\DistributionLine;
use App\Models\TruckCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class ArrivalImport implements ToCollection
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
            foreach ($collections->toArray() as $index => $collection) {
                $client = Client::where('name_client', $collection['Client'])->first();
                $type = ArrivalType::where('type', $collection['Type'])->first();
                $lineType = ArrivalLineType::where('type', $collection['packaging type'])->first();
                $city = City::where('city', $collection['POD'])->first();
                $eta = convertExcelDateToTimestamp($collection['ETA']);
                $ata = convertExcelDateToTimestamp($collection['ATA']);
                $date_bae = convertExcelDateToTimestamp($collection['Date BAE Prévisionnelle']);
                $date_maga = convertExcelDateToTimestamp($collection['Date magasinage']);
                $date_sur = convertExcelDateToTimestamp($collection['Date Surestaries']);

                $existingArrival = Arrival::where('client_id', $client->id_client)->where('dossier_client', $collection['Dossier Client'])->first(); //check for avoiding duplicate entries

                if ($collection['ID']) {
                    if (!$existingArrival) {
                        $arrival = Arrival::create([
                            'client_id' => $client->id_client,
                            'arrival_type_id' => $type->id,
                            'status' => 'cree',
                            'dossier_tegic' => $collection['Dossier Tegic'],
                            'dossier_client' => $collection['Dossier Client'],
                            'shipping_compagnie' => $collection['Shipping Compagnie'],
                            'city_id' => $city->id_city,
                            'eta' => $eta,
                            'ata' => $ata,
                            'lieu_de_chargement' => $collection['Lieu de chargement'],
                            'lieu_de_dechargement' => $collection['Lieu de déchargement'],
                            'lieu_de_restitution' => $collection['Lieu de Restitution'],
                            'date_bae_Previsionnelle' => $date_bae,
                            'date_magasinage' => $date_maga,
                            'date_surestaries' => $date_sur,
                            'created_by' => Auth::id(),
//                    'modifiedby' => Auth::id(),
                        ]);

                        $arrival_line = ArrivalLine::create([
                            'arrival_id' => $arrival->id,
                            'numero' => $collection['Numéro'],
                            'arrival_line_type_id' => $lineType->id,
                            'nb_de_pieces' => $collection['Nb de pièces'],
                            'poids' => $collection['Poids'],
                            'volume' => $collection['Volume'],
                        ]);

                    }
                }
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }
    }
}
