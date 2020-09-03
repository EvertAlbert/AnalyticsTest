<?php

use App\Action;
use Illuminate\Database\Seeder;

class ActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Action::create([
            'id' => 1,
            'name' => 'connect']);
        Action::create([
            'id' => 2,
            'name' => 'disconnect']);
        Action::create([
            'id' => 3,
            'name' => 'language']);
        Action::create([
            'id' => 4,
            'name' => 'age']);
        Action::create([
            'id' => 5,
            'name' => 'productClick']);
        Action::create([
            'id' => 6,
            'name' => 'rate']);
        Action::create([
            'id' => 7,
            'name' => 'viewTime']);
    }
}
