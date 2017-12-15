<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\HoroscopeCategory;

class HoroscopeCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Aries',
                'slug' => 'aries',
                'from_date' => 'Mar 21',
                'to_date' => 'Apr 20',
            ],
            [
                'name' => 'Taurus',
                'slug' => 'taurus',
                'from_date' => 'Apr 21',
                'to_date' => 'May 21',
            ],
            [
                'name' => 'Gemini',
                'slug' => 'gemini',
                'from_date' => 'May 22',
                'to_date' => 'Jun 21',
            ],
            [
                'name' => 'Cancer',
                'slug' => 'cancer',
                'from_date' => 'Jun 22',
                'to_date' => 'Jul 22',
            ],
            [
                'name' => 'Leo',
                'slug' => 'leo',
                'from_date' => 'Jul 23',
                'to_date' => 'Aug 22',
            ],
            [
                'name' => 'Virgo',
                'slug' => 'virgo',
                'from_date' => 'Aug 23',
                'to_date' => 'Sep 23',
            ],
            [
                'name' => 'Libra',
                'slug' => 'libra',
                'from_date' => 'Sep 24',
                'to_date' => 'Oct 23',
            ],
            [
                'name' => 'Scorpio',
                'slug' => 'scorpio',
                'from_date' => 'Oct 24',
                'to_date' => 'Nov 22',
            ],
            [
                'name' => 'Sagittarius',
                'slug' => 'sagittarius',
                'from_date' => 'Nov 23',
                'to_date' => 'Dec 21',
            ],
            [
                'name' => 'Capricorn',
                'slug' => 'capricorn',
                'from_date' => 'Dec 22',
                'to_date' => 'Jan 20',
            ],
            [
                'name' => 'Aquarius',
                'slug' => 'aquarius',
                'from_date' => 'Jan 21',
                'to_date' => 'Feb 19',
            ],
            [
                'name' => 'Pisces',
                'slug' => 'pisces',
                'from_date' => 'Feb 20',
                'to_date' => 'Mar 20',
            ]
        ];
        DB::table('horoscope_categories')->insert($data);
    }
}
