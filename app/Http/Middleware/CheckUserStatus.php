<?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Support\Facades\Auth;

    class CheckUserStatus
    {
        public function handle($request, Closure $next)
        {
            // Controlla se l'utente è autenticato
            if (Auth::check()) {
                // Controlla se l'utente è disabilitato
                if (Auth::user()->is_disabled) {
                    // Se l'utente è disabilitato, disconnettilo e reindirizzalo
                    Auth::logout();
                    return redirect()->route('login')->with('error', 'Il tuo account è stato disabilitato. Contatta l\'amministratore.');
                }
            }

            return $next($request);
        }
    }
