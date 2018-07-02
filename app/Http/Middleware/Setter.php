<?php 
namespace App\Http\Middleware;

use Closure,Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Session\SessionManager;

class Setter
{

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @param  string|null  $guard
   * @return mixed
   */
  public function handle($request, Closure $next, $guard = null) {

    //$request->request->add(['testing' => 'test' ]);
    // set a default admin session
    if( !$request->session()->get('cpanel') ) {
      $request->session()->put('cpanel', (object) [
        'is_logged'     => false,
        'user'          => (object) [
            'id'            => 0,
        ]
      ]);
      $request->session()->save();
    }

    return $next($request);
  }

}
