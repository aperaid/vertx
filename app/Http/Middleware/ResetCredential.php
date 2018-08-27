<?php

namespace App\Http\Middleware;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Ftp as Adapter;

use Closure;
use App\Model\Settings\ControllerDevice;
use App\Jobs\SendMessage;

class ResetCredential
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $controllers = ControllerDevice::all();
      foreach($controllers as $controller){
        $adapter = new Adapter(array(
          'host' => $controller->ip,
          'port' => 21,
          'username' => 'root',
          'password' => 'pass',
          'root' => '',
          'timeout' => 3,
        ));
        $filesystem = new Filesystem($adapter);
        try {
          //$filesystem->put('/mnt/apps/data/config/Formats', '1 /mnt/apps/data/config/formats/h10301_99.vff\n');
          //$filesystem->put('/mnt/apps/data/config/CardSets','');
          $filesystem->put('/mnt/apps/data/config/AccessDB','');
          $filesystem->put('/mnt/apps/data/config/IdentDB', '');
        } catch (\Exception $e) {
          $errors[] = ["Could not connect to ".$controller->name." IP: ".$controller->ip];
        }
        dispatch(new SendMessage('0107;0010;%'.$controller->ip));
				dispatch(new SendMessage('0108;0010;%'.$controller->ip));
				dispatch(new SendMessage('0012;0012;1;%'.$controller->ip));
				dispatch(new SendMessage('0012;0012;2;%'.$controller->ip));
      }
      if(isset($errors)){
        $request->e = $errors;
      }
      return $next($request);
    }
}
