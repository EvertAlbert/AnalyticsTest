<?php

use App\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Language::create([
            'name_short' => 'NL',
            'name_full' => 'Dutch']);
        Language::create([
            'name_short' => 'FRA',
            'name_full' => 'French']);
        Language::create([
            'name_short' => 'ENG',
            'name_full' => 'English']);
    }
}
