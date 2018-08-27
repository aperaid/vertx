<?php

namespace App\Http\Controllers\Monitor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Jobs\SendMessage;
use Yajra\Datatables\Datatables;

use App\Model\Settings\ControllerDevice;
use App\Model\Monitor\CCTV\Cctv;
use App\Model\Settings\Door;
use App\Model\Monitor\Event\Event;
use App\Model\Monitor\Event\EventName;
use App\Model\Settings\Room;
use App\Model\Addon\Remedy\Remedy;
use App\Model\Report\Log;
use DB;
use Auth;
use App\Jobs\SendBroadcast;

class MonitorController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware(function ($request, $next){
			if(Auth::check()&&(Auth::user()->access=='Administrator'||Auth::user()->access=='Operator'||Auth::user()->access=='Employee'||Auth::user()->access=='Monitor'))
				$this->access = array("edit", "show", "position", "map", "cctv", "disread1", "enaread1", "disread2", "enaread2", "forceoff", "forceon", "heldoff", "heldon", "lockoff", "lockon", "open", "restore");
			else
				$this->access = array("");
		return $next($request);
		});
		
		//set cctv url name after ip
		$this->urlip = "/cgi-bin/main.cgi";
		
		date_default_timezone_set('Asia/Krasnoyarsk');
		$this->date = date("H:i:s \G\M\T m/d/Y");
	}
	
	public function EditEventLive(Request $request){
		if(Auth::check()){
		if(in_array("edit", $this->access)){
			//Validation
			$this->validate($request, [
				'id'=>'required',
				'notes'=>'required',
			]);
			$event = Event::find($request->id);
			$events = Event::leftJoin('event_names', function($join){
					$join->on('events.taskcode', '=', 'event_names.task')
					->on('events.eventcode', '=', 'event_names.event');
				})
			->where('events.id', $event->id)
			->first();
			$eventio = Event::leftJoin('io_link_event_names', 'events.iolinkerid', '=', 'io_link_event_names.extra')
			->where('events.id', $event->id)
			->first();
			$log = new Log;
			$log->time = $this->date;
			$log->name = Auth::user()->name;
			if($event->taskcode==4 && $event->eventcode==20)
				$log->act = 'ACKNOWLEGE Alarm WITH ID '.$request->id.', NAME '.$eventio->name.', NOTES '.$request->notes;
			else
				$log->act = 'ACKNOWLEGE Alarm WITH ID '.$request->id.', NAME '.$events->name.', NOTES '.$request->notes;
			$log->save();
			$event->notes = $request->notes;
			if($event->taskcode==4&&$event->eventcode==20){
				$event->acknowledge = 1;
			}
			$event->save();
			$request->session()->flash('message', 'Event (ID: '.$request->id.') has been successfully edited.');
			$request->session()->flash('id', $request->id);
		}else
			return redirect()->back();
		}else
			return redirect('/login');
	}
	
	public function EventLive(Request $request){
		if(Auth::check()){
		if(in_array("show", $this->access)){
			if (isset($request->id)){
				$events = Event::select('events.*', 'event_names.name as eventname', 'controller_devices.name as controllername', 'credentials.name as credentialname', 'credentials.accesstype', 'credentials.cardnumber', 'credentials.cardbits', 'credentials.cardsetid', 'credentials.printednumber', 'io_link_event_names.name as iolinkname')
				->leftJoin('event_names', function($join){
					$join->on('events.taskcode', '=', 'event_names.task')
					->on('events.eventcode', '=', 'event_names.event');
				})
				->leftJoin('io_link_event_names', 'events.iolinkerid', '=', 'io_link_event_names.extra')
				->leftJoin('controller_devices', 'events.mac', '=', 'controller_devices.mac')
				->leftJoin('credentials', 'events.uniqueid', '=', 'credentials.uniqueid')
				->where('events.id', $request->id)
				->first();
				return $events->toJson();
			}else{
				$select = Event::limit(200)->orderBy('id', 'desc')
				->where('events.taskcode', '!=', 3)
				->where('events.taskcode', '!=', 5)
				->where('events.taskcode', '!=', 6)
				->where('events.taskcode', '!=', 7)
				->where('events.taskcode', '!=', 8)
				->where('events.taskcode', '!=', 9)
				->where('events.taskcode', '!=', 10)
				->where('events.taskcode', '!=', 14)
				->where('events.eventcode', '!=', 1)
				->where(function($query){
					$query
					->where('events.iolinkerid', '!=', "8100;0")
					->where('events.iolinkerid', '!=', "8200;0")
					->where('events.iolinkerid', '!=', "8300;0")
					->where('events.iolinkerid', '!=', "8800;0")
					->where('events.iolinkerid', '!=', "8900;0")
					->where('events.iolinkerid', '!=', "9500;1")
					->where('events.iolinkerid', '!=', "9400;1")
					->where('events.iolinkerid', '!=', "9300;1")
					->where('events.iolinkerid', '!=', "9200;1")
					//->where('events.iolinkerid', '!=', "9100;1")
					//->where('events.iolinkerid', '!=', "9000;1")
					->where('events.iolinkerid', '!=', "9500;0")
					->where('events.iolinkerid', '!=', "9400;0")
					->where('events.iolinkerid', '!=', "9300;0")
					->where('events.iolinkerid', '!=', "9200;0")
					->where('events.iolinkerid', '!=', "9100;0")
					->where('events.iolinkerid', '!=', "9000;0")
					->where('events.iolinkerid', '!=', "9510;1")
					->where('events.iolinkerid', '!=', "9410;1")
					->where('events.iolinkerid', '!=', "9310;1")
					->where('events.iolinkerid', '!=', "9210;1")
					//->where('events.iolinkerid', '!=', "9110;1")
					//->where('events.iolinkerid', '!=', "9010;1")
					->where('events.iolinkerid', '!=', "9510;0")
					->where('events.iolinkerid', '!=', "9410;0")
					->where('events.iolinkerid', '!=', "9310;0")
					->where('events.iolinkerid', '!=', "9210;0")
					->where('events.iolinkerid', '!=', "9110;0")
					->where('events.iolinkerid', '!=', "9010;0")
					->orWhere('events.iolinkerid',  null);
				})
				->pluck('id')
				->toArray();
				$events = Event::select('events.*', 'event_names.name as eventname', 'controller_devices.name as controllername', 'credentials.name as credentialname', 'credentials.accesstype', 'credentials.cardnumber', 'cd.name as readername'/*, 'dd.name as doorname'*/, 'io_link_event_names.name as iolinkname')
				->leftJoin('event_names', function($join){
					$join->on('events.taskcode', '=', 'event_names.task')
					->on('events.eventcode', '=', 'event_names.event');
				})
				->leftJoin('io_link_event_names', 'events.iolinkerid', '=', 'io_link_event_names.extra')
				->leftJoin('controller_devices', 'events.mac', '=', 'controller_devices.mac')
				->leftJoin('credentials', 'events.uniqueid', '=', 'credentials.uniqueid')
				->leftJoin(DB::raw('(select readers.name, readers.iface, readers.a, readers.exitid, controller_devices.mac from readers left join controller_devices on readers.iid = controller_devices.iid) AS cd'), function($join){
					$join->on('events.interfaceid', '=', 'cd.iface')
					->on('events.readeraddress', '=', 'cd.a')
					->on('events.mac', '=', 'cd.mac');
				})
				/*->leftJoin(DB::raw('(select doors.name, doors.if, doors.iid, controller_devices.mac from doors left join controller_devices on doors.iid = controller_devices.iid) AS dd'), function($join){
					$join->on('events.interfaceid', '=', 'dd.if')
					->on('events.mac', '=', 'dd.mac');
				})*/
				->whereIn('events.id', $select);
			}
			
			if(env('APP_COMPANY') == "BCA"){
				$events = $events/*->select('events.*', DB::raw('date_format(str_to_date(right(events.msgtime,10), "%m/%d/%Y"), "%Y%m%d") as time'), 'events.msgtime as msgtime', 'event_names.name as eventname', 'credentials.name as credentialname', 'credentials.nip', 'cd.name as readername', db::raw('null as passid'))
				->select('events.*', 'event_names.name as eventname', 'controller_devices.name as controllername', 'credentials.name as credentialname', 'credentials.accesstype', 'credentials.cardnumber', 'cd.name as readername', 'dd.name as doorname', 'io_link_event_names.name as iolinkname', 'emprem.passid as emppass', 'venrem.passid as venpass', 'rooms.name as roomname', 'r_locations.room as r_roomname')
				->leftJoin(DB::raw('(select passid, nip, date from prs left join remedies on prs.instanceid = remedies.instanceid where location = "'.env('APP_BCA').'") AS emprem'), function($join){
					$join->on('credentials.nip', '=', 'emprem.nip');
				})
				->leftJoin(DB::raw('(select passid, temp_passes.uniqueid, date from temp_passes left join prs on temp_passes.prid = prs.id left join remedies on prs.instanceid = remedies.instanceid where location = "'.env('APP_BCA').'") AS venrem'), function($join){
					$join->on('credentials.uniqueid', '=', 'venrem.uniqueid');
				})
				->leftJoin(DB::raw('(SELECT rl1.* FROM r_locations rl1 JOIN (SELECT MAX(id)as maxid FROM r_locations GROUP BY passid) rl2 ON rl1.id = rl2.maxid AND rl1.id = rl2.maxid) AS r_locations'), function($join){
					$join->on('emprem.passid', '=', 'r_locations.passid')
					->orOn('venrem.passid', '=', 'r_locations.passid');
				})
				->leftJoin('rooms', 'cd.exitid', 'rooms.id')
				->leftJoin(DB::raw('(SELECT rt1.* FROM r_times rt1 JOIN (SELECT MAX(id)as maxid FROM r_times GROUP BY passid) rt2 ON rt1.id = rt2.maxid AND rt1.id = rt2.maxid) AS r_times'), function($join){
					$join->on('emprem.passid', '=', 'r_times.passid')
					->orOn('venrem.passid', '=', 'r_times.passid');
				})
				->where(function($query){
					$query
					->whereRaw('((date_format(str_to_date(right(events.msgtime,10), "%m/%d/%Y"), "%Y/%c/%e") = emprem.date or date_format(str_to_date(right(events.msgtime,10), "%m/%d/%Y"), "%Y/%c/%e") = venrem.date) and (substr(events.msgtime, 1, 8) not between right(r_times.start, 8) and right(r_times.end, 8)) and events.eventcode != 20) or ((date_format(str_to_date(right(events.msgtime,10), "%m/%d/%Y"), "%Y/%c/%e") = emprem.date or date_format(str_to_date(right(events.msgtime,10), "%m/%d/%Y"), "%Y/%c/%e") = venrem.date) and (substr(events.msgtime, 1, 8) between right(r_times.start, 8) and right(r_times.end, 8)) and events.eventcode = 20) or (events.uniqueid in (SELECT uniqueid FROM credentials c WHERE NOT EXISTS ( SELECT nip FROM prs p WHERE p.nip = c.nip)) and events.uniqueid not in (SELECT uniqueid FROM temp_passes group by uniqueid) )')
					->orWhereNotNull('iolinkerid')
					->orWhereNotNull('hexcardpin');
				})*/
				->get();
				/*$events = array(); 
				foreach($r_events as $event){
					$room = $event->roomname;
					$r_room = $event->r_roomname;
					$r_rooms = explode(", ",$r_room);
					if($event->emppass){
						$passid = $event->emppass;
					}else if($event->venpass){
						$passid = $event->venpass;
					}else{
						$passid = "None";
					}
					
					if($passid == "None"){
						$events[]=$event;
					}else{
						if($room == "Command Center" || $room == "Corridor" || $room == "LANTAI 12"){
							$events[]=$event;
						}else{
							foreach ($r_rooms as $rr){
								if($room == $rr){
									$events[]=$event;
								}
							}
						}
					}
				}*/
				
				foreach($events as $event){
					$passid = Remedy::whereRaw("date_format(str_to_date(remedies.date, '%Y/%c/%e'), '%Y%m%d') = '$event->time'")
					->where('remedies.nip', 'LIKE', '%'.$event->nip.'%')
					->get()->pluck('passid')->toArray();
					
					//assign the value to collections
					$event->passid=implode(',',$passid);
				}
			}else{
				$events = $events->get();
			}
			
			//pake function dibawah
			$color = array();
			foreach($events as $event){
				if($event->taskcode == 4 && $event->eventcode == 20){
					if(substr($event->iolinkerid,5)==0){
						$color[] = "LightGreen";
					}else{
						$color[] = $this->BGColor($event->taskcode, $event->eventcode);
					}
				}else{
					$color[] = $this->BGColor($event->taskcode, $event->eventcode);
				}
			}
			
			return view('pages.monitor.liveevent')
			->with('events', $events)
			->with('company', env('APP_COMPANY'))
			->with('color', $color)
			->with('top_menu_sel', 'event');
		}else
			return redirect()->back();
		}else
			return redirect('/login');
	}
	
	public function BGColor($taskcode, $eventcode){
		$color = "none";
		switch ($taskcode){
			case 1:
				switch ($eventcode){
					case 22:
					case 26:
					case 27:
					case 28:
						$color = "LightGray";
						break;
				}
				break;
			case 2:
				switch ($eventcode){
					case 20:
					case 21:
					case 37:
					case 38:
					case 63:
					case 67:
					case 68:
						$color = "LightGreen";
						break;
					case 23:
					case 24:
					case 27:
					case 29:
					case 30:
					case 31:
					case 32:
					case 33:
					case 34:
					case 35:
					case 36:
					case 53:
					case 54:
					case 55:
					case 56:
					case 57:
					case 58:
					case 61:
					case 62:
					case 64:
					case 65:
					case 66:
						$color = "LightGoldenRodYellow";
						break;
					case 45:
					case 50:
						$color = "LightSkyBlue";
						break;
					case 26:
						$color = "LightGray";
						break;
				}
				break;
			case 4:
				switch ($eventcode){
					case 20:
						$color = "LightCoral";
						break;
				}
				break;
			default:
				$color = "none";
				break;
		}
		
		return $color;
	}
	
	public function Alarm(Request $request){
		if(Auth::check()){
		if(in_array("show", $this->access)){
			$alarms = Event::select('events.*', 'event_names.name as eventname', 'controller_devices.name as controllername', 'cd.name as doorname', 'credentials.name as credentialname', 'credentials.accesstype', 'credentials.cardnumber', 'io_link_event_names.name as iolinkname')
			->leftJoin('event_names', function($join){
				$join->on('events.taskcode', '=', 'event_names.task')
				->on('events.eventcode', '=', 'event_names.event');
			})
			->leftJoin('io_link_event_names', 'events.iolinkerid', '=', 'io_link_event_names.extra')
			->leftJoin('controller_devices', 'events.mac', '=', 'controller_devices.mac')
			->leftJoin('credentials', 'events.uniqueid', '=', 'credentials.uniqueid')
			->leftJoin(DB::raw('(select doors.name, doors.panel, doors.iid, doors.if, doors.position, controller_devices.mac from doors left join controller_devices on doors.iid = controller_devices.iid) AS cd'), function($join){
				$join->on('events.interfaceid', '=', 'cd.if')
				->on('events.readeraddress', '=', 'cd.position')
				->on('events.mac', '=', 'cd.mac');
			})
			->whereRaw('(events.iolinkerid = "9000;1" OR events.iolinkerid = "9100;1" OR events.iolinkerid = "9200;1" OR events.iolinkerid = "9300;1" OR events.iolinkerid = "9400;1" OR events.iolinkerid = "9010;1" OR events.iolinkerid = "9110;1" OR events.iolinkerid = "9210;1" OR events.iolinkerid = "9310;1" OR events.iolinkerid = "9410;1")')
			->whereNull('events.acknowledge')
			->get();

			if (isset($request->id)){
				$alarm2s = Event::select('events.*', 'event_names.name as eventname', 'controller_devices.name as controllername', 'cd.name as doorname', 'credentials.name as credentialname', 'credentials.accesstype', 'credentials.cardnumber', 'io_link_event_names.name as iolinkname')
				->leftJoin('event_names', function($join){
					$join->on('events.taskcode', '=', 'event_names.task')
					->on('events.eventcode', '=', 'event_names.event');
				})
				->leftJoin('io_link_event_names', 'events.iolinkerid', '=', 'io_link_event_names.extra')
				->leftJoin('controller_devices', 'events.mac', '=', 'controller_devices.mac')
				->leftJoin('credentials', 'events.uniqueid', '=', 'credentials.uniqueid')
				->leftJoin(DB::raw('(select doors.name, doors.panel, doors.iid, doors.if, doors.position, controller_devices.mac from doors left join controller_devices on doors.iid = controller_devices.iid) AS cd'), function($join){
					$join->on('events.interfaceid', '=', 'cd.if')
					->on('events.readeraddress', '=', 'cd.position')
					->on('events.mac', '=', 'cd.mac');
				})
				->whereRaw('(events.iolinkerid = "9000;1" OR events.iolinkerid = "9100;1" OR events.iolinkerid = "9200;1" OR events.iolinkerid = "9300;1" OR events.iolinkerid = "9400;1" OR events.iolinkerid = "9010;1" OR events.iolinkerid = "9110;1" OR events.iolinkerid = "9210;1" OR events.iolinkerid = "9310;1" OR events.iolinkerid = "9410;1")')
				->where('events.acknowledge', '1')
				->where('events.id', $request->id)
				->first();
				return $alarm2s->toJson();
			}else{
				$alarm2s = Event::select('events.*', 'event_names.name as eventname', 'controller_devices.name as controllername', 'cd.name as doorname', 'credentials.name as credentialname', 'credentials.accesstype', 'credentials.cardnumber', 'io_link_event_names.name as iolinkname')
				->leftJoin('event_names', function($join){
					$join->on('events.taskcode', '=', 'event_names.task')
					->on('events.eventcode', '=', 'event_names.event');
				})
				->leftJoin('io_link_event_names', 'events.iolinkerid', '=', 'io_link_event_names.extra')
				->leftJoin('controller_devices', 'events.mac', '=', 'controller_devices.mac')
				->leftJoin('credentials', 'events.uniqueid', '=', 'credentials.uniqueid')
				->leftJoin(DB::raw('(select doors.name, doors.panel, doors.iid, doors.if, doors.position, controller_devices.mac from doors left join controller_devices on doors.iid = controller_devices.iid) AS cd'), function($join){
					$join->on('events.interfaceid', '=', 'cd.if')
					->on('events.readeraddress', '=', 'cd.position')
					->on('events.mac', '=', 'cd.mac');
				})
				->whereRaw('(events.iolinkerid = "9000;1" OR events.iolinkerid = "9100;1" OR events.iolinkerid = "9200;1" OR events.iolinkerid = "9300;1" OR events.iolinkerid = "9400;1" OR events.iolinkerid = "9010;1" OR events.iolinkerid = "9110;1" OR events.iolinkerid = "9210;1" OR events.iolinkerid = "9310;1" OR events.iolinkerid = "9410;1")')
				->where('events.acknowledge', '1')
				->get();
			}

			return view('pages.monitor.alarm')
			->with('alarms', $alarms)
			->with('alarm2s', $alarm2s)
			->with('top_menu_sel', 'alarm');
		}else
			return redirect()->back();
		}else
			return redirect('/login');
	}
	
	public function EditAlarm(Request $request){
		if(Auth::check()){
		if(in_array("edit", $this->access)){
			//Validation
			$this->validate($request, [
				'id'=>'required',
			]);
			$event = Event::find($request->id);
			$events = Event::leftJoin('io_link_event_names', 'events.iolinkerid', '=', 'io_link_event_names.extra')
			->where('events.id', $event->id)
			->first();
			$log = new Log;
			$log->time = $this->date;
			$log->name = Auth::user()->name;
			$log->act = 'ACKNOWLEGE Alarm WITH ID '.$request->id.', NAME '.$events->name.', NOTES '.$request->acktext;
			$log->save();
			$event->acknowledge = 1;
			$event->notes = $request->acktext;
			$event->save();
			$request->session()->flash('message', 'Acknowledge (ID: '.$request->id.')');
			$request->session()->flash('id', $request->id);
		}else
			return redirect()->back();
		}else
			return redirect('/login');
	}

	public function Position(Request $request){
		if(Auth::check()){
		if(in_array("position", $this->access)){
			if (isset($request->id)){
				$room_detail = Room::select('rooms.name as roomname', 'count.iid', 'count.iface', 'last.msgtime', 'count.count',  'trans.msgtime as msgtimes', 'trans.credentialname as credentialsname', 'trans.eventname as accessmethod', 'trans.printednumber as cardnumber')
				->leftJoin(DB::raw('(select count(events.uniqueid)as count, ANY_VALUE(cd.exitid)as exitid, ANY_VALUE(controller_devices.iid)as iid, ANY_VALUE(cd.iface)as iface, ANY_VALUE(events.msgtime)as msgtime from events left join controller_devices on events.mac = controller_devices.mac left join (select readers.name, readers.iface, readers.a, readers.exitid, controller_devices.mac from readers left join controller_devices on readers.iid = controller_devices.iid) AS cd on events.interfaceid = cd.iface and events.readeraddress = cd.a and events.mac = cd.mac where events.id in (SELECT max(id) FROM events where uniqueid is not null group by uniqueid) group by events.mac,interfaceid,readeraddress) AS count'), function($join){
					$join->on('rooms.id', '=', 'count.exitid');
				})// and substr(msgtime,14,10)=DATE_FORMAT(str_to_date(curdate(), "%Y-%m-%d"), "%m/%d/%Y")klo mao perhari
				->leftJoin(DB::raw('(select events.msgtime, credentials.name as credentialname, event_names.name as eventname, credentials.printednumber, cd.exitid from events left join controller_devices on events.mac = controller_devices.mac left join (select readers.name, readers.iface, readers.a, readers.exitid, controller_devices.mac from readers left join controller_devices on readers.iid = controller_devices.iid) AS cd on events.interfaceid = cd.iface and events.readeraddress = cd.a and events.mac = cd.mac left join event_names on events.taskcode = event_names.task and events.eventcode = event_names.event left join credentials on events.uniqueid=credentials.uniqueid where events.id in (SELECT max(id) FROM events where uniqueid is not null group by uniqueid)) AS trans'), function($join){
					$join->on('rooms.id', '=', 'trans.exitid');
				})
				->leftJoin(DB::raw('(select events.msgtime, cd.exitid from events left join controller_devices on events.mac = controller_devices.mac left join (select readers.name, readers.iface, readers.a, readers.exitid, controller_devices.mac from readers left join controller_devices on readers.iid = controller_devices.iid) AS cd on events.interfaceid = cd.iface and events.readeraddress = cd.a and events.mac = cd.mac left join event_names on events.taskcode = event_names.task and events.eventcode = event_names.event where events.id in (SELECT max(id) FROM events where uniqueid is not null)) AS last'), function($join){
					$join->on('rooms.id', '=', 'last.exitid');
				})
				->where('rooms.id', $request->id)
				->get();
				return $room_detail->toJson();
			}else{
				$rooms = Room::select('rooms.id', 'rooms.name as roomname', DB::raw('sum(count.count) as count'))
				->leftJoin(DB::raw('(select count(events.uniqueid)as count, ANY_VALUE(cd.exitid)as exitid from events left join controller_devices on events.mac = controller_devices.mac left join (select readers.name, readers.iface, readers.a, readers.exitid, controller_devices.mac from readers left join controller_devices on readers.iid = controller_devices.iid) AS cd on events.interfaceid = cd.iface and events.readeraddress = cd.a and events.mac = cd.mac where events.id in (SELECT max(id) FROM events where uniqueid is not null group by uniqueid) group by events.mac,interfaceid,readeraddress) AS count'), function($join){
					$join->on('rooms.id', '=', 'count.exitid');
				})
				->groupBy('rooms.id', 'count.exitid')
				->get();
			}
			
			return view('pages.monitor.position')
			->with('rooms', $rooms)
			->with('top_menu_sel', 'position');
		}else
			return redirect()->back();
		}else
			return redirect('/login');
	}
	
	public function Map(Request $request){
		if(Auth::check()){
		if(in_array("map", $this->access)){
			if (isset($request->id)){
				if(substr($request->id, 0, 4)=='door'){
					$door_detail = Door::where('doors.id', substr($request->id, 4))->first();
					return $door_detail->toJson();
				}else if(substr($request->id, 0, 4)=='room'){
					$room_detail = Room::select('rooms.id', 'rooms.name', DB::raw('sum(count.count) as traffic'))
					->leftJoin(DB::raw('(select count(events.uniqueid)as count, ANY_VALUE(cd.exitid)as exitid from events left join controller_devices on events.mac = controller_devices.mac left join (select readers.name, readers.iface, readers.a, readers.exitid, controller_devices.mac from readers left join controller_devices on readers.iid = controller_devices.iid) AS cd on events.interfaceid = cd.iface and events.readeraddress = cd.a and events.mac = cd.mac where events.id in (SELECT max(id) FROM events where uniqueid is not null group by uniqueid) group by events.mac,interfaceid,readeraddress) AS count'), function($join){
						$join->on('rooms.id', '=', 'count.exitid');
					})
					->groupBy('rooms.id', 'count.exitid')
					->where('rooms.id', substr($request->id, 4))->first();
					return $room_detail->toJson();
				}
			}else{
				$doors = Door::all();
				
				foreach($doors as $door){
					if($door->forcealarm=='9000;1')
						$door->color='red';
					else if($door->holdalarm=='9100;1')
						$door->color='red';
					else if($door->forcealarm=='9000;0' && $door->holdalarm=='9100;0')
						$door->color='green';
					else
						$door->color='yellow';
				}
				
				$rooms = Room::select('rooms.id', 'rooms.name as roomname', DB::raw('sum(count.count) as count'))
				->leftJoin(DB::raw('(select count(events.uniqueid)as count, ANY_VALUE(cd.exitid)as exitid from events left join controller_devices on events.mac = controller_devices.mac left join (select readers.name, readers.iface, readers.a, readers.exitid, controller_devices.mac from readers left join controller_devices on readers.iid = controller_devices.iid) AS cd on events.interfaceid = cd.iface and events.readeraddress = cd.a and events.mac = cd.mac where events.id in (SELECT max(id) FROM events where uniqueid is not null group by uniqueid) group by events.mac,interfaceid,readeraddress) AS count'), function($join){
					$join->on('rooms.id', '=', 'count.exitid');
				})
				->groupBy('rooms.id', 'count.exitid')
				->get();
			}
			
			/*$maxid = Event::select(DB::raw('max(id) as id'))->whereNotNull('iolinkerid')->groupBy('mac', DB::raw('substr(iolinkerid, 3, 2)'))->pluck('id');
			$doors = Event::select('events.*', 'doors.id as doorid', 'event_names.name as eventname', 'cont.name as controllername', 'cont.iid as iid', 'cont.interfaceid as if', 'cont.pos as pos', 'doors.panel as panel', 'doors.name as doorname', 'credentials.name as credentialname', 'credentials.accesstype', 'credentials.cardnumber', 'io_link_event_names.name as iolinkname')
			->leftJoin('event_names', function($join){
				$join->on('events.taskcode', '=', 'event_names.task')
				->on('events.eventcode', '=', 'event_names.event');
			})
			->leftJoin('io_link_event_names', 'events.iolinkerid', '=', 'io_link_event_names.extra')
			->leftJoin(DB::raw('(select controller_devices.*, events.interfaceid, events.id, substr(events.iolinkerid, 3, 1) as pos from events left join controller_devices on controller_devices.mac = events.mac) AS cont'), function($join){
				$join->on('events.mac', '=', 'cont.mac')
				->on('events.id', '=', 'cont.id');
			})
			->leftJoin('credentials', 'events.uniqueid', '=', 'credentials.uniqueid')
			->leftJoin('doors', function($join){
				$join->on('cont.iid', '=', 'doors.iid')
				->on('cont.interfaceid', '=', 'doors.if')
				->on('cont.pos', '=', 'doors.position');
			})
			->whereNotNull('events.iolinkerid')
			->whereIn('events.id', $maxid)
			->get();

			for($x=1;$x<50;$x++){
				$door[$x] = $doors->where('doorid', $x)->first();
				if(isset($door[$x])){
					if($door[$x]->iolinkerid=="9000;0" || $door[$x]->iolinkerid=="9010;0" || $door[$x]->iolinkerid=="9100;0" || $door[$x]->iolinkerid=="9110;0"){
						$col[$x] = 'green';
						$sta[$x] = "Door NORMAL";
					}else{
						$col[$x] = 'red';
						if($door[$x]->iolinkerid=="9000;1" || $door[$x]->iolinkerid=="9010;1")
							$sta[$x] = "Door FORCED";
						else if($door[$x]->iolinkerid=="9100;1" || $door[$x]->iolinkerid=="9110;1")
							$sta[$x] = "Door HELD";
					}
					$id[$x] = $door[$x]->doorid;
					$name[$x] = $door[$x]->doorname;
					$con[$x] = $door[$x]->iid;
					$if[$x] = $door[$x]->interfaceid;
					$pos[$x] = $door[$x]->pos;
				}else{
					$col[$x] = 'orange';
					$sta[$x] = "Door UNKNOWN";
					$id[$x] = 0;
					$name[$x] = 'Unknown';
					$con[$x] = 'Unknown';
					$if[$x] = 'Unknown';
					$pos[$x] = 'Unknown';
				}
			}*/
			
			
		
			/*$rooms = Room::select('rooms.id as roomid', 'rooms.name as roomname', 'count.iid', 'count.iface', 'last.msgtime', 'count.count',  'trans.msgtime as msgtimes', 'trans.credentialname as credentialsname', 'trans.eventname as accessmethod', 'trans.printednumber as cardnumber')
			->leftJoin(DB::raw('(select count(events.uniqueid)as count, ANY_VALUE(cd.exitid)as exitid, ANY_VALUE(controller_devices.iid)as iid, ANY_VALUE(cd.iface)as iface, ANY_VALUE(events.msgtime)as msgtime from events left join controller_devices on events.mac = controller_devices.mac left join (select readers.name, readers.iface, readers.a, readers.exitid, controller_devices.mac from readers left join controller_devices on readers.iid = controller_devices.iid) AS cd on events.interfaceid = cd.iface and events.readeraddress = cd.a and events.mac = cd.mac where events.id in (SELECT max(id) FROM events where uniqueid is not null group by uniqueid) group by events.mac,interfaceid,readeraddress) AS count'), function($join){
				$join->on('rooms.id', '=', 'count.exitid');
			})
			->leftJoin(DB::raw('(select events.msgtime, credentials.name as credentialname, event_names.name as eventname, credentials.printednumber, cd.exitid from events left join controller_devices on events.mac = controller_devices.mac left join (select readers.name, readers.iface, readers.a, readers.exitid, controller_devices.mac from readers left join controller_devices on readers.iid = controller_devices.iid) AS cd on events.interfaceid = cd.iface and events.readeraddress = cd.a and events.mac = cd.mac left join event_names on events.taskcode = event_names.task and events.eventcode = event_names.event left join credentials on events.uniqueid=credentials.uniqueid where events.id in (SELECT max(id) FROM events where uniqueid is not null group by uniqueid)) AS trans'), function($join){
				$join->on('rooms.id', '=', 'trans.exitid');
			})
			->leftJoin(DB::raw('(select events.msgtime, cd.exitid from events left join controller_devices on events.mac = controller_devices.mac left join (select readers.name, readers.iface, readers.a, readers.exitid, controller_devices.mac from readers left join controller_devices on readers.iid = controller_devices.iid) AS cd on events.interfaceid = cd.iface and events.readeraddress = cd.a and events.mac = cd.mac left join event_names on events.taskcode = event_names.task and events.eventcode = event_names.event where events.id in (SELECT max(id) FROM events where uniqueid is not null)) AS last'), function($join){
				$join->on('rooms.id', '=', 'last.exitid');
			})
			->get();
			
			for($x=1;$x<50;$x++){
				$room[$x] = $rooms->where('roomid', $x)->first();
				if(isset($room[$x])){
					if($room[$x]->count>0)
						$traffic[$x] = $room[$x]->count;
					else
						$traffic[$x] = 0;
				}else
					$traffic[$x] = 0;
			}*/
			
			
		
			return view('pages.monitor.map')
			->with('doors', $doors)
			->with('rooms', $rooms)
			->with('access', $this->access)
			//->with('col', $col)->with('sta', $sta)->with('id', $id)->with('name', $name)->with('con', $con)->with('if', $if)->with('pos', $pos)
			->with('top_menu_sel', 'map');
		}else
			return redirect()->back();
		}else
			return redirect('/login');
	}
	
	public function CCTV(Request $request){
		if(Auth::check()){
		if(in_array("cctv", $this->access)){
			if (isset($request->id)){
				$cctv_detail = Cctv::where('cctvs.id', $request->id)
				->first();
				return $cctv_detail->toJson();
			}else{
				$cctvs = Cctv::all();
			}
				
			return view('pages.monitor.cctv')
			->with('cctvs', $cctvs)
			->with('urlip', $this->urlip)
			->with('top_menu_sel', 'cctv');
		}else
			return redirect()->back();
		}else
			return redirect('/login');
	}

	public function HardwareStatus(){
		return view('pages.monitor.hardware')
		->with('top_menu_sel', 'status');
	}

	public function SiteMonitor(){
		return view('pages.monitor.site')
		->with('top_menu_sel', 'site');
	}

	public function ForceEvent(){
		//Send 1060 to Controller
			$controllers = ControllerDevice::all();
			foreach($controllers as $controller){
				dispatch(new SendMessage('0012;0012;6;%'.$controller->ip));
				dispatch(new SendMessage('0060;0010;%'.$controller->ip));
			}
	}
}
