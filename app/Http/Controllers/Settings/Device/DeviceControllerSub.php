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
use App\Model\Report\Log;
use DB;
use Auth;

class DeviceControllerSub extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware(function ($request, $next){
			if(Auth::check()&&(Auth::user()->access=='Administrator'||Auth::user()->access=='Operator'))
				$this->access = array("show", "edit", "create", "delete");
			else
				$this->access = array("");
		return $next($request);
		});
		
		date_default_timezone_set('Asia/Krasnoyarsk');
		$this->date = date("H:i:s \G\M\T m/d/Y");
	}

	/*
	 * Devices
	 */
	public function ControllerSub(Request $request){
		if(Auth::check()){
		if(in_array("show", $this->access)){			
			if (isset($request->id)){
				$controllersub_detail = ControllerDeviceSub::select("controller_device_subs.*", "controller_devices.name as headcontroller")
				->leftJoin("controller_devices", "controller_device_subs.iid", "controller_devices.iid")
				->where("id", $request->id)
				->first();
				return $controllersub_detail->toJson();
				exit();
			}
			$controllersubs = ControllerDeviceSub::all();
			$controllers = ControllerDevice::where("device", "V1000")->get();
			return view('pages.settings.setup.viewcontrollersub')
			->with('controllersubs', $controllersubs)
			->with('controllers', $controllers)
			->with('top_menu_sel', 'controllersub_view');
		}else
			return redirect()->back();
		}else
			return redirect('/login');
	}
	
	public function EditControllerSub(Request $request){
		if(in_array("edit", $this->access)){
			$controllersub = ControllerDeviceSub::find($request->id);
			//laravel validation can't read 0
			if($request->iface==0 && $request->oldiface==0){
				$request->oldiface = '00';
				$controllersub->iface = '00';
				$controllersub->save();
			}
			$controller = ControllerDevice::where('iid', $controllersub->iid)->first();
			//Validation
			$this->validate($request, [
				'name'=>'required',
				'iid'=>'required',
				'iface'=>'required|unique:controller_device_subs,iface,'.$request->oldiface.',iface,iid,'.$request->iid,
				'device'=>'required'
			], [
				'name.required' => 'The Name field is required.',
				'iid.required' => 'The Head Controller field is required.',
				'iface.required' => 'The Interface field is required.',
				'iface.unique' => 'The Interface with Head Controller '.$controller->name.' has already been taken.',
				'device.required' => 'The Device field is required.'
			]);
			$log = new Log;
			$log->time = $this->date;
			$log->name = Auth::user()->name;
			$log->act = 'EDIT Sub Controller FROM ID '.$controllersub->id.', NAME '.$controllersub->name.', IID '.$controllersub->iid.', IFACE '.$controllersub->iface.', DOORSTRIKE '.$controllersub->doorstrike.', DEVICE '.$controllersub->device.' INTO ID '.$request->id.', NAME '.$request->name.', IID '.$request->iid.', IFACE '.$request->iface.', DOORSTRIKE '.$request->doorstrike.', DEVICE '.$request->device;
			$log->save();
			$controllersub->name = $request->name;
			$controllersub->iid = $request->iid;
			$controllersub->iface = $request->iface;
			if($request->device=="V100"){
				$controllersub->doorstrike = $request->doorstrike;
			}
			$controllersub->device = $request->device;
			$controllersub->save();
			$request->type = 'controller'; // for generate file
			$message = "$request->iface;7e02;1;$request->doorstrike;1;";
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
			if($request->device=="V100"){
				dispatch(new SendMessage('0007;'.$msglen.';'.$message.'%'.$controller->ip));
			}else{
				
			}
			$request->session()->flash('message', 'Sub Controller '.$request->name.' (ID: '.$request->id.') has been successfully edited.');
			$request->session()->flash('id', $request->id);
		}else
			return redirect()->back();	
	}
	
	public function AddControllerSub(){
		if(Auth::check()){
		if(in_array("create", $this->access)){
			$controllers = ControllerDevice::where("device", "V1000")->get();
			return view('pages.settings.setup.addcontrollersub')
			->with('controllers', $controllers)
			->with('top_menu_sel', 'controllersub_add');
		}else
			return redirect()->back();
		}else
			return redirect('/login');
	}
	
	public function NewControllerSub(Request $request){
		if(in_array("create", $this->access)){
			$controller = ControllerDevice::where('iid', $request->iid)->first();
			//Validation
			$this->validate($request, [
				'name'=>'required',
				'iid'=>'required',
				'iface'=>'required|unique:controller_device_subs,iface,NULL,id,iid,'.$request->iid,
				'device'=>'required'
			], [
				'name.required' => 'The Name field is required.',
				'iid.required' => 'The Head Controller field is required.',
				'iface.required' => 'The Interface field is required.',
				'iface.unique' => 'The Interface with Head Controller '.$controller->name.' has already been taken.',
				'device.required' => 'The Device field is required.'
			]);
			$log = new Log;
			$log->time = $this->date;
			$log->name = Auth::user()->name;
			$log->act = 'ADD Sub Controller WITH ID '.$request->id.', NAME '.$request->name.', IID '.$request->iid.', IFACE '.$request->iface.', DOORSTRIKE '.$request->doorstrike.', DEVICE '.$request->device;
			$log->save();
			$controllersub = new ControllerDeviceSub;
			$controllersub->name = $request->name;
			$controllersub->iid = $request->iid;
			$controllersub->iface = $request->iface;
			$controllersub->doorstrike = $request->doorstrike;
			$controllersub->device = $request->device;
			$controllersub->save();
			$request->type = 'controller'; // for generate file
			$message = "$request->iface;7e02;1;$request->doorstrike;1;";
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
			if($request->device=="V100"){
				dispatch(new SendMessage('0007;'.$msglen.';'.$message.'%'.$controller->ip));
			}else{
				
			}
			return redirect('/settings/controllersub')->with('message', 'Controller has been successfully added.')->with('id', $controllersub->id);
		}else
			return redirect()->back();	
	}
	
	public function DeleteControllerSub(Request $request){
		if(in_array("delete", $this->access)){
			//Validation
			$reader = Reader::where("iid", $request->iid)->where("iface", $request->iface)->first();
			if(isset($reader->name)){
				$readername = $reader->name;
			}else{
				$readername = "None";
			}
			$this->validate($request, [
				'id'=>'required',
				'iface'=>'required',
				'iid'=>'required|unique:readers,iid,NULL,id,iface,'.$request->iface,
			], [
				'id.required' => 'The ID field is required.',
				'iface.required' => 'The IFACE field is required.',
				'iid.required' => 'The IID field is required.',
				'iid.unique' => 'Remove Reader first with name '.$readername.'.'
			]);
			$this->validate($request, [
				'id'=>'required'
			]);
			$controllersub = ControllerDeviceSub::find($request->id);
			$log = new Log;
			$log->time = $this->date;
			$log->name = Auth::user()->name;
			$log->act = 'DELETE Sub Controller REMOVE ID '.$controllersub->id.', NAME '.$controllersub->name.', IID '.$controllersub->iid.', IFACE '.$controllersub->iface.', DOORSTRIKE '.$controllersub->doorstrike.', DEVICE '.$controllersub->device;
			$log->save();
			$controllersub -> delete();
			DB::statement('ALTER TABLE controller_device_subs AUTO_INCREMENT = 1');
			$request->type = 'controller'; // for generate file
			$request->session()->flash('message', 'Sub Controller '.$controllersub->name.' (ID: '.$controllersub->id.') has been successfully deleted.');
		}else
			return redirect()->back();	
	}
}