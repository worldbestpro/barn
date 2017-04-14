<?php

use Illuminate\Database\Seeder;

class LittersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $litter1 = factory(\App\Models\Litter::class)->create(['kits_amount' => 10]);
        $litter2 = factory(\App\Models\Litter::class)->create(['kits_amount' => 10]);

        $buck = factory(\App\Models\RabbitBreeder::class)->create(['sex' => 'buck', 'kits' => 10, 'litters_count' => 1, 'live_kits' => 10]);
        $doe  = factory(\App\Models\RabbitBreeder::class)->create(['sex' => 'doe', 'kits' => 10, 'litters_count' => 1, 'live_kits' => 10]);

        $doe->litters()->saveMany([$litter1, $litter2]);
        $buck->litters()->saveMany([$litter1, $litter2]);

        $litter1Kits = factory(\App\Models\RabbitKit::class, 10)->create()->each(function ($kit) use ($litter1) {
            $kit->litter()->associate($litter1);
            $kit->update();
        });
        $litter2Kits = factory(\App\Models\RabbitKit::class, 10)->create()->each(function ($kit) use ($litter2) {
            $kit->litter()->associate($litter2);
            $kit->update();
        });
    }
}
