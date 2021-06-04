# CONTAINER

```
$container = new Container();

$container->bind('name', 'Жан-Клод');
echo $container->get('name');
echo '<br>';
echo $container->get('foo');

$container->singleton(User::class, User::class);

dump($container->get(User::class));
dump($container->get(User::class));
dump($container->get(User::class));

echo "<h2>Factory</h2>";
dump($container->factory(\App\Foo::class));
dump($container->factory(\App\Foo::class));
dump($container->factory(\App\Foo::class));

echo "<h2>Make</h2>";
dump($container->make(\App\Foo::class));
dump($container->make(\App\Foo::class));
dump($container->make(\App\Foo::class));


echo "<h2>Make object with params</h2>";
dump($post1 = $container->make(\App\Entity\Post::class, [
    'id' => 1,
    'title' => 'Hello',
    'content' => 'Hello world ...'
]));

$post1->setPublished(true);
dump($post1);


dump($container->make(\App\Entity\Post::class, [1, 'Hello', 'Hello world ...', true]));

dump($container->log());
dd($container->getBindings());
```