<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\SparePart;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            'Pendurthi',
            'Kothavalasa',
            'Anakapelly',
            'Chinna Musallwada',
            'NAD Junction',
            'Marripalem',
            'Gajuwaka',
            'Koramanapalalem',
            'Duvvada',
            'Kancherapalem',
            'RTC Complex',
            'Maddipalem',
            'Madhuruwada',
            'Endada',
            'Hnumanthwada',
            'Akkayapalam',
            'PM Palem',
            'Allipuram',
            'Siripuram',
            'Shulanager',
            'Peddawaltair',
            'Chinnawaltair',
        ];

        foreach ($areas as $area) {
            Area::firstOrCreate(['name' => $area]);
        }

        $spareParts = [
            'Sediment',
            'Spun',
            'Post/Carbon',
            'Membrane Housing',
            'Pump',
            'Float',
            'Pipe',
            'Carbon',
            'Tap',
            'Membrane',
            'SV',
            'SMPS',
            'Diveter Wall',
        ];

        foreach ($spareParts as $part) {
            SparePart::firstOrCreate(['name' => $part]);
        }
    }
}
