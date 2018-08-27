<?php

namespace App\Http\Middleware;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Ftp as Adapter;
use Illuminate\Support\Facades\Storage;

use Closure;
use App\Model\Settings\ControllerDevice;

class SyncFile
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
      if(isset($request->iid)){
        $controller = ControllerDevice::find($request->iid);
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
          $filesystem->put('/mnt/apps/data/config/AccessGroups',Storage::get('config/AccessGroups'));
					$filesystem->put('/mnt/apps/data/config/CardSets',Storage::get('config/CardSets'));
          $filesystem->put('/mnt/apps/data/config/CommCfg',Storage::get('config/CommCfg'));
          $filesystem->put('/mnt/apps/data/config/DoorGroups',Storage::get('config/DoorGroups'));
          //$filesystem->put('/mnt/apps/data/config/Elevator',Storage::get('config/Elevator'));
          $filesystem->put('/mnt/apps/data/config/EventMsg',Storage::get('config/EventMsg'));
					$filesystem->put('/mnt/apps/data/config/Formats',Storage::get('config/Formats'));
          $filesystem->put('/mnt/apps/data/config/HereIAm',Storage::get('config/HereIAm'));
          $filesystem->put('/mnt/apps/data/config/Holidays',Storage::get('config/Holidays'));
          //$filesystem->put('/mnt/apps/data/config/InterfaceBoards',Storage::get('config/InterfaceBoards/'.$controller->iid.'/InterfaceBoards'));
          //$filesystem->put('/mnt/apps/data/config/InterfaceTypes',Storage::get('config/InterfaceTypes'));
          $filesystem->put('/mnt/apps/data/config/InternalID',Storage::get('config/InternalID/'.$controller->iid.'/InternalID'));
          $filesystem->put('/mnt/apps/data/config/IOLinkerRules',Storage::get('config/IOLinkerRules/'.$controller->iid.'/IOLinkerRules'));
          $filesystem->put('/mnt/apps/data/config/KeyPadFile',Storage::get('config/KeyPadFile'));
          $filesystem->put('/mnt/apps/data/config/MsgPriority',Storage::get('config/MsgPriority'));
          $filesystem->put('/mnt/apps/data/config/MultiManRules',Storage::get('config/MultiManRules'));
          //$filesystem->put('/mnt/apps/data/config/PINReaders',Storage::get('config/PINReaders'));
          //$filesystem->put('/mnt/apps/data/config/PTPAdjacentReaders',Storage::get('config/PTPAdjacentReaders/'.$controller->iid.'/PTPAdjacentReaders'));
          //$filesystem->put('/mnt/apps/data/config/PTPGateways',Storage::get('config/PTPGateways'));
          $filesystem->put('/mnt/apps/data/config/Readers',Storage::get('config/Readers'));
          $filesystem->put('/mnt/apps/data/config/Schedules',Storage::get('config/Schedules'));


        } catch (\Exception $e) {
          $errors[] = ["Could not connect to ".$controller->name." IP: ".$controller->ip];
        }
      } else {
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
            $filesystem->put('/mnt/apps/data/config/AccessGroups',Storage::get('config/AccessGroups'));
						$filesystem->put('/mnt/apps/data/config/CardSets',Storage::get('config/CardSets'));
            $filesystem->put('/mnt/apps/data/config/CommCfg',Storage::get('config/CommCfg'));
            $filesystem->put('/mnt/apps/data/config/DoorGroups',Storage::get('config/DoorGroups'));
            //$filesystem->put('/mnt/apps/data/config/Elevator',Storage::get('config/Elevator'));
            $filesystem->put('/mnt/apps/data/config/EventMsg',Storage::get('config/EventMsg'));
						$filesystem->put('/mnt/apps/data/config/Formats',Storage::get('config/Formats'));
            $filesystem->put('/mnt/apps/data/config/HereIAm',Storage::get('config/HereIAm'));
            $filesystem->put('/mnt/apps/data/config/Holidays',Storage::get('config/Holidays'));
						//$filesystem->put('/mnt/apps/data/config/InterfaceBoards',Storage::get('config/InterfaceBoards/'.$controller->iid.'/InterfaceBoards'));
						//$filesystem->put('/mnt/apps/data/config/InterfaceTypes',Storage::get('config/InterfaceTypes'));
						$filesystem->put('/mnt/apps/data/config/InternalID',Storage::get('config/InternalID/'.$controller->iid.'/InternalID'));
						$filesystem->put('/mnt/apps/data/config/IOLinkerRules',Storage::get('config/IOLinkerRules/'.$controller->iid.'/IOLinkerRules'));
            $filesystem->put('/mnt/apps/data/config/KeyPadFile',Storage::get('config/KeyPadFile'));
            $filesystem->put('/mnt/apps/data/config/MsgPriority',Storage::get('config/MsgPriority'));
            $filesystem->put('/mnt/apps/data/config/MultiManRules',Storage::get('config/MultiManRules'));
            //$filesystem->put('/mnt/apps/data/config/PINReaders',Storage::get('config/PINReaders'));
            //$filesystem->put('/mnt/apps/data/config/PTPAdjacentReaders',Storage::get('config/PTPAdjacentReaders/'.$controller->iid.'/PTPAdjacentReaders'));
            //$filesystem->put('/mnt/apps/data/config/PTPGateways',Storage::get('config/PTPGateways'));
            $filesystem->put('/mnt/apps/data/config/Readers',Storage::get('config/Readers'));
            $filesystem->put('/mnt/apps/data/config/Schedules',Storage::get('config/Schedules'));
          } catch (\Exception $e) {
            $errors[] = ["Could not connect to ".$controller->name." IP: ".$controller->ip];
          }
        }
      }
      if(isset($errors)){
        $request->e = $errors;
      }
      return $next($request);
    }
}
