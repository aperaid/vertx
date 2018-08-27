<?php

namespace App\Listeners;

use App\Events\EventHappened;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Jobs\SendMessage;
use App\Jobs\SendBroadcast;

use App\Model\Settings\ControllerDevice;
use App\Model\Settings\Door;
use App\Model\Settings\Room;
use App\Model\Settings\DoorGroup;
use App\Model\Monitor\Event\Event;
use App\Model\Access\Credential;
use App\Model\Addon\Telegram\TelegramAccount;
use App\Model\Addon\Tridium\Tridium;
use DB;
class EventHappenedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  EventHappened  $event
     * @return void
     */
    public function handle(EventHappened $event)
    {
      $unmask = explode('%', $event->EventMessage);
      $message = $unmask[0];
      $ip = $unmask[1];
      $msgHeader = substr($message, 0, 4);
      switch ($msgHeader) {
				case 1027:
					break;
        case 1080:
          dispatch((new SendMessage('0080;0010;%'.$ip))->onConnection('sync'));
          break;
        case 1065:
          //Remove the first 10 bundle header
          $allevent = substr($message, 10);
          preg_match_all('/[^^]+/', $allevent, $eventarray, PREG_PATTERN_ORDER);
          //Now each value in $eventarray is for example 1060/1;etc;etc;msgid;mac
          $countevent = count($eventarray[0]);
          echo "Total from $ip is $countevent\n";
         $i = 0; 
         foreach($eventarray[0] as $newEvent){
            $this->SaveEvent($newEvent);
            $i++;
          } 
          echo "Processed Message $i \n";
          dispatch((new SendMessage('0067;0010;%'.$ip)));
          break;
        case 1107:
          dispatch((new SendMessage('0108;0010;%'.$ip)));
          break;
        case 1042:
          dispatch((new SendMessage('0070;0010;%'.$ip)));
          break;
        default:
          //echo "Unknown msg header, nothing is done...\n";
          break;
      }
    }

    private function SaveEvent($event){
      $exploded = explode(';', $event, 10);
      $findEvent = Event::where('msgid', $exploded[1])->where('msgtime', $exploded[7])->where('mac', $exploded[8])->first();
      if (isset($findEvent)){
        echo "$exploded[1] already exists \n";
      } else {
        $newEvent = new Event;
        $newEvent->eventid = $exploded[0];
        $newEvent->msgid = $exploded[1];
        $newEvent->msgtype = $exploded[2];
        $newEvent->classcode = $exploded[3];
        $newEvent->taskcode = $exploded[4];
        $newEvent->eventcode = $exploded[5];
        $newEvent->priority = $exploded[6];
        $newEvent->msgtime = $exploded[7];
        $newEvent->mac = $exploded[8];
        if ($exploded[4] == 7){

        } else {
          $newEvent->extra = $exploded[9];
          $extra = explode(';', $exploded[9]);
        }
        switch ($exploded[4]){
          case 1:
            //Hanya untuk testing tridium
            //app('App\Http\Controllers\MonitorController')->Occupancy();
            switch ($exploded[5]){
              case 1:
                //version
                break;
              case 22:
                $newEvent->interfaceid = $extra[0];
				        $iface = $extra[0];
								$readeraddress = $extra[1];
                $newEvent->readeraddress = $extra[1];
                $newEvent->hexcardpin = $extra[2];
                break;
              case 26:
                $newEvent->interfaceid = $extra[0];
				        $iface = $extra[0];
								$readeraddress = $extra[1];
                $newEvent->readeraddress = $extra[1];
                break;
              case 27:
                $newEvent->interfaceid = $extra[0];
				        $iface = $extra[0];
								$readeraddress = $extra[1];
                $newEvent->readeraddress = $extra[1];
                $newEvent->uniqueid = $extra[2];
                $credential = $extra[2];
                break;
              case 28:
                //interfaceid, reader address, uniqueid, hexcardpin, nbits
                break;
              case 40:
                //interfaceid, reader address, hexcardpin; nbits
                break;
              case 50:
                //cardaction, uniqueid, result
                break;
              case 55:
                //result
                break;
            }
            break;
          case 2:
            switch ($exploded[5]){
							//Hanya untuk testing tridium
							//app('App\Http\Controllers\MonitorController')->Occupancy();
              case 1:
                break;
              case 32:
								$newEvent->interfaceid = $extra[0];
				        $iface = $extra[0];
								$readeraddress = $extra[1];
                $newEvent->readeraddress = $extra[1];
                $newEvent->uniqueid = $extra[2];
                $credential = $extra[2];
				        $readeraddress = $extra[1];
								
								//automatically refresh apb 
								$controllers = ControllerDevice::all();
								foreach($controllers as $controller){
									$message = "$newEvent->uniqueid;";
									$msglen = strlen($message) + 10;
									if ($msglen < 10){
										$msglen = "000".$msglen;
									} else if ($msglen < 100) {
										$msglen = "00".$msglen;
									} else if ($msglen < 1000) {
										$msglen = "0".$msglen;
									} else {
										//nothing need to be done;
									}
									dispatch(new SendMessage('0077;'.$msglen.';'.$message.'%'.$controller->ip));
								}
                break;
              case 21:
              case 23:
              case 24:
              case 27:
              case 30:
              case 31:
              case 33:
              case 34:
              case 35:
              case 36:
              case 37:
              case 38:
                $newEvent->interfaceid = $extra[0];
				        $iface = $extra[0];
								$readeraddress = $extra[1];
                $newEvent->readeraddress = $extra[1];
                $newEvent->uniqueid = $extra[2];
                $credential = $extra[2];
				        $readeraddress = $extra[1];
                break;
              case 20:
								$newEvent->interfaceid = $extra[0];
				        $iface = $extra[0];
								$readeraddress = $extra[1];
                $newEvent->readeraddress = $extra[1];
                $newEvent->uniqueid = $extra[2];
                $credential = $extra[2];
				        $readeraddress = $extra[1];
								
								if(env('APP_TRIDIUM')==1){
									$tridiums = Tridium::all();
									//Tridium
									if(count($tridiums)>0){
										foreach($tridiums as $tridium){
											if($tridium->purpose=="Room"){
												$controller = ControllerDevice::select('controller_devices.mac', 'readers.iface', 'readers.a')
												->leftJoin('readers', 'controller_devices.iid', 'readers.iid')
												->where('readers.entryid', $tridium->room)
												->orWhere('readers.exitid', $tridium->room)
												->first();
											}else if($tridium->purpose=="Lift"){
												$controller = ControllerDevice::select('controller_devices.mac', 'readers.iface', 'readers.a')
												->leftJoin('readers', 'controller_devices.iid', 'readers.iid')
												->where('readers.id', $tridium->reader)
												->first();
											}
											
											if($tridium->purpose=="Room"&&$controller->mac==$newEvent->mac&&$controller->iface==$newEvent->interfaceid){
												$room = Room::select('rooms.id', 'rooms.name as roomname', DB::raw('ifnull(sum(count.count),0) as count'))
												->leftJoin(DB::raw("(select count(events.uniqueid)as count, ANY_VALUE(cd.exitid)as exitid from events left join controller_devices on events.mac = controller_devices.mac left join (select readers.name, readers.iface, readers.a, readers.exitid, controller_devices.mac from readers left join controller_devices on readers.iid = controller_devices.iid) AS cd on events.interfaceid = cd.iface and events.readeraddress = cd.a and events.mac = cd.mac where events.id in (SELECT max(id) FROM events where taskcode = 2 and eventcode = 20 group by uniqueid) group by events.mac,interfaceid,readeraddress) AS count"), function($join){
													$join->on('rooms.id', '=', 'count.exitid');
												})
												->where('rooms.id', $tridium->room)
												->groupBy('rooms.id', 'count.exitid')
												->first();
												
												if($newEvent->readeraddress==0){
													$value = $room->count+1;
												}else if($newEvent->readeraddress==1){
													$value = $room->count-1;
												}
												//echo "VALUE RUANGAN $value";
												
												$this->tridium($tridium->username, $tridium->password, $tridium->url, $tridium->xml, $value);
												//echo "USERNAME $tridium->username, PASSWORD $tridium->password, URL $tridium->url, OCCUPANCY $room->count, PURPOSE $tridium->purpose";
											}else if($tridium->purpose=="Lift"&&$controller->mac==$newEvent->mac&&$controller->iface==$newEvent->interfaceid&&$controller->a==$newEvent->readeraddress){
												$orang = Credential::select('credentials.liftfloor')
												->where('uniqueid', $newEvent->uniqueid)
												->where('credentials.liftfloor', '!=', '')
												->first();
												
												$liftfloors = explode(',', $orang->liftfloor);
												
												foreach($liftfloors as $liftfloor){
													$this->tridium($tridium->username, $tridium->password, $tridium->url, $tridium->xml, $liftfloor);
													//echo "USERNAME $tridium->username, PASSWORD $tridium->password, URL $tridium->url, LIFTFLOOR $liftfloor, PURPOSE $tridium->purpose";
												}
											}
										}
									}
								}
								//Telegram
								if(env('APP_TELEGRAM')==1){
									$telegrams = TelegramAccount::where('subscription', 1)
									->get();
										
									if(count($telegrams)>0){
										$event = Event::where('uniqueid', $newEvent->uniqueid)
										->where('taskcode', 2)
										->where('eventcode', 20)
										->where('readeraddress', 0)
										->whereRaw("substr(msgtime, 14, 10) = DATE_FORMAT(curdate(), '%m/%d/%Y')")
										->count();
										
										//only first event in a day
										if($event==0){
											$user = Credential::where('uniqueid', $newEvent->uniqueid)->first();
											foreach($telegrams as $telegram){
												$token = $telegram->token;
												$groupid = $telegram->groupid;
												$message = $user->name.' check in at '.explode(' GMT ', $newEvent->msgtime)[0].', '.$user->name.' may check out at '.date('H:i:s', strtotime(explode(' GMT ', $newEvent->msgtime)[0])+32400);
												$this->telegram($token, $groupid, $message);
												//echo "TOKEN $token GROUPID $groupid MESSAGE $message\n";
											}
										}
									}
								}
                break;
              case 29:
                $newEvent->interfaceid = $extra[0];
				        $iface = $extra[0];
								$readeraddress = $extra[1];
                $newEvent->readeraddress = $extra[1];
				        $readeraddress = $extra[1];
                $newEvent->uniqueid = $extra[2];
                $credential = $extra[2];
								$pinError = $extra[3]; //No use yet
								break;
              case 32:
								$newEvent->interfaceid = $extra[0];
				        $iface = $extra[0];
								$readeraddress = $extra[1];
                $newEvent->readeraddress = $extra[1];
                $newEvent->uniqueid = $extra[2];
                $credential = $extra[2];
				        $readeraddress = $extra[1];
								
								//automatically refresh apb
								$controllers = ControllerDevice::all();
								foreach($controllers as $controller){
									$message = "$newEvent->uniqueid;";
									$msglen = strlen($message) + 10;
									if ($msglen < 10){
										$msglen = "000".$msglen;
									} else if ($msglen < 100) {
										$msglen = "00".$msglen;
									} else if ($msglen < 1000) {
										$msglen = "0".$msglen;
									} else {
										//nothing need to be done;
									}
									dispatch(new SendMessage('0077;'.$msglen.';'.$message.'%'.$controller->ip));
								}
                break;
              case 38:
                break;
              case 39:
                break;
              case 40:
                break;
              case 41:
                break;
              case 42:
                break;
              case 45:
                break;
              case 50:
                break;
              case 52:
                break;
              case 53:
                break;
              case 54:
                break;
              case 55:
                break;
              case 26:
              case 61:
                $newEvent->interfaceid = $extra[0];
				        $iface = $extra[0];
								$readeraddress = $extra[1];
                $newEvent->readeraddress = $extra[1];
                break;
              case 56:
              case 57:
              case 58:
              case 62:
              case 63:
              case 64:
              case 65:
              case 66:
              case 67:
              case 68:
                $newEvent->interfaceid = $extra[0];
				        $iface = $extra[0];
								$readeraddress = $extra[1];
                $newEvent->readeraddress = $extra[1];
                //$newEvent->mm = $extra[2]
                $newEvent->uniqueid = $extra[3];
                break;
            }
            break;
          case 3:
            switch ($exploded[5]){
              case 1:
							case 122:
								break;
            }
            break;
          case 4:
            switch ($exploded[5]){
							case 1:
								break;
              case 20:
								$newEvent->iolinkerid = $extra[2] . ';' . $extra[3];
								$newEvent->interfaceid = $extra[0];
								$iface = $extra[0];
								$readeraddress = $extra[1];
								$newEvent->readeraddress = $extra[1];
								$msgtype = "alarm";
								$ioevent = $newEvent->iolinkerid;
								//update iolinker alarm status to door
								//Khusus 9200,9300,9400, masukin ke position 0 dan 1 (kalau ada)
								if(in_array($extra[2], ['8100', '8200', '8300', '8800', '8900', '9200', '9300', '9400'])){
									$door = Door::where('iid', ControllerDevice::where('mac', $exploded[8])->first()->iid)
									->where('if', $extra[0])
									->first();
								//Kalau belakangnya 00, maka bikin $door yg positionnya 0
								}else if(in_array($extra[2], ['9000', '9100', '9500'])){
									$door = Door::where('iid', ControllerDevice::where('mac', $exploded[8])->first()->iid)
									->where('if', $extra[0])
									->where('position', 0)
									->first();
								//Kalau belakangnya 10, maka bikin $door yg position nya 1
								}else if(in_array($extra[2], ['9010', '9110', '9510'])){
									$door = Door::where('iid', ControllerDevice::where('mac', $exploded[8])->first()->iid)
									->where('if', $extra[0])
									->where('position', 1)
									->first();
								}else{
									//do nothing
								}
								switch (substr($extra[2], 0, 2)) {
									case 81:
										if (isset($door)){
											echo "Tamper Switch Normal\n";
										}
										break;
									case 82:
										if (isset($door)){
											echo "AC Failure Normal\n";
										}
										break;
									case 83:
										if (isset($door)){
											echo "Battery Switch Normal\n";
										}
										break;
									case 88:
										if (isset($door)){
											echo "Input 1 Normal\n";
										}
										break;
									case 89:
										if (isset($door)){
											echo "Input 2 Normal\n";
										}
										break;
									case 90:
										if (isset($door)){
											$door->forcealarm = $newEvent->iolinkerid;
											$door->save();
											//Telegram
											if(env('APP_TELEGRAM')==1){
												$telegrams = TelegramAccount::where('subscription', 2)
												->get();
											
												if($telegrams->count()>0 && $extra[3]==1){
													foreach($telegrams as $telegram){
														$token = $telegram->token;
														$groupid = $telegram->groupid;
														$message = $door->name.' Forced Open Alarm';
														$this->telegram($token, $groupid, $message);
														//echo "TOKEN $token GROUPID $groupid MESSAGE $message\n";
													}
												}
											}
										}
										break;
									case 91:
										if (isset($door)){
											$door->holdalarm = $newEvent->iolinkerid;
											$door->save();
											//Telegram
											if(env('APP_TELEGRAM')==1){
												$telegrams = TelegramAccount::where('subscription', 3)
												->get();
											
												if($telegrams->count()>0 && $extra[3]==1){
													foreach($telegrams as $telegram){
														$token = $telegram->token;
														$groupid = $telegram->groupid;
														$message = $door->name.' Hold Open Alarm';
														$this->telegram($token, $groupid, $message);
														//echo "TOKEN $token GROUPID $groupid MESSAGE $message\n";
													}
												}
											}
										}
										break;
									case 92:
										if (isset($door)){
											$door->tamperalarm = $newEvent->iolinkerid;
											$door->save();
										}
										break;
									case 93:
										if (isset($door)){
											$door->acalarm = $newEvent->iolinkerid;
											$door->save();
										}
										break;
									case 94:
										if (isset($door)){
											$door->batteryalarm = $newEvent->iolinkerid;
											$door->save();
										}
										break;
									case 95:
										if (isset($door)){
											$door->openalarm = $newEvent->iolinkerid;
											$door->save();
										}
										break;
									default:
										echo "Something wrong with controller\n";
										break;
								}
								break;
            }
            break;
          case 5:
            switch ($exploded[5]){
              case 1:
            }
            break;
          case 6:
            switch ($exploded[5]){
              case 1:
            }
            break;
          case 7:
            switch ($exploded[5]){
              case 1:
            }
            break;
          case 8:
            switch ($exploded[5]){
              case 1:
            }
            break;
          case 9:
            switch ($exploded[5]){
              case 1:
            }
            break;
          case 10:
            switch ($exploded[5]){
              case 1:
            }
            break;
          case 11:
            switch ($exploded[5]){
              case 1:
            }
            break;
          case 14:
            switch ($exploded[5]){
              case 1:
            }
            break;
          default:
            break;
        }
        $newEvent->save();
        echo "$exploded[1] saved..\n";

        // Credential unique id
        if(!isset($credential)){
          $credential = null;
        }
        // 0 - 32
        if(!isset($iface)){
          $iface = null;
        }
        //0 or 1 (left or right)
        if(!isset($readeraddress)){
          $readeraddress = null;
        }
        if(!isset($ioevent)){
          $ioevent = null;
        }
        if(!isset($msgtype)){
          $msgtype = 'event';
        }
				
        @dispatch((new SendBroadcast(json_encode(array('name'=>'server', 'msgtype'=>$msgtype, 'controller'=>$exploded[8], 'iface'=>$iface, 'readeraddress'=>$readeraddress, 'msgtime'=>$exploded[7], 'taskcode'=>$exploded[4], 'eventcode'=>$exploded[5], 'ioevent'=>$ioevent, 'credential'=>$credential, 'id'=>$newEvent->id)))));
      }
      return true;
    }
		
		private function telegram($token, $user, $message)
    {
			$request_params = [
			'chat_id' => $user,
			'text' => $message
			];
			$request_url = 'https://api.telegram.org/bot'.$token.'/sendMessage?'.http_build_query($request_params);
			
			file_get_contents($request_url);
    }
		
		private function tridium($username, $password, $url, $xml, $value)
    {
			$input_xml = str_replace('val="0.0"', 'val="'.$value.'"', $xml);
			
			$process = curl_init($url);
			curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: text/xml', 'User-Agent: Java/1.8.0_131'));
			curl_setopt($process, CURLOPT_HEADER, 1);
			curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
			curl_setopt($process, CURLOPT_TIMEOUT, 30);
			curl_setopt($process, CURLOPT_POST, 1);
			curl_setopt($process, CURLOPT_POSTFIELDS, $input_xml);
			curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
			$return = curl_exec($process);
			curl_close($process);
    }
}
