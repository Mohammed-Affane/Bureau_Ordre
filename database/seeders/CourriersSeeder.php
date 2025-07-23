<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CourriersSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $totalRecords = (int) (env('SEED_COURRIERS_COUNT', 5000));
        $batchSize = 500;

        $expediteurIds = DB::table('expediteurs')->pluck('id')->toArray();
        $userIds = DB::table('users')->pluck('id')->toArray();
        $entiteIds = DB::table('entites')->pluck('id')->toArray();

        $types = ['arrive', 'depart', 'visa', 'decision', 'interne'];
        $priorites = ['normale', 'urgent', 'confidentiel', 'A reponse obligatoire'];

        $batches = ceil($totalRecords / $batchSize);
        $progressBarLength = 50;

        $startTime = microtime(true);

        for ($i = 0; $i < $batches; $i++) {
            $data = [];
            $recordsInBatch = ($i == $batches - 1) ? ($totalRecords - $i * $batchSize) : $batchSize;

            for ($j = 0; $j < $recordsInBatch; $j++) {
                $type = $types[array_rand($types)];

                $row = [
                    'reference_arrive'     => null,
                    'reference_bo'         => null,
                    'reference_visa'       => null,
                    'reference_dec'        => null,
                    'reference_depart'     => null,
                    'date_reception'       => null,
                    'date_depart'          => null,

                    'type_courrier'        => $type,
                    'objet'                => $this->generateObjet($type, $faker),
                    'fichier_scan'         => $faker->boolean(80) ? 'documents/' . $faker->word . '.pdf' : null,
                    'date_enregistrement'  => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                    'Nbr_piece'            => $faker->numberBetween(1, 10),
                    'priorite'             => $priorites[array_rand($priorites)],
                    'statut'               => 'en_attente',

                    'id_expediteur'        => !empty($expediteurIds) ? $faker->randomElement($expediteurIds) : null,
                    'id_agent_en_charge'   => !empty($userIds) ? $faker->randomElement($userIds) : null,
                    'entite_id'            => !empty($entiteIds) ? $faker->randomElement($entiteIds) : null,

                    'created_at'           => now(),
                    'updated_at'           => now(),
                ];

                switch ($type) {
                    case 'arrive':
                        $row['reference_arrive'] = $faker->numberBetween(1000, 9999);
                        $row['reference_bo']     = $faker->numberBetween(1000, 9999);
                        $row['date_reception']   = $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d');
                        break;

                    case 'visa':
                        $row['reference_arrive'] = $faker->numberBetween(1000, 9999);
                        $row['reference_visa']   = $faker->numberBetween(1000, 9999);
                        $row['date_reception']   = $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d');
                        break;

                    case 'decision':
                        $row['reference_dec']    = $faker->numberBetween(1000, 9999);
                        $row['date_depart']      = $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d');
                        break;

                    case 'depart':
                    case 'interne':
                        $row['reference_depart'] = $faker->numberBetween(1000, 9999);
                        $row['date_depart']      = $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d');
                        break;
                }

                $data[] = $row;
            }

            DB::table('courriers')->insert($data);

            // Progress + ETA
            $completed = ($i + 1) * $batchSize;
            $percent = min(100, ($completed / $totalRecords) * 100);
            $bars = floor(($percent / 100) * $progressBarLength);
            $progressBar = str_repeat('#', $bars) . str_repeat('-', $progressBarLength - $bars);

            $elapsed = microtime(true) - $startTime;
            $avgPerBatch = $elapsed / ($i + 1);
            $remainingBatches = $batches - ($i + 1);
            $etaSeconds = $avgPerBatch * $remainingBatches;

            printf(
                "\rProgress: [%s] %3d%% | ETA: %s",
                $progressBar,
                $percent,
                $this->formatSeconds($etaSeconds)
            );

            flush();
        }

        echo "\nSeeding completed in " . $this->formatSeconds(microtime(true) - $startTime) . "\n";
    }

    private function generateObjet(string $type, $faker): string
    {
        switch ($type) {
            case 'arrive':
                return 'Arrivée: ' . $faker->sentence(4);
            case 'depart':
                return 'Départ: ' . $faker->sentence(4);
            case 'visa':
                return 'Visa demandé: ' . $faker->sentence(4);
            case 'decision':
                return 'Décision prise: ' . $faker->sentence(4);
            case 'interne':
                return 'Communication interne: ' . $faker->sentence(4);
            default:
                return $faker->sentence(6);
        }
    }

    private function formatSeconds($seconds): string
    {
        $seconds = max(0, (int) round($seconds));
        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;

        if ($minutes > 0) {
            return sprintf('%dm %02ds', $minutes, $remainingSeconds);
        }

        return sprintf('%ds', $remainingSeconds);
    }
}
