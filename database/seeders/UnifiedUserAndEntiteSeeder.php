<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Entite;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UnifiedUserAndEntiteSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $boRole = Role::firstOrCreate(['name' => 'bo']);
        $cabRole = Role::firstOrCreate(['name' => 'cab']);
        $sgRole = Role::firstOrCreate(['name' => 'sg']);
        $daiRole = Role::firstOrCreate(['name' => 'dai']);
        $chefDivisionRole = Role::firstOrCreate(['name' => 'chef_division']);

        // Create admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'password' => Hash::make('password')]
        );
        $adminUser->assignRole($adminRole);

        // Create BO user
        $boUser = User::firstOrCreate(
            ['email' => 'bo@example.com'],
            ['name' => 'BO User', 'password' => Hash::make('password')]
        );
        $boUser->assignRole($boRole);

        // Create CABINET
        $cabinetUser = User::firstOrCreate(
            ['email' => 'cabinet_responsable@example.com'],
            ['name' => 'CABINET Responsable', 'password' => Hash::make('password')]
        );
        $cabinetUser->assignRole($cabRole);

        $cabinetEntite = Entite::firstOrCreate(
            ['nom' => 'CABINET'],
            [
                'type' => 'division',
                'code' => null,
                'parent_id' => null,
                'responsable_id' => $cabinetUser->id,
            ]
        );

        // Create SG
        $sgUser = User::firstOrCreate(
            ['email' => 'sg_responsable@example.com'],
            ['name' => 'SG Responsable', 'password' => Hash::make('password')]
        );
        $sgUser->assignRole($sgRole);

        $sgEntite = Entite::firstOrCreate(
            ['nom' => 'SG'],
            [
                'type' => 'division',
                'code' => null,
                'parent_id' => $cabinetEntite->id,
                'responsable_id' => $sgUser->id,
            ]
        );

        // Define structure
        $structure = [
            'DAI' => [], // this gets `dai` role
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
            // Create division user & entity
            $divisionEmail = strtolower($division) . '_responsable@example.com';
            $divisionUser = User::firstOrCreate(
                ['email' => $divisionEmail],
                ['name' => "{$division} Responsable", 'password' => Hash::make('password')]
            );

            // Assign special role to DAI, otherwise chef_division
            if ($division === 'DAI') {
                $divisionUser->assignRole($daiRole);
            } else {
                $divisionUser->assignRole($chefDivisionRole);
            }

            $divisionEntite = Entite::firstOrCreate(
                ['nom' => $division],
                [
                    'type' => 'division',
                    'code' => null,
                    'parent_id' => $sgEntite->id,
                    'responsable_id' => $divisionUser->id,
                ]
            );

            foreach ($services as $service) {
                $serviceEmail = strtolower(str_replace('/', '_', $service)) . '_responsable@example.com';
                $serviceUser = User::firstOrCreate(
                    ['email' => $serviceEmail],
                    ['name' => "{$service} Responsable", 'password' => Hash::make('password')]
                );
                $serviceUser->assignRole($chefDivisionRole);

                Entite::firstOrCreate(
                    ['nom' => $service],
                    [
                        'type' => 'service',
                        'code' => null,
                        'parent_id' => $divisionEntite->id,
                        'responsable_id' => $serviceUser->id,
                    ]
                );
            }
        }
    }
}
