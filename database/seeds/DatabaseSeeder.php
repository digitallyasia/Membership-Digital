<?php

use App\Announcement;
use App\Benefit;
use App\Notification;
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
        factory('App\User')->create([
            'name' => 'Admin',
            'email' => 'admin@membershipdigital.my'
        ]);
        $members = factory(App\User::class, 20)->create()->pluck('id')->toArray();
        $organization =  factory(Organization::class)->create([
            'name' => 'Organization',
            'email' => 'organization@membershipdigital.my'
        ]);
        $organization->announcements()->saveMany(factory(Announcement::class, 20)->create());
        $organization->benefits()->saveMany(factory(Benefit::class, 20)->create());
        $organization->notifications()->saveMany(factory(Notification::class, 20)->create());
        $organization->members()->attach($members, ['status' => true]);
    }
}
