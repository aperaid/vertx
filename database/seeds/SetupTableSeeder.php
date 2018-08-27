<?php

use Illuminate\Database\Seeder;
use App\Model\Settings\ControllerDevice;
use App\Model\Settings\ControllerDeviceSub;
use App\Model\Settings\IoLinker;
use App\Model\Settings\Reader;
use App\Model\Settings\Room;
use App\Model\Settings\Door;
use App\Model\Settings\AccessGroup;
use App\Model\Settings\DoorGroup;
use App\Model\Settings\Role;
use App\Model\Settings\MultimanRule;
use App\Model\Settings\Schedule;
use App\Model\Access\Credential;
use App\Model\Access\FacilityCode;
use App\Model\Addon\MusteringPoint\MusteringPoint;
use Illuminate\Support\Facades\DB;

class SetupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
			//set company name BCA or unilever
			$company = "other";
			
      ControllerDevice::truncate();
      ControllerDeviceSub::truncate();
      IoLinker::truncate();
      Reader::truncate();
			Room::truncate();
      Door::truncate();
      AccessGroup::truncate();
      DoorGroup::truncate();
      Role::truncate();
      MultimanRule::truncate();
      Schedule::truncate();
			Credential::truncate();
			FacilityCode::truncate();
			MusteringPoint::truncate();
			if($company=="unilever"){
				ControllerDevice::insert([
					['iid'=>'1','name'=>'Controller 1','ip'=>'192.168.100.91','port'=>'4050','mac' => '00:06:8E:02:04:D1', 'doorstrike' => '0', 'device' => 'V2000'],
					['iid'=>'2','name'=>'Controller 2','ip'=>'192.168.100.92','port'=>'4050','mac' => '00:06:8E:02:04:D2', 'doorstrike' => '0', 'device' => 'V2000'],
					['iid'=>'3','name'=>'Controller 3','ip'=>'192.168.100.93','port'=>'4050','mac' => '00:06:8E:02:04:D3', 'doorstrike' => '0', 'device' => 'V2000'],
					['iid'=>'4','name'=>'Controller 4','ip'=>'192.168.100.94','port'=>'4050','mac' => '00:06:8E:02:04:D4', 'doorstrike' => '0', 'device' => 'V2000'],
					['iid'=>'5','name'=>'Controller 5','ip'=>'192.168.100.95','port'=>'4050','mac' => '00:06:8E:02:04:D5', 'doorstrike' => '0', 'device' => 'V2000'],
				]);
			}else{
				ControllerDevice::insert([
					['iid'=>'1','name'=>'Controller 1','ip'=>'192.168.100.99','port'=>'4050','mac' => '00:06:8E:02:CA:7B', 'doorstrike' => '0', 'device' => 'V2000'],
				]);
			}
			/*ControllerDeviceSub::insert([
				['id'=>'1','name'=>'V100 Default','iid'=>'1','iface'=>'0', 'doorstrike' => '0','device' => 'V100']
			]);*/
			IoLinker::insert([
				['id'=>'1', 'iid' => '1','name'=>'Over Time','lhs'=>'OM(0,7)','rhs'=>'(E(2,36) & I(1,0,6)) | (E(2,24) & I(1,0,6))', 'date' => '09:00:00 GMT 08/17/2015','notes' => 'Allow exit if credential or schedules already expired but not allowing to enter the same door after exit','status'=>'1'],
				['id'=>'2', 'iid' => '1','name'=>'Buzzer Held Door 1 IID 1','lhs'=>'O(0,1)','rhs'=>'I(1,0,24)&L(9100)', 'date' => '09:00:00 GMT 08/17/2015','notes' => 'When hold door 1 triggerred, Buzzer on Aux Relay 1 will be executed','status'=>'1'],
				['id'=>'3', 'iid' => '1','name'=>'Buzzer Held Door 2 IID 1','lhs'=>'O(0,17)','rhs'=>'I(1,0,26)&L(9110)', 'date' => '09:00:00 GMT 08/17/2015','notes' => 'When hold door 2 triggerred, Buzzer on Aux Relay 2 will be executed','status'=>'1'],
				['id'=>'4', 'iid' => '1','name'=>'Buzzer Force Door 1 IID 1','lhs'=>'O(0,1)','rhs'=>'I(1,0,25)', 'date' => '09:00:00 GMT 08/17/2015','notes' => 'When force door 1 triggerred, Buzzer on Aux Relay 1 will be executed','status'=>'1'],
				['id'=>'5', 'iid' => '1','name'=>'Buzzer Force Door 2 IID 1','lhs'=>'O(0,17)','rhs'=>'I(1,0,27)', 'date' => '09:00:00 GMT 08/17/2015','notes' => 'When force door 2 triggerred, Buzzer on Aux Relay 2 will be executed','status'=>'1'],
				['id'=>'6', 'iid' => '1','name'=>'Normalize Force Door 1 IID 1','lhs'=>'O(0,11)','rhs'=>'~I(1,0,25)', 'date' => '09:00:00 GMT 08/17/2015','notes' => 'Normalize force door 1 alarm without acknowledge the alarm, just close the door','status'=>'1'],
				['id'=>'7', 'iid' => '1','name'=>'Normalize Force Door 2 IID 1','lhs'=>'O(0,27)','rhs'=>'~I(1,0,27)', 'date' => '09:00:00 GMT 08/17/2015','notes' => 'Normalize force door 2 alarm without acknowledge the alarm, just close the door','status'=>'1'],
			]);
			if($company=="unilever"){
				Reader::insert([
					['id'=>'01','name'=>'1R01','iid'=>'1','iface'=>'0','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'2','lkup'=>'0','multiman' => '0'],
					['id'=>'02','name'=>'1R02','iid'=>'1','iface'=>'0','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'2','lkup'=>'0','multiman' => '0'],
					['id'=>'03','name'=>'1R03','iid'=>'1','iface'=>'1','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'2','lkup'=>'0','multiman' => '0'],
					['id'=>'04','name'=>'1R04','iid'=>'1','iface'=>'1','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'2','lkup'=>'0','multiman' => '0'],
					['id'=>'05','name'=>'1R05','iid'=>'1','iface'=>'2','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'2','lkup'=>'0','multiman' => '0'],
					['id'=>'06','name'=>'1R06','iid'=>'1','iface'=>'2','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'2','lkup'=>'0','multiman' => '0'],
					['id'=>'07','name'=>'1R07','iid'=>'1','iface'=>'3','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'2','lkup'=>'0','multiman' => '0'],
					['id'=>'08','name'=>'1R08','iid'=>'1','iface'=>'3','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'2','lkup'=>'0','multiman' => '0'],
					['id'=>'09','name'=>'1R09','iid'=>'1','iface'=>'4','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'2','lkup'=>'0','multiman' => '0'],
					['id'=>'10','name'=>'1R10','iid'=>'1','iface'=>'4','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'2','lkup'=>'0','multiman' => '0'],
					['id'=>'11','name'=>'1R11','iid'=>'1','iface'=>'5','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'2','lkup'=>'0','multiman' => '0'],
					['id'=>'12','name'=>'1R12','iid'=>'1','iface'=>'5','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'2','lkup'=>'0','multiman' => '0'],
					['id'=>'13','name'=>'1R13','iid'=>'1','iface'=>'6','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'2','lkup'=>'0','multiman' => '0'],
					['id'=>'14','name'=>'1R14','iid'=>'1','iface'=>'6','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'2','lkup'=>'0','multiman' => '0'],
					['id'=>'15','name'=>'1R15','iid'=>'1','iface'=>'7','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'2','lkup'=>'0','multiman' => '0'],
					['id'=>'16','name'=>'1R16','iid'=>'1','iface'=>'7','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'2','lkup'=>'0','multiman' => '0'],
					['id'=>'17','name'=>'2R01','iid'=>'2','iface'=>'0','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'3','lkup'=>'0','multiman' => '0'],
					['id'=>'18','name'=>'2R02','iid'=>'2','iface'=>'0','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'3','lkup'=>'0','multiman' => '0'],
					['id'=>'19','name'=>'2R03','iid'=>'2','iface'=>'1','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'3','lkup'=>'0','multiman' => '0'],
					['id'=>'20','name'=>'2R04','iid'=>'2','iface'=>'1','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'3','lkup'=>'0','multiman' => '0'],
					['id'=>'21','name'=>'2R05','iid'=>'2','iface'=>'2','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'3','lkup'=>'0','multiman' => '0'],
					['id'=>'22','name'=>'2R06','iid'=>'2','iface'=>'2','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'3','lkup'=>'0','multiman' => '0'],
					['id'=>'23','name'=>'2R07','iid'=>'2','iface'=>'3','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'3','lkup'=>'0','multiman' => '0'],
					['id'=>'24','name'=>'2R08','iid'=>'2','iface'=>'3','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'3','lkup'=>'0','multiman' => '0'],
					['id'=>'25','name'=>'2R09','iid'=>'2','iface'=>'4','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'3','lkup'=>'0','multiman' => '0'],
					['id'=>'26','name'=>'2R10','iid'=>'2','iface'=>'4','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'3','lkup'=>'0','multiman' => '0'],
					['id'=>'27','name'=>'2R11','iid'=>'2','iface'=>'5','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'3','lkup'=>'0','multiman' => '0'],
					['id'=>'28','name'=>'2R12','iid'=>'2','iface'=>'5','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'3','lkup'=>'0','multiman' => '0'],
					['id'=>'29','name'=>'2R13','iid'=>'2','iface'=>'6','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'3','lkup'=>'0','multiman' => '0'],
					['id'=>'30','name'=>'2R14','iid'=>'2','iface'=>'6','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'3','lkup'=>'0','multiman' => '0'],
					['id'=>'31','name'=>'2R15','iid'=>'2','iface'=>'7','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'3','lkup'=>'0','multiman' => '0'],
					['id'=>'32','name'=>'2R16','iid'=>'2','iface'=>'7','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'3','lkup'=>'0','multiman' => '0'],
					['id'=>'33','name'=>'3R01','iid'=>'3','iface'=>'0','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'4','lkup'=>'0','multiman' => '0'],
					['id'=>'34','name'=>'3R02','iid'=>'3','iface'=>'0','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'4','lkup'=>'0','multiman' => '0'],
					['id'=>'35','name'=>'3R03','iid'=>'3','iface'=>'1','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'4','lkup'=>'0','multiman' => '0'],
					['id'=>'36','name'=>'3R04','iid'=>'3','iface'=>'1','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'4','lkup'=>'0','multiman' => '0'],
					['id'=>'37','name'=>'3R05','iid'=>'3','iface'=>'2','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'4','lkup'=>'0','multiman' => '0'],
					['id'=>'38','name'=>'3R06','iid'=>'3','iface'=>'2','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'4','lkup'=>'0','multiman' => '0'],
					['id'=>'39','name'=>'3R07','iid'=>'3','iface'=>'3','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'4','lkup'=>'0','multiman' => '0'],
					['id'=>'40','name'=>'3R08','iid'=>'3','iface'=>'3','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'4','lkup'=>'0','multiman' => '0'],
					['id'=>'41','name'=>'3R09','iid'=>'3','iface'=>'4','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'4','lkup'=>'0','multiman' => '0'],
					['id'=>'42','name'=>'3R10','iid'=>'3','iface'=>'4','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'4','lkup'=>'0','multiman' => '0'],
					['id'=>'43','name'=>'3R11','iid'=>'3','iface'=>'5','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'4','lkup'=>'0','multiman' => '0'],
					['id'=>'44','name'=>'3R12','iid'=>'3','iface'=>'5','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'4','lkup'=>'0','multiman' => '0'],
					['id'=>'45','name'=>'3R13','iid'=>'3','iface'=>'6','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'4','lkup'=>'0','multiman' => '0'],
					['id'=>'46','name'=>'3R14','iid'=>'3','iface'=>'6','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'4','lkup'=>'0','multiman' => '0'],
					['id'=>'47','name'=>'3R15','iid'=>'3','iface'=>'7','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'4','lkup'=>'0','multiman' => '0'],
					['id'=>'48','name'=>'3R16','iid'=>'3','iface'=>'7','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'4','lkup'=>'0','multiman' => '0'],
					['id'=>'49','name'=>'4R01','iid'=>'4','iface'=>'0','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'5','lkup'=>'0','multiman' => '0'],
					['id'=>'50','name'=>'4R02','iid'=>'4','iface'=>'0','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'5','lkup'=>'0','multiman' => '0'],
					['id'=>'51','name'=>'4R03','iid'=>'4','iface'=>'1','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'5','lkup'=>'0','multiman' => '0'],
					['id'=>'52','name'=>'4R04','iid'=>'4','iface'=>'1','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'5','lkup'=>'0','multiman' => '0'],
					['id'=>'53','name'=>'4R05','iid'=>'4','iface'=>'2','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'5','lkup'=>'0','multiman' => '0'],
					['id'=>'54','name'=>'4R06','iid'=>'4','iface'=>'2','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'5','lkup'=>'0','multiman' => '0'],
					['id'=>'55','name'=>'4R07','iid'=>'4','iface'=>'3','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'5','lkup'=>'0','multiman' => '0'],
					['id'=>'56','name'=>'4R08','iid'=>'4','iface'=>'3','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'5','lkup'=>'0','multiman' => '0'],
					['id'=>'57','name'=>'4R09','iid'=>'4','iface'=>'4','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'5','lkup'=>'0','multiman' => '0'],
					['id'=>'58','name'=>'4R10','iid'=>'4','iface'=>'4','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'5','lkup'=>'0','multiman' => '0'],
					['id'=>'59','name'=>'4R11','iid'=>'4','iface'=>'5','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'5','lkup'=>'0','multiman' => '0'],
					['id'=>'60','name'=>'4R12','iid'=>'4','iface'=>'5','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'5','lkup'=>'0','multiman' => '0'],
					['id'=>'61','name'=>'4R13','iid'=>'4','iface'=>'6','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'5','lkup'=>'0','multiman' => '0'],
					['id'=>'62','name'=>'4R14','iid'=>'4','iface'=>'6','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'5','lkup'=>'0','multiman' => '0'],
					['id'=>'63','name'=>'4R15','iid'=>'4','iface'=>'7','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'5','lkup'=>'0','multiman' => '0'],
					['id'=>'64','name'=>'4R16','iid'=>'4','iface'=>'7','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'5','lkup'=>'0','multiman' => '0'],
					['id'=>'65','name'=>'OutToIn','iid'=>'5','iface'=>'0','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '1','exitid'=>'6','lkup'=>'0','multiman' => '0'],
					['id'=>'66','name'=>'InToOut','iid'=>'5','iface'=>'0','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '6','exitid'=>'1','lkup'=>'0','multiman' => '0'],
				]);
			}else{
				Reader::insert([
					['id'=>'1','name'=>'Reader 1','iid'=>'1','iface'=>'0','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '1','exitid'=>'2','lkup'=>'0','multiman' => '0'],
					['id'=>'2','name'=>'Reader 2','iid'=>'1','iface'=>'0','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '2','exitid'=>'1','lkup'=>'0','multiman' => '0'],
					//['id'=>'3','name'=>'Reader 3','iid'=>'2','iface'=>'0','a' => '0', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '0','exitid'=>'0','lkup'=>'0','multiman' => '0'],
					//['id'=>'4','name'=>'Reader 4','iid'=>'2','iface'=>'0','a' => '1', 'am'=>'5','psup'=>'0','pincmd'=>'0','rdrtype' => '1','elevator' => '0', 'apbtype'=>'0','tmout'=>'0','apbact'=>'0','entryid' => '0','exitid'=>'0','lkup'=>'0','multiman' => '0'],
				]);
			}
			if($company=="unilever"){
				Room::insert([
					['id'=>'1','name'=>'Outside'],
					['id'=>'2','name'=>'Mustering Point 1'],
					['id'=>'3','name'=>'Mustering Point 2'],
					['id'=>'4','name'=>'Mustering Point 3'],
					['id'=>'5','name'=>'Mustering Point 4'],
				]);
			}else{
				Room::insert([
					['id'=>'1','name'=>'Outside'],
					['id'=>'2','name'=>'Room 1'],
				]);
			}
      Door::insert([
        ['name'=>'Door 1','iid'=>'1','if'=>'0','position' => '0','brands' => 'HID', 'panel'=>'Panel 1','ulock'=>'6','held'=>'20','disable' => '0','force' => '1','hold' => '1'],
        //['name'=>'Door 2','iid'=>'1','if'=>'0','position' => '1','brands' => 'HID', 'panel'=>'Panel 1','ulock'=>'5','held'=>'30','disable' => '0','force' => '1','hold' => '1'],
      ]);
      AccessGroup::insert([
        ['id'=>'0','name'=>'None','d1'=>'0','s1'=>'0','d2'=>'0','s2'=>'0','d3'=>'0','s3'=>'0','d4'=>'0','s4'=>'0','d5'=>'0','s5'=>'0','d6'=>'0','s6'=>'0','d7'=>'0','s7'=>'0','d8'=>'0','s8'=>'0','d9'=>'0','s9'=>'0','d10'=>'0','s10'=>'0','d11'=>'0','s11'=>'0','d12'=>'0','s12'=>'0','d13'=>'0','s13'=>'0','d14'=>'0','s14'=>'0','d15'=>'0','s15'=>'0','d16'=>'0','s16'=>'0','d17'=>'0','s17'=>'0','d18'=>'0','s18'=>'0','d19'=>'0','s19'=>'0','d20'=>'0','s20'=>'0','d21'=>'0','s21'=>'0','d22'=>'0','s22'=>'0','d23'=>'0','s23'=>'0','d24'=>'0','s24'=>'0','d25'=>'0','s25'=>'0','d26'=>'0','s26'=>'0','d27'=>'0','s27'=>'0','d28'=>'0','s28'=>'0','d29'=>'0','s29'=>'0','d30'=>'0','s30'=>'0','d31'=>'0','s31'=>'0','d32'=>'0','s32'=>'0','d33'=>'0','s33'=>'0','d34'=>'0','s34'=>'0','d35'=>'0','s35'=>'0','d36'=>'0','s36'=>'0','d37'=>'0','s37'=>'0','d38'=>'0','s38'=>'0','d39'=>'0','s39'=>'0','d40'=>'0','s40'=>'0'],
      ]);
      DB::statement('UPDATE access_groups SET id="0" WHERE id=1;');
			if($company=="unilever"){
				AccessGroup::insert([
					['id'=>'1','name'=>'Mustering Point Access Group','d1'=>'1','s1'=>'1','d2'=>'2','s2'=>'1','d3'=>'3','s3'=>'1','d4'=>'4','s4'=>'1','d5'=>'0','s5'=>'0','d6'=>'0','s6'=>'0','d7'=>'0','s7'=>'0','d8'=>'0','s8'=>'0','d9'=>'0','s9'=>'0','d10'=>'0','s10'=>'0','d11'=>'0','s11'=>'0','d12'=>'0','s12'=>'0','d13'=>'0','s13'=>'0','d14'=>'0','s14'=>'0','d15'=>'0','s15'=>'0','d16'=>'0','s16'=>'0','d17'=>'0','s17'=>'0','d18'=>'0','s18'=>'0','d19'=>'0','s19'=>'0','d20'=>'0','s20'=>'0','d21'=>'0','s21'=>'0','d22'=>'0','s22'=>'0','d23'=>'0','s23'=>'0','d24'=>'0','s24'=>'0','d25'=>'0','s25'=>'0','d26'=>'0','s26'=>'0','d27'=>'0','s27'=>'0','d28'=>'0','s28'=>'0','d29'=>'0','s29'=>'0','d30'=>'0','s30'=>'0','d31'=>'0','s31'=>'0','d32'=>'0','s32'=>'0','d33'=>'0','s33'=>'0','d34'=>'0','s34'=>'0','d35'=>'0','s35'=>'0','d36'=>'0','s36'=>'0','d37'=>'0','s37'=>'0','d38'=>'0','s38'=>'0','d39'=>'0','s39'=>'0','d40'=>'0','s40'=>'0']
				]);
			}else{
				AccessGroup::insert([
					['id'=>'1','name'=>'AG1','d1'=>'1','s1'=>'1','d2'=>'0','s2'=>'0','d3'=>'0','s3'=>'0','d4'=>'0','s4'=>'0','d5'=>'0','s5'=>'0','d6'=>'0','s6'=>'0','d7'=>'0','s7'=>'0','d8'=>'0','s8'=>'0','d9'=>'0','s9'=>'0','d10'=>'0','s10'=>'0','d11'=>'0','s11'=>'0','d12'=>'0','s12'=>'0','d13'=>'0','s13'=>'0','d14'=>'0','s14'=>'0','d15'=>'0','s15'=>'0','d16'=>'0','s16'=>'0','d17'=>'0','s17'=>'0','d18'=>'0','s18'=>'0','d19'=>'0','s19'=>'0','d20'=>'0','s20'=>'0','d21'=>'0','s21'=>'0','d22'=>'0','s22'=>'0','d23'=>'0','s23'=>'0','d24'=>'0','s24'=>'0','d25'=>'0','s25'=>'0','d26'=>'0','s26'=>'0','d27'=>'0','s27'=>'0','d28'=>'0','s28'=>'0','d29'=>'0','s29'=>'0','d30'=>'0','s30'=>'0','d31'=>'0','s31'=>'0','d32'=>'0','s32'=>'0','d33'=>'0','s33'=>'0','d34'=>'0','s34'=>'0','d35'=>'0','s35'=>'0','d36'=>'0','s36'=>'0','d37'=>'0','s37'=>'0','d38'=>'0','s38'=>'0','d39'=>'0','s39'=>'0','d40'=>'0','s40'=>'0']
				]);
			}
			if($company=="unilever"){
				DoorGroup::insert([
					['id'=>'1','name'=>'Mustering Point Door Group 1','dgcount'=>'16','r1'=>'1','r2' => '2', 'r3'=>'3','r4'=>'4','r5'=>'5','r6' => '6', 'r7'=>'7','r8'=>'8','r9'=>'9','r10' => '10', 'r11'=>'11','r12'=>'12','r13'=>'13','r14' => '14', 'r15'=>'15','r16'=>'16'],
					['id'=>'2','name'=>'Mustering Point Door Group 2','dgcount'=>'16','r17'=>'17','r18' => '18', 'r19'=>'19','r20'=>'20','r21'=>'21','r22' => '22', 'r23'=>'23','r24'=>'24','r25'=>'25','r26' => '26', 'r27'=>'27','r28'=>'28','r29'=>'29','r30' => '30', 'r31'=>'31','r32'=>'32'],
					['id'=>'3','name'=>'Mustering Point Door Group 3','dgcount'=>'16','r33'=>'33','r34' => '34', 'r35'=>'35','r36'=>'36','r37'=>'37','r38' => '38', 'r39'=>'39','r40'=>'40','r41'=>'41','r42' => '42', 'r43'=>'43','r44'=>'44','r45'=>'45','r46' => '46', 'r47'=>'47','r48'=>'48'],
					['id'=>'4','name'=>'Mustering Point Door Group 4','dgcount'=>'16','r49'=>'49','r50' => '50', 'r51'=>'51','r52'=>'52','r53'=>'53','r54' => '54', 'r55'=>'55','r56'=>'56','r57'=>'57','r58' => '58', 'r59'=>'59','r60'=>'60','r61'=>'61','r62' => '62', 'r63'=>'63','r64'=>'64']
				]);
			}else if($company=="BCA"){
				DoorGroup::insert([
					['id'=>'1','name'=>'ATL','dgcount'=>'2','r1'=>'1','r2' => '2'],
					['id'=>'2','name'=>'Bussiness Unit','dgcount'=>'2','r1'=>'3','r2' => '4'],
					['id'=>'3','name'=>'Network','dgcount'=>'2','r1'=>'5','r2' => '6'],
					['id'=>'4','name'=>'Storage','dgcount'=>'2','r1'=>'1','r2' => '2'],
					['id'=>'5','name'=>'SUN','dgcount'=>'2','r1'=>'1','r2' => '2'],
					['id'=>'6','name'=>'Tandem','dgcount'=>'2','r1'=>'1','r2' => '2'],
				]);
			}else{
				DoorGroup::insert([
					['id'=>'1','name'=>'DG1','dgcount'=>'2','r1'=>'1','r2'=>'2','r3'=>'0','r4'=>'0','r5'=>'0','r6'=>'0','r7'=>'0','r8'=>'0','r9'=>'0','r10'=>'0','r11'=>'0','r12'=>'0','r13'=>'0','r14'=>'0','r15'=>'0','r16'=>'0','r17'=>'0','r18'=>'0','r19'=>'0','r20'=>'0','r21'=>'0','r22'=>'0','r23'=>'0','r24'=>'0','r25'=>'0','r26'=>'0','r27'=>'0','r28'=>'0','r29'=>'0','r30'=>'0','r31'=>'0','r32'=>'0','r33'=>'0','r34'=>'0','r35'=>'0','r36'=>'0','r37'=>'0','r38'=>'0','r39'=>'0','r40'=>'0']
				]);
			}
      Role::insert([
        ['id'=>'0','name'=>'No Role'],
      ]);
      DB::statement('UPDATE roles SET id="0" WHERE id=1;');
      Role::insert([
        ['id'=>'1','name'=>'Supervisor'],
        ['id'=>'2','name'=>'Employee']
      ]);
      MultimanRule::insert([
        ['id'=>'1','name'=>'Supervisor + Employee','preserveseq'=>'0','tmout'=>'10','type'=>'0','rolecount'=>'2','role1'=>'1','role2'=>'2','role3'=>'0','role4'=>'0','role5'=>'0','role6'=>'0','role7'=>'0','role8'=>'0']
      ]);
      Schedule::insert([
        ['id'=>'1','schedid'=>'1','name'=>'All Day','definition'=>'i','day'=>'0','interval'=>'1','h1'=>'00','m1'=>'00','s1'=>'00','h2'=>'23','m2'=>'59','s2'=>'59'],
        ['id'=>'2','schedid'=>'1','name'=>'All Day','definition'=>'i','day'=>'1','interval'=>'1','h1'=>'00','m1'=>'00','s1'=>'00','h2'=>'23','m2'=>'59','s2'=>'59'],
        ['id'=>'3','schedid'=>'1','name'=>'All Day','definition'=>'i','day'=>'2','interval'=>'1','h1'=>'00','m1'=>'00','s1'=>'00','h2'=>'23','m2'=>'59','s2'=>'59'],
        ['id'=>'4','schedid'=>'1','name'=>'All Day','definition'=>'i','day'=>'3','interval'=>'1','h1'=>'00','m1'=>'00','s1'=>'00','h2'=>'23','m2'=>'59','s2'=>'59'],
        ['id'=>'5','schedid'=>'1','name'=>'All Day','definition'=>'i','day'=>'4','interval'=>'1','h1'=>'00','m1'=>'00','s1'=>'00','h2'=>'23','m2'=>'59','s2'=>'59'],
        ['id'=>'6','schedid'=>'1','name'=>'All Day','definition'=>'i','day'=>'5','interval'=>'1','h1'=>'00','m1'=>'00','s1'=>'00','h2'=>'23','m2'=>'59','s2'=>'59'],
        ['id'=>'7','schedid'=>'1','name'=>'All Day','definition'=>'i','day'=>'6','interval'=>'1','h1'=>'00','m1'=>'00','s1'=>'00','h2'=>'23','m2'=>'59','s2'=>'59']
      ]);
			if($company=="BCA"){
				Credential::insert([
					['id'=>'1','name'=>'BCATEMP1','nip'=>'','cardsetid'=>'0','noformat'=>'1','cardbits'=>'0','cardnumber'=>'00C600D9','pin'=>'0','accesstype'=>'1','uniqueid'=>'1','deleted'=>'0','ag1'=>'0','ag2'=>'0','ag3'=>'0','ag4'=>'0','ag5'=>'0','ag6'=>'0','ag7'=>'0','ag8'=>'0','passbackexempt'=>'0','inputsuppressed'=>'0','extendedaccess'=>'0','accessdeleted'=>'0','pincmd'=>'0','cardissuelevel'=>'0','startdate'=>'0','enddate'=>'0','escortid'=>'0','inscheduleelevatorgroup'=>'0','outscheduleelevatorgroup'=>'0','outputgroupmode'=>'0','pinexempt'=>'0','role'=>'0','email'=>'','address'=>'','workplace'=>'','department'=>'','position'=>'','printednumber'=>'00108','issuedon'=>'02/25/17','photo'=>''],
					['id'=>'2','name'=>'TRIYA ROSYADI .','nip'=>'51471','cardsetid'=>'0','noformat'=>'1','cardbits'=>'0','cardnumber'=>'00C60114','pin'=>'0','accesstype'=>'1','uniqueid'=>'2','deleted'=>'0','ag1'=>'0','ag2'=>'0','ag3'=>'0','ag4'=>'0','ag5'=>'0','ag6'=>'0','ag7'=>'0','ag8'=>'0','passbackexempt'=>'0','inputsuppressed'=>'0','extendedaccess'=>'0','accessdeleted'=>'0','pincmd'=>'0','cardissuelevel'=>'0','startdate'=>'0','enddate'=>'0','escortid'=>'0','inscheduleelevatorgroup'=>'0','outscheduleelevatorgroup'=>'0','outputgroupmode'=>'0','pinexempt'=>'0','role'=>'0','email'=>'','address'=>'','workplace'=>'','department'=>'','position'=>'','printednumber'=>'51471','issuedon'=>'02/25/17','photo'=>''],
					['id'=>'3','name'=>'JINWEI .','nip'=>'51512','cardsetid'=>'0','noformat'=>'1','cardbits'=>'0','cardnumber'=>'00C60058','pin'=>'0','accesstype'=>'1','uniqueid'=>'3','deleted'=>'0','ag1'=>'0','ag2'=>'0','ag3'=>'0','ag4'=>'0','ag5'=>'0','ag6'=>'0','ag7'=>'0','ag8'=>'0','passbackexempt'=>'0','inputsuppressed'=>'0','extendedaccess'=>'0','accessdeleted'=>'0','pincmd'=>'0','cardissuelevel'=>'0','startdate'=>'0','enddate'=>'0','escortid'=>'0','inscheduleelevatorgroup'=>'0','outscheduleelevatorgroup'=>'0','outputgroupmode'=>'0','pinexempt'=>'0','role'=>'0','email'=>'','address'=>'','workplace'=>'','department'=>'','position'=>'','printednumber'=>'51512','issuedon'=>'02/25/17','photo'=>''],
					['id'=>'4','name'=>'ANGGA PRAMUDITA .','nip'=>'52275','cardsetid'=>'0','noformat'=>'1','cardbits'=>'0','cardnumber'=>'00C6014B','pin'=>'0','accesstype'=>'1','uniqueid'=>'4','deleted'=>'0','ag1'=>'0','ag2'=>'0','ag3'=>'0','ag4'=>'0','ag5'=>'0','ag6'=>'0','ag7'=>'0','ag8'=>'0','passbackexempt'=>'0','inputsuppressed'=>'0','extendedaccess'=>'0','accessdeleted'=>'0','pincmd'=>'0','cardissuelevel'=>'0','startdate'=>'0','enddate'=>'0','escortid'=>'0','inscheduleelevatorgroup'=>'0','outscheduleelevatorgroup'=>'0','outputgroupmode'=>'0','pinexempt'=>'0','role'=>'0','email'=>'','address'=>'','workplace'=>'','department'=>'','position'=>'','printednumber'=>'52275','issuedon'=>'02/25/17','photo'=>''],
				]);
			}else{
				
			}
      FacilityCode::insert([
        ['id'=>'1','facilitycode'=>'99'],
      ]);
      MusteringPoint::insert([
        ['id'=>'1','name'=>'Outside','roomid'=>'1'],
      ]);
    }
}
