<?php

use App\Announcement;
use App\Benefit;
use App\Organization;
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
        // $this->call(UserSeeder::class);
        $admin = factory('App\User')->create([
            'name' => 'Admin',
            'email' => 'admin@ngo.com'
        ]);
        $admin->organizations()->saveMany(
            factory(Organization::class, 5)->create()
                ->each(function ($organization) {
                    $organization->announcements()->saveMany(factory(Announcement::class, 3)->create());
                    $organization->benefits()->saveMany(factory(Benefit::class, 3)->create());
                })
        );
    }
}
