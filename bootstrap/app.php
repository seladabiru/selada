<?php  
  
use Illuminate\Foundation\Application;  
use Illuminate\Foundation\Configuration\Exceptions;  
use Illuminate\Foundation\Configuration\Middleware;  
  
return Application::configure(basePath: dirname(__DIR__))  
    ->withRouting(  
        web: __DIR__.'/../routes/web.php',  
        commands: __DIR__.'/../routes/console.php',  
        health: '/up',  
    )  
    ->withMiddleware(function (Middleware $middleware) {  
        $middleware->redirectGuestsTo('/login');  
        $middleware->redirectUsersTo(function () {  
            if (auth()->check() && auth()->user()->isAdmin()) {  
                return route('admin.dashboard');  
            }  
            return route('songs.index');  
        });  
        $middleware->alias([  
            'admin' => \App\Http\Middleware\AdminMiddleware::class,  
        ]);  
    })  
    ->withExceptions(function (Exceptions $exceptions): void {  
        $exceptions->render(function (Illuminate\Session\TokenMismatchException $e) {  
            return redirect()->route('login')->with('message', 'Sesi Anda telah berakhir. Silakan login kembali.');  
        });  
    })->create();  
