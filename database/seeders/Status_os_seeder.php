<?php

namespace Database\Seeders;

use App\Models\Status_os;
use Illuminate\Database\Seeder;

class Status_os_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status_os::create([
            'status'      => 'Orçamento',
        ]);
        Status_os::create([
            'status'      => 'Aberto',
        ]);
        Status_os::create([
            'status'      => 'Em Andamento',
        ]);
        Status_os::create([
            'status'      => 'Finalizado',
        ]);
        Status_os::create([
            'status'      => 'Cancelado',
        ]);
    }
}
