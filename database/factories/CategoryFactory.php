<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    $name = $faker->unique()->word;
    $slug = str_slug($name, '-');
    return [
        'name' => $name,
        'slug' => $slug
    ];
});
