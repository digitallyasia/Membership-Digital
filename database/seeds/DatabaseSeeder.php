<?php

use App\Announcement;
use App\Benefit;
use App\Notification;
use App\Organization;
use App\Plan;
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
        $plans = [
            [
                'name' => 'Free',
                'code' => 'free',
                'membership_number' => false,
                'import_membership_csv' => false,
                'card_customization' => false,
                'number_of_notifications' => 20,
                'number_of_announcements' => 20,
                'number_of_benefits' => 20,
                'number_of_members' => 100,
                'monthly_price' => 0,
                'yearly_price' => 0,
            ],
            [
                'name' => 'Basic',
                'code' => 'standard',
                'membership_number' => false,
                'import_membership_csv' => false,
                'card_customization' => false,
                'number_of_notifications' => 40,
                'number_of_announcements' => 40,
                'number_of_benefits' => 30,
                'number_of_members' => 1000,
                'monthly_price' => 100,
                'yearly_price' => 900,
            ],
            [
                'name' => 'Premium',
                'code' => 'premium',
                'membership_number' => true,
                'import_membership_csv' => true,
                'card_customization' => true,
                'number_of_notifications' => 65,
                'number_of_announcements' => 50,
                'number_of_benefits' => 40,
                'number_of_members' => 12000,
                'monthly_price' => 200,
                'yearly_price' => 1500,
            ],

        ];
        foreach ($plans as $plan) {
            Plan::Create($plan);
        }
        factory('App\User')->create([
            'name' => 'Admin',
            'email' => 'admin@membershipdigital.my'
        ]);
        $test_user  = factory('App\User')->create([
            'name' => 'Test User',
            'email' => 'user@membershipdigital.my'
        ]);
        $members = factory(App\User::class, 19)->create()->pluck('id')->toArray();
        $organization =  factory(Organization::class)->create([
            'name' => 'Organization',
            'email' => 'organization@membershipdigital.my'
        ]);
        $organization->announcements()->saveMany(factory(Announcement::class, 19)->create());
        $organization->benefits()->saveMany(factory(Benefit::class, 19)->create());
        $organization->notifications()->saveMany(factory(Notification::class, 19)->create());
        $organization->members()->attach($members, ['status' => 'accepted']);
        $organization->members()->attach($test_user->id, ['status' => 'accepted']);
    }
}
