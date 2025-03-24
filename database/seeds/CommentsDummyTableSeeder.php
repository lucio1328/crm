<?php

use Illuminate\Database\Seeder;

class CommentsDummyTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\User::class, 1)->create()->each(function ($user) {
            factory(App\Models\Comment::class, 1)->create([
                'user_id' => $user->id, // Lier le commentaire à un utilisateur
            ]);
        });
    }
}
