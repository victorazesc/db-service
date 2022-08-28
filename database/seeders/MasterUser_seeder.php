<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Seeder;

class MasterUser_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        return User::create([
            'first_name' => 'Victor',
            'last_name' => 'Azevedo',
            'document' => '10463808932',
            'profile_id' => '1',
            'email' => 'victorazesc@hotmail.com',
            'password' => Hash::make('corte99100wc'),
        ]);
    }
}
