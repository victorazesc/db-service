<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

class Profiles_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Profile::create([
            'profile_name'      => 'Administrativo',
        ]);
        Profile::create([
            'profile_name'      => 'Qualidade',
        ]);
        Profile::create([
            'profile_name'      => 'Regulatório',
        ]);
        Profile::create([
            'profile_name'      => 'Gerência',
        ]);
    }
}
