<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CourriersSeeder extends Seeder
{
public function run()
    {
        $faker = Faker::create();

        // Get existing IDs for relationships
        $expediteurIds = DB::table('expediteurs')->pluck('id')->toArray();
        $userIds = DB::table('users')->pluck('id')->toArray();
        $entiteIds = DB::table('entites')->pluck('id')->toArray();

        $types = ['arrive', 'depart', 'visa', 'decision', 'interne'];
        $priorites = ['normale', 'urgent', 'confidentiel', 'A reponse obligatoire'];
        $statuts = ['en_attente', 'en_cours', 'arriver', 'cloture', 'archiver'];

        $batchSize = 500; // Smaller batch size for 5k records
        $totalRecords = 5000;
        $batches = ceil($totalRecords / $batchSize);

        for ($i = 0; $i < $batches; $i++) {
            $data = [];
            $recordsInBatch = ($i == $batches - 1) ? $totalRecords % $batchSize : $batchSize;
            
            for ($j = 0; $j < $recordsInBatch; $j++) {
                $type = $types[array_rand($types)];
                $hasReference = $faker->boolean(70); // 70% chance to have a reference
                
                $data[] = [
                    'reference_arrive' => $type === 'arrive' && $hasReference ? $faker->numberBetween(1000, 9999) : null,
                    'reference_bo' => $type === 'arrive' && $hasReference ? $faker->numberBetween(1000, 9999) : null,
                    'reference_visa' => $type === 'visa' && $hasReference ? $faker->numberBetween(1000, 9999) : null,
                    'reference_dec' => $type === 'decision' && $hasReference ? $faker->numberBetween(1000, 9999) : null,
                    'reference_depart' => $type === 'depart' && $hasReference ? $faker->numberBetween(1000, 9999) : null,
                    
                    'type_courrier' => $type,
                    'objet' => $faker->sentence(6),
                    'date_reception' => $type !== 'depart' ? $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d') : null,
                    'date_depart' => $type === 'depart' ? $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d') : null,
                    'fichier_scan' => $faker->boolean(80) ? 'documents/' . $faker->word . '.pdf' : null,
                    'date_enregistrement' => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                    'Nbr_piece' => $faker->numberBetween(1, 10),
                    'priorite' => $priorites[array_rand($priorites)],
                    'statut' => $statuts[array_rand($statuts)],
                    
                    'id_expediteur' => !empty($expediteurIds) ? $faker->randomElement($expediteurIds) : null,
                    'id_agent_en_charge' => !empty($userIds) ? $faker->randomElement($userIds) : null,
                    'entite_id' => !empty($entiteIds) ? $faker->randomElement($entiteIds) : null,
                    
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            DB::table('courriers')->insert($data);
            
            // Output progress
            $progress = round(($i + 1) * $batchSize / $totalRecords * 100);
            echo "Progress: {$progress}%\n";
        }
    }
}
