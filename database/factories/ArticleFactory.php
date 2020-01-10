<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Article;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    $title = $faker->sentence($nbWords = 3, $variableNbWords = true);
    $slug = str_slug($title, '-');
    return [
        'title' => $title,
        'details' => $faker->paragraph,
        'user_id'=> 7,
        'approve_status' => 1,
        'paid_status' => 1,
        'slug' => $slug
    ];
});
