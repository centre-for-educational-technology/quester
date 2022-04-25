<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        if (config('app.env') === 'local') {

            $construct_id = DB::table('constructs')->insertGetId([
                'name' => 'Pingutuse regulatsioon',
                'objective' => 'Mind huvitab, kuidas õpilased täna pingutasid tunnis',
            ]);

            DB::table('statements')->insert([
                'text' => 'Mul on selles tunnis õppides nii laisk või igav tunne, et ma jätan planeeritud ülesande lõpetamata.',
                'construct_id' => $construct_id,
                'position' => '1',
            ]);

            DB::table('statements')->insert([
                'text' => 'Ma näen palju vaeva selleks, et tunnis hästi hakkama saada, isegi kui mulle ei meeldi see, mida me teeme.',
                'construct_id' => $construct_id,
                'position' => '2',
            ]);

            DB::table('statements')->insert([
                'text' => 'Kui õppematerjalid on keerulised, siis ma annan alla või teen ainult lihtsad osad.',
                'construct_id' => $construct_id,
                'position' => '3'
            ]);

            DB::table('statements')->insert([
                'text' => 'Isegi kui õppematerjalid on igavad ja ebahuvitavad, suudan ma teha ülesanded lõpuni.',
                'construct_id' => $construct_id,
                'position' => '4'
            ]);

        }
    }
}
