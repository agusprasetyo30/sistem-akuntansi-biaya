<?php

namespace Database\Seeders;

use App\Models\Management_Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CompanySeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ManagementRoleSeeder::class);
        $this->call(KategoriMaterialSeeder::class);
        $this->call(KategoriProdukSeeder::class);
        $this->call(GroupAccountSeeder::class);
        //        $this->call(MaterialSeeder::class);
        $this->call(CostCenterSeeder::class);
        $this->call(PlantSeeder::class);
        $this->call(VersionAsumsiSeeder::class);
        $this->call(AsumsiUmumSeeder::class);
        $this->call(RegionSeeder::class);
        $this->call(GroupAccountFCSeeder::class);
        $this->call(GeneralLedgerAccountFCSeeder::class);
        $this->call(GLAccountSeeder::class);
        $this->call(KategoriBalansSeeder::class);
        $this->call(TempSimulasiProyeksiSeeder::class);
        //        $this->call(MappingMaterialBallansSeeder::class);
        $this->call(FeatureSeeder::class);
    }
}
