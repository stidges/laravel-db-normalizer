<?php
require_once 'vendor/autoload.php';

use Stidges\LaravelDbNormalizer\Normalizer;

$normalizer = new Normalizer;

echo "
<pre>User::with('posts')->find(1);</pre>
<pre style='color: #999'>// or</pre>
<pre>
DB::table('users')
  ->join('posts', 'posts.user_id', '=', 'users.id')
  ->where('id', 1)
  ->first();
</pre>
<pre style='color: #999'>// Results in:</pre>";

$data = [
    [
        'id'         => 1,
        'name'       => 'Stidges',
        'username'   => 'stidges',
        'email'      => 'info@stidges.com',
        'created_at' => '2010-10-10 10:00:00',
        'updated_at' => '2010-10-10 10:00:00',
        'posts'      => [
            [ 'id' => 1, 'title' => 'My First Post', 'content' => 'Lorem ipsum', 'user_id' => 1 ],
            [ 'id' => 2, 'title' => 'My Second Post', 'content' => 'Lorem ipsum', 'user_id' => 1 ],
        ]
    ]
];

var_dump($normalizer->normalize($data[0]));
