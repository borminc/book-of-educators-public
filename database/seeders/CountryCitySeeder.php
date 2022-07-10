<?php

namespace Database\Seeders;

use Exception;
use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CountryCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $con = config('database.default');

        $sqlFilePath = null;

        if ($con === 'mysql') {
            $sqlFilePath = base_path('database/seeders/data/country_city_data_mysql.sql');
        } else if ($con === 'pgsql') {
            $sqlFilePath = base_path('database/seeders/data/country_city_data_pgsql.sql');
        }

        if (env('DEPLOYED_ON') === 'heroku') {
            $sqlFilePath = null;
        }

        if ($sqlFilePath && file_exists($sqlFilePath)) {
            $file = file_get_contents($sqlFilePath);
            DB::unprepared($file);
        } else {
            $this->importRawData();
        }
    }

    /**
     * Import from json file (slower)
     *
     * @return void
     */
    public function importRawData()
    {
        $file_path = base_path('database/seeders/data/country_city_data.json');
        $data = json_decode(file_get_contents($file_path), true);

        if (env('DEPLOYED_ON') === 'heroku') {
            $data = [
                'Cambodia' => $data['Cambodia']
            ];
        }

        foreach ($data as $_country => $_cities) {
            $country = Country::firstOrCreate([
                'name' => $_country,
            ]);

            foreach ($_cities as $_city) {
                City::create([
                    'name' => $_city,
                    'country_id' => $country->id,
                ]);
            }
        }
    }
}
