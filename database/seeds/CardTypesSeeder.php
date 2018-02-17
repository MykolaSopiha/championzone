<?php

use Illuminate\Database\Seeder;

class CardTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getData() as $role) {

            $item = new \App\Role();
            $item->name = $role;
            $item->save();
        }
    }

    public function getData()
    {
        return [
            'admin',
            'mediabuyer',
            'accountant',
            'farmer',
        ];
    }
}
