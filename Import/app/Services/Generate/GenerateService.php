<?php

namespace App\Services\Generate;
use App\Models\User;
use App\Models\Industry;
use Faker\Factory as Faker;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;

class GenerateService
{
   
    public function generateClients($count = 10)
    {
         
        $faker = Faker::create();

         
        $userIds = User::pluck('id')->toArray();

         
        $industryIds = Industry::pluck('id')->toArray();

        if (empty($userIds) || empty($industryIds)) {
            return;
        }

         for ($i = 0; $i < $count; $i++) {
            DB::table('clients')->insert([
                'external_id'   => Uuid::uuid4()->toString(),
                'address'       => $faker->streetAddress,
                'zipcode'       => $faker->postcode,
                'city'          => $faker->city,
                'company_name'  => $faker->company,
                'vat'           => $faker->optional()->vat,
                'company_type'  => $faker->randomElement(['SARL', 'SAS', 'EURL', 'Auto-Entrepreneur']),
                'client_number' => $faker->randomNumber(8, true),
                'user_id'       => $faker->randomElement($userIds),
                'industry_id'   => $faker->randomElement($industryIds),
                'deleted_at'    => null, // Pas de suppression logique par défaut
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        echo "✅ $count clients ont été créés avec succès !";
    }
}
