<?php

namespace Modules\DigitalSignature\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DigitalSignatureDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(DigitalSignatureRoleAndPermissionsTableSeeder::class);
    }
}
