<?php

namespace App\Http\Controllers\Developer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class LogController extends Controller
{
  
    public function __construct()
    {
      $this->middleware('auth');
    }
    
    public function Index(Request $request){
      if ($request->type == "controller")
        return $this->Controller();
      else if ($request->type == "broadcast")
        return $this->Broadcast();
      else if ($request->type == "queue")
        return $this->Queue();
      else
        return view(pages.developer.log) -> with('log', "Invalid Parameter") -> with('top_menu_sel', "None");
    }
    
    public function RefreshIndex(Request $request){
      if ($request->type == "controller")
        return $this->RefreshController();
        else if ($request->type == "broadcast")
          return $this->RefreshBroadcast();
          else if ($request->type == "queue")
            return $this->RefreshQueue();
            else
              return view(pages.developer.log) -> with('log', "Invalid Parameter") -> with('top_menu_sel', "None");
    }
  
    public function Controller(){
      //Read log from storage
      $log = $this->getLatestControllerLog(1024);
      return view('pages.developer.log') -> with ('log', $log) -> with('top_menu_sel', "None");
    }
    
    public function Broadcast(){
      $log = "Broadcast Log";
      return view('pages.developer.log') -> with ('log', $log) -> with('top_menu_sel', "None");
    }
    
    public function Queue(){
      $log = "Queue Log";
      return view('pages.developer.log') -> with ('log', $log) -> with('top_menu_sel', "None");
    }
    
    public function RefreshController(){
      //Read log from storage      
      $log = $this->getLatestControllerLog(1024);
      return $log;
    }
    
    public function getLatestControllerLog($buffer){
      $log = Storage::get('log/log-controller.log');
      
      return $log;
    }
    
}
