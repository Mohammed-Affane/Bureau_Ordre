<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Entite;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EntiteAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create CABINET
        $cabinetUser = $this->createUser('CABINET');
        $cabinet = Entite::create([
            'nom' => 'CABINET',
            'type' => 'division',
            'code' => null,
            'parent_id' => null,
            'responsable_id' => $cabinetUser->id,
        ]);

        // Create SG
        $sgUser = $this->createUser('SG');
        $sg = Entite::create([
            'nom' => 'SG',
            'type' => 'division',
            'code' => null,
            'parent_id' => $cabinet->id,
            'responsable_id' => $sgUser->id,
        ]);

        // Define divisions & their services
        $structure = [
            'DAI' => [],
            'DRHB' => ['DRHB/SGRH', 'DRHB/SBMC'],
            'DAS' => ['DAS/SSEC', 'DAS/SFRC'],
            'DAU' => ['DAU/SU', 'DAU/SCC'],
            'DCT' => ['DCT/SEC', 'DCT/SFLPC'],
            'DAT' => ['DAT/ST', 'DAT/SE', 'DAT/SGCN'],
            'DAEC' => ['DAEC/SEPCP', 'DAEC/SAEC'],
            'DPN' => [],
            'SSIC' => [],
            'SPS' => [],
            'SJC' => [],
        ];

        foreach ($structure as $division => $services) {
            // Create division under SG
            $divisionUser = $this->createUser($division);
            $divisionEntite = Entite::create([
                'nom' => $division,
                'type' => 'division',
                'code' => null,
                'parent_id' => $sg->id,
                'responsable_id' => $divisionUser->id,
            ]);

            foreach ($services as $service) {
                $serviceUser = $this->createUser($service);
                Entite::create([
                    'nom' => $service,
                    'type' => 'service',
                    'code' => null,
                    'parent_id' => $divisionEntite->id,
                    'responsable_id' => $serviceUser->id,
                ]);
            }
        }
    }

    private function createUser(string $name): User
    {
        return User::create([
            'name' => "{$name} Responsable",
            'email' => strtolower(str_replace('/', '_', $name)) . '@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
