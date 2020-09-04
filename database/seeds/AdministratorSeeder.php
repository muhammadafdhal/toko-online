<?php

use Illuminate\Database\Seeder;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $administrator = new \App\User;
        $administrator->username = "administrator";
        $administrator->name = "Site Administrator";
        $administrator->email = "administrator@larashop.com";
        $administrator->roles = json_encode(["ADMIN"]);
        $administrator->password = \Hash::make("larashop");
        $administrator->avatar = "tidak-ada-file.png";
        $administrator->address = "Panam, Pekanbaru, Riau";
        $administrator->phone = "082323232323";
        
        $administrator->save();
        $this->command->info("User Admin berhasil ditambahkan");
    }
}
