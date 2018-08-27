<?php

namespace App\Http\Controllers\Settings\Device;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Jobs\SendMessage;
use Yajra\Datatables\Datatables;

use App\Model\Settings\ControllerDevice;
use App\Model\Settings\ControllerDeviceSub;
use App\Model\Settings\Reader;
use App\Model\Settings\Door;
use App\Model\Report\Log;
use DB;
use Auth;

class DeviceController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Request $request)
	{
		if (!$request->is("setting/setup/restart/true")) {
			$this->middleware(function ($request, $next){
				if(Auth::check()&&(Auth::user()->access=='Administrator'||Auth::user()->access=='Operator'))
					$this->access = array("show", "edit", "create", "delete", "sync");
				else
					$this->access = array("");
			return $next($request);
			});
		} else {
		}
			
		date_default_timezone_set('Asia/Krasnoyarsk');
		$this->date = date("H:i:s \G\M\T m/d/Y");
	}

	/*
	 * Devices
	 */
	public function Controller(Request $request){
		if(Auth::check()){
		if(in_array("show", $this->access)){			
			if (isset($request->iid)){
				$controller_detail = ControllerDevice::find($request->iid);
				return $controller_detail->toJson();
				exit();
			}
			$controllers = ControllerDevice::all();
			return view('pages.settings.setup.viewcontroller')
			->with('controllers', $controllers)
			->with('top_menu_sel', 'controller_view');
		}else
			return redirect()->back();
		}else
			return redirect('/login');
	}
	
	public function EditController(Request $request){
		if(in_array("edit", $this->access)){
			//Validation
			$this->validate($request, [
				'iid'=>'required|unique:controller_devices,iid,'.$request->oldiid.',iid',
				'name'=>'required',
				'ip'=>'required|unique:controller_devices,ip,'.$request->oldip.',ip',
				'port'=>'required|between:0,65535',
				'mac'=>'required|unique:controller_devices,mac,'.$request->oldmac.',mac',
				'device'=>'required'
			], [
				'iid.required' => 'The IID field is required.',
				'iid.unique' => 'The IID has already been taken.',
				'name.required' => 'The Name field is required.',
				'ip.required' => 'The IP field is required.',
				'ip.unique' => 'The IP has already been taken.',
				'port.required' => 'The Port field is required.',
				'mac.required' => 'The Mac field is required.',
				'mac.unique' => 'The Mac has already been taken.',
				'device.required' => 'The Name field is required.'
			]);
			$controller = ControllerDevice::find($request->oldiid);
			$log = new Log;
			$log->time = $this->date;
			$log->name = Auth::user()->name;
			$log->act = 'EDIT Controller FROM IID '.$controller->iid.', NAME '.$controller->name.', IP '.$controller->ip.', PORT '.$controller->port.', MAC '.$controller->mac.', DOORSTRIKE '.$controller->doorstrike.', DEVICE '.$controller->device.' INTO IID '.$request->iid.', NAME '.$request->name.', IP '.trim($request->ip, '_').', PORT '.$request->port.', MAC '.$request->mac.', DOORSTRIKE '.$request->doorstrike.', DEVICE '.$request->device;
			$log->save();
			$controller->iid = $request->iid;
			$controller->name = $request->name;
			$controller->ip = trim($request->ip, '_');
			$controller->port = $request->port;
			$controller->mac = $request->mac;
			if($request->device!="V1000"){
				$controller->doorstrike = $request->doorstrike;
			}
			$controller->device = $request->device;
			$controller->save();
			$request->type = 'controller'; // for generate file
			if($request->device!="V1000"){
				dispatch(new SendMessage('0007;0023;0;7e02;1;'.$request->doorstrike.';1;%'.$controller->ip));
			}
			$request->session()->flash('message', 'Controller '.$request->name.' (IID: '.$request->iid.') has been successfully edited.');
			$request->session()->flash('iid', $request->iid);
		}else
			return redirect()->back();
	}
	
	public function AddController(){
		if(Auth::check()){
		if(in_array("create", $this->access)){
			return view('pages.settings.setup.addcontroller')
			->with('top_menu_sel', 'controller_add');
		}else
			return redirect()->back();
		}else
			return redirect('/login');
	}
	
	public function NewController(Request $request){
		if(in_array("create", $this->access)){
			//Validation
			$this->validate($request, [
				'iid'=>'required|unique:controller_devices',
				'name'=>'required',
				'ip'=>'required|unique:controller_devices',
				'port'=>'required|between:0,65535',
				'mac'=>'required|unique:controller_devices',
				'device'=>'required'
			], [
				'iid.required' => 'The IID field is required.',
				'iid.unique' => 'The IID has already been taken.',
				'name.required' => 'The Name field is required.',
				'ip.required' => 'The IP field is required.',
				'ip.unique' => 'The IP has already been taken.',
				'port.required' => 'The Port field is required.',
				'mac.required' => 'The Mac field is required.',
				'mac.unique' => 'The Mac has already been taken.',
				'device.required' => 'The Device field is required.'
			]);
			$log = new Log;
			$log->time = $this->date;
			$log->name = Auth::user()->name;
			$log->act = 'ADD Controller WITH IID '.$request->iid.', NAME '.$request->name.', IP '.trim($request->ip, '_').', PORT '.$request->port.', MAC '.$request->mac.', DOORSTRIKE '.$request->doorstrike.', DEVICE '.$request->device;
			$log->save();
			$controller = new ControllerDevice;
			$controller->iid = $request->iid;
			$controller->name = $request->name;
			$controller->ip = trim($request->ip, '_');
			$controller->port = $request->port;
			$controller->mac = $request->mac;
			$controller->doorstrike = $request->doorstrike;
			$controller->device = $request->device;
			$controller->save();
			$request->type = 'controller'; // for generate file
			if($request->device!="V1000"){
				dispatch(new SendMessage('0007;0023;0;7e02;1;'.$request->doorstrike.';1;%'.$controller->ip));
			}
			return redirect('/settings/controller')->with('message', 'Controller has been successfully added.')->with('iid', $request->iid);
		}else
			return redirect()->back();
	}
	
	public function DeleteController(Request $request){
		if(in_array("delete", $this->access)){
			$controller = ControllerDevice::find($request->iid);
			$controllersub = ControllerDeviceSub::where("iid", $request->iid)->first();
			if(isset($controllersub->name)){
				$subcontrollername = $controllersub->name;
			}else{
				$subcontrollername = "None";
			}
			$reader = Reader::where("iid", $request->iid)->first();
			if(isset($reader->name)){
				$readername = $reader->name;
			}else{
				$readername = "None";
			}
			$door = Door::where("iid", $request->iid)->first();
			if(isset($door->name)){
				$doorname = $door->name;
			}else{
				$doorname = "None";
			}
			//Validation
			if($controller->device=="V1000"){
				$this->validate($request, [
					'iid'=>'required|unique:controller_device_subs'
				], [
					'iid.required' => 'The IID field is required.',
					'iid.unique' => 'Remove Sub Controller first with name '.$subcontrollername.'.'
				]);
			}else if($controller->device=="V2000"){
				$this->validate($request, [
					'iid'=>'required|unique:readers'
				], [
					'iid.required' => 'The IID field is required.',
					'iid.unique' => 'Remove Reader first with name '.$readername.'.'
				]);
			}
			$log = new Log;
			$log->time = $this->date;
			$log->name = Auth::user()->name;
			$log->act = 'DELETE Controller REMOVE IID '.$controller->iid.', NAME '.$controller->name.', IP '.$controller->ip.', PORT '.$controller->port.', MAC '.$controller->mac;
			$log->save();
			$controller -> delete();
			DB::statement('ALTER TABLE controller_devices AUTO_INCREMENT = 1');
			$request->type = 'controller'; // for generate file
			$request->session()->flash('message', 'Controller '.$controller->name.' (IID: '.$request->iid.') has been successfully deleted.');
		}else
			return redirect()->back();
	}
	
	public function SyncController(Request $request){
		if(in_array("sync", $this->access)){
			if(isset($request->e)){
				//Flash the error message
				return response($request->e, 422);
			} else {
				//Flassh success message
				$request->session()->flash('message', 'Controller(s) successfully synced');
			}
		}else
			return redirect()->back();
	}

	/*
	 * Restart
	 */
	public function Restart(){
		if(Auth::check()){
		if(in_array("sync", $this->access)){
			$controllers = ControllerDevice::all();
			return view('pages.settings.setup.restart')
			->with('top_menu_sel', 'restart')
			->with('controllers', $controllers);
		}else
			return redirect()->back();
		}else
			return redirect('/login');
	}
	
	public function SendRestart(Request $request){
		foreach($request->iid as $iid){
			$ip = ControllerDevice::Select('ip')->where('iid', $iid)->first();
			foreach($request->task as $task){
				if (strlen($task)>1){
					dispatch(new SendMessage('0012;0013;'.$task.';%'.$ip['ip']));
				} else {
					dispatch(new SendMessage('0012;0012;'.$task.';%'.$ip['ip']));
				}
				$log = new Log;
				$log->time = $this->date;
				$log->name = Auth::user()->name;
				$log->act = 'RESTART Controller WITH IP '.$ip->ip.', TASK '.$task;
				$log->save();
			}
		}
	}
	
	public function AutoRestart(Request $request){
		$controllers = ControllerDevice::all();
		foreach($controllers as $controller){
			dispatch(new SendMessage('0012;0012;2;%'.$controller->ip));
			dispatch(new SendMessage('0012;0012;6;%'.$controller->ip));
		}
	}
}