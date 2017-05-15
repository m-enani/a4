<?php

use Illuminate\Database\Seeder;
use App\Rank;

class RanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ranks = array
            (
                array("Want it!", 4),
                array("Wouldn't mind", 3),
                array("If I must", 2),
                array("Count me out", 1)
            );

        foreach ($ranks as $rankInfo) {
            $rank = new Rank();
            $rank->description = $rankInfo[0];
            $rank->value = $rankInfo[1];
            $rank->save();
        }

    }
}
