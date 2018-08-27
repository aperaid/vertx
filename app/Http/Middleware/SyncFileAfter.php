<?php

namespace App\Http\Middleware;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Ftp as Adapter;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests;

use Closure;
use App\Model\Settings\ControllerDevice;
use App\Jobs\SendMessage;

class SyncFileAfter
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
      $response = $next($request);
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
          if($request->type == 'ag') {
            //AccessGroups
            $filesystem->put('/mnt/apps/data/config/AccessGroups',Storage::get('config/AccessGroups'));
          } elseif ($request->type == 'controller') {
						//CardSets
						$filesystem->put('/mnt/apps/data/config/CardSets',Storage::get('config/CardSets'));
            //CommCfg
            $filesystem->put('/mnt/apps/data/config/CommCfg',Storage::get('config/CommCfg'));
            //EventMsg
            $filesystem->put('/mnt/apps/data/config/EventMsg',Storage::get('config/EventMsg'));
            //Elevator
            //$filesystem->put('/mnt/apps/data/config/Elevator',Storage::get('config/Elevator'));
						//Formats
            $filesystem->put('/mnt/apps/data/config/Formats',Storage::get('config/Formats'));
            //HereIAm
            $filesystem->put('/mnt/apps/data/config/HereIAm',Storage::get('config/HereIAm'));
            //InterfaceBoards
            //$filesystem->put('/mnt/apps/data/config/InterfaceBoards',Storage::get('config/InterfaceBoards/'.$controller->iid.'/InterfaceBoards'));
            //InterfaceTypes
            //$filesystem->put('/mnt/apps/data/config/InterfaceTypes',Storage::get('config/InterfaceTypes'));
            //InternalID
            $filesystem->put('/mnt/apps/data/config/InternalID',Storage::get('config/InternalID/'.$controller->iid.'/InternalID'));
            //MsgPriority
            $filesystem->put('/mnt/apps/data/config/MsgPriority',Storage::get('config/MsgPriority'));
            //PTPAdjacentReaders
            //$filesystem->put('/mnt/apps/data/config/PTPAdjacentReaders',Storage::get('config/PTPAdjacentReaders/'.$controller->iid.'/PTPAdjacentReaders'));
            //PTPGateways
            //$filesystem->put('/mnt/apps/data/config/PTPGateways',Storage::get('config/PTPGateways'));
            //KeyPadFile
            $filesystem->put('/mnt/apps/data/config/KeyPadFile',Storage::get('config/KeyPadFile'));
          } elseif ($request->type == 'dg') {
            //DoorGroups
            $filesystem->put('/mnt/apps/data/config/DoorGroups',Storage::get('config/DoorGroups'));
          } elseif ($request->type == 'iolinker') {
            //IOLinkerRules
            $filesystem->put('/mnt/apps/data/config/IOLinkerRules',Storage::get('config/IOLinkerRules/'.$controller->iid.'/IOLinkerRules'));
          } elseif ($request->type == 'mm') {
            //MultimanRules
            $filesystem->put('/mnt/apps/data/config/MultiManRules',Storage::get('config/MultiManRules'));
          } elseif ($request->type == 'pin') {
            //PINReaders
            //$filesystem->put('/mnt/apps/data/config/PINReaders',Storage::get('config/PINReaders'));
          } elseif ($request->type == 'reader') {
            //Readers
            $filesystem->put('/mnt/apps/data/config/Readers',Storage::get('config/Readers'));
          } elseif ($request->type == 'schedule') {
            //Schedules
            $filesystem->put('/mnt/apps/data/config/Schedules',Storage::get('config/Schedules'));
          } elseif ($request->type == 'holiday') {
            //Holidays
            $filesystem->put('/mnt/apps/data/config/Holidays',Storage::get('config/Holidays'));
          } elseif ($request->type == 'remedy') {
            //Schedules
            $filesystem->put('/mnt/apps/data/config/Schedules',Storage::get('config/Schedules'));
            //AccessGroups
            $filesystem->put('/mnt/apps/data/config/AccessGroups',Storage::get('config/AccessGroups'));
          }
        } catch (\Exception $e) {
          $errors[] = $controller;
        }
      }
      if(isset($errors)){
        $err = "Could not connect to:\n";
        foreach ($errors as $error) {
          $err .= "$error->name IP $error->ip\n";
        }
        $request->session()->flash('error', $err);
      }
      return $response;
    }
}
