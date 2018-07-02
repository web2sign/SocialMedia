<?php

namespace Modules\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthAdmin
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle(Request $request, Closure $next)
  {
    if( !$request->session()->get('cpanel')->is_logged ) {
        return redirect( url('admin/login') );
    }

    $_module = explode(".", Route::current()->getName() );
    $is_module = isset($_module[0]) ? ( ($_module[0] == "module") ? true : false ) : false;

    $_groups = [];
    if( $is_module ) {
        $module['name'] = $_module[1];
        $module['method'] = isset($_module[2]) ? $_module[2] : 'read';
        foreach(session()->get('cpanel')->user->usergroup as $group) {
            foreach($group['modules'] as $val) {
                if( $module['name'] == $val['module'] && $val[ $module['method'] ] == 1) {
                    $_groups[] = $val;
                }                    
            }
        }
    } else {
        // make the module visible to all if its not declared as module in the route
        $_groups[] = [];
    }

    // let's redirect the user to dashboard when group of user doesn't have the right to view the module
    if(!count($_groups) && $is_module) {
        return redirect()->to( url('admin/dashboard') );
    }


    return $next($request);
  }
}
