<?php

namespace App\Http\Controllers\Developer;

use Illuminate\Http\Request;
use App\Jobs\SendMessage;
use App\Http\Controllers\Controller;
use App\Model\Settings\ControllerDevice;
use App\Model\Access\Credential;

class SendMessageController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  
  public function Index(Request $request){
    return view('pages.developer.sendmessage') -> with('top_menu_sel', "None");
  }
	
	public function Send(Request $request){
		$controllers = ControllerDevice::all();
		foreach($controllers as $controller){
			$msglen = strlen($request->message) + 10;
			if ($msglen < 10){
				$msglen = "000".$msglen;
			} else if ($msglen < 100) {
				$msglen = "00".$msglen;
			} else if ($msglen < 1000) {
				$msglen = "0".$msglen;
			} else {
				//nothing need to be done;
			}
			dispatch(new SendMessage($request->code.";".$msglen.';'.$request->message.'%'.$controller->ip));
		}
		return redirect('/dev/sendmessage')->with('success');
	}
	
	public function InsertAll(Request $request){
		$credentials = Credential::where('deleted', 0)->get();
		$send_message = "";
		foreach($credentials as $credential){
			//check format to put hex number or card number with facility code
			if($credential->noformat == 0)
				$hexorprintednumber = $credential->printednumber;
			else
				$hexorprintednumber = $credential->cardnumber;
			
			//check 26 bits or 32 bits and check 26 bits with facility 0
			if($credential->cardbits == 26)
				if($credential->cardsetid != 0)
					$cardsetid = $credential->cardsetid;
				else if($request -> noformat == 0 && $credential->cardsetid == 0)
					$cardsetid = 253;
				else
					$cardsetid = 0;
			else if($credential->cardbits == 32)
				$cardsetid = 252;
			else if($credential->cardbits == 36)
				$cardsetid = 251;
			else
				$cardsetid = 0;
			
			$message = "$cardsetid;$credential->noformat;$hexorprintednumber;$credential->pin;$credential->accesstype;$credential->uniqueid;$credential->ag1;$credential->ag2;$credential->ag3;$credential->ag4;$credential->ag5;$credential->ag6;$credential->ag7;$credential->ag8;$credential->passbackexempt;$credential->inputsuppressed;$credential->extendedaccess;$credential->pincmd;$credential->cardissuelevel;$credential->startdate;$credential->enddate;$credential->escortid;$credential->inscheduleelevatorgroup;$credential->outscheduleelevatorgroup;$credential->outputgroupmode;$credential->pinexempt;$credential->role;";
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
			$send_message .= "0027;$msglen;$message";
		}
		$controllers = ControllerDevice::all();
		echo $send_message;
		foreach($controllers as $controller){
			dispatch(new SendMessage("$send_message%".$controller->ip));
		}
		$request->session()->flash('message', 'Insert All Credential Success');
	}
		
	public function ResetCredential(Request $request){
		if(isset($request->e)){
			//Flash the error message
			return response($request->e, 422);
		} else {
			//Flash success message
			$request->session()->flash('message', 'All credentials has been emptied');
		}
	}
}
