// Ajouter dans $routeMiddleware
protected $routeMiddleware = [
    // ... autres middlewares
    'jwt.auth' => \App\Http\Middleware\JwtMiddleware::class,
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
];