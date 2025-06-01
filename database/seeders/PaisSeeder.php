<?php

namespace Database\Seeders;

use App\Models\Pais;
use Illuminate\Database\Seeder;

class PaisSeeder extends Seeder
{
    public function run()
    {
        $paises = [
            ['pais' => 'El Salvador', 'codigo' => 'SV', 'estado' => '1'],
            ['pais' => 'Estados Unidos', 'codigo' => 'US', 'estado' => '1'],
            ['pais' => 'México', 'codigo' => 'MX', 'estado' => '1'],
            // Agrega más países según necesites
        ];

        foreach ($paises as $pais) {
            Pais::create($pais);
        }
    }
}