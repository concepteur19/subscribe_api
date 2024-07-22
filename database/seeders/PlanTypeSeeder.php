<?php

namespace Database\Seeders;

use App\Models\PlanType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $planTypes = [
            // netflix
            [
                'default_subscription_id' => 1,
                'type' => 'Mobile',
                'amount' => 2.99
            ],
            [
                'default_subscription_id' => 1,
                'type' => 'Basic',
                'amount' => 3.99
            ],
            [
                'default_subscription_id' => 1,
                'type' => 'Standart',
                'amount' => 7.99
            ],
            [
                'default_subscription_id' => 1,
                'type' => 'Premium',
                'amount' => 9.99
            ],

            // jio
            [
                'default_subscription_id' => 2,
                'type' => 'Premium',
                'amount' => 0.35
            ],
            [
                'default_subscription_id' => 2,
                'type' => 'Family',
                'amount' => 1.07
            ],

            // spotify
            [
                'default_subscription_id' => 3,
                'type' => 'Students',
                'amount' => 5.99
            ],
            [
                'default_subscription_id' => 3,
                'type' => 'Staff',
                'amount' => 10.99
            ],
            [
                'default_subscription_id' => 3,
                'type' => 'Duo',
                'amount' => 14.99
            ],
            [
                'default_subscription_id' => 3,
                'type' => 'Family',
                'amount' => 17.99
            ],


            // hotstar
            [
                'default_subscription_id' => 4,
                'type' => 'VIP annual',
                'amount' => 5.40
            ],
            [
                'default_subscription_id' => 4,
                'type' => 'VIP mensual',
                'amount' => 20.30
            ],

            // youtube premium
            [
                'default_subscription_id' => 5,
                'type' => 'Student',
                'amount' => 7.99
            ],
            [
                'default_subscription_id' => 5,
                'type' => 'Particular',
                'amount' => 12.99
            ],
            [
                'default_subscription_id' => 5,
                'type' => 'Family',
                'amount' => 23.99
            ],

            // prime
            [
                'default_subscription_id' => 6,
                'type' => 'Access',
                'amount' => 6.99
            ],
            [
                'default_subscription_id' => 6,
                'type' => 'Student',
                'amount' => 7.49
            ],
            [
                'default_subscription_id' => 6,
                'type' => 'Prime Monthly',
                'amount' => 14.99
            ],
            [
                'default_subscription_id' => 6,
                'type' => 'Prime Annual',
                'amount' => 139
            ],

        ];

        foreach ($planTypes as $planType) {
            PlanType::create($planType);
        }
    }
}
