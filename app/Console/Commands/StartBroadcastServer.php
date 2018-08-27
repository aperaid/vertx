<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Settings\ControllerDevice;
use App\Model\Monitor\Event\EventName;
use App\Model\Access\Credential;
use App\Model\Settings\Door;
use App\Model\Monitor\Event\Event;
use App\Model\Monitor\Event\IoLinkEventName;
use App\Model\Settings\Reader;
use App\Model\Settings\Room;
use App\Model\Addon\Telegram\TelegramAccount;
use DB;
use DateTime;

class StartBroadcastServer extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:broadcast';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start A S P H A Broadcast Server';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    private $clients;

    public function handle()
    {
			$host = 'localhost'; // host
			$port = '9000'; // port
			$null = NULL; // null var
										
			echo("========Starting=========\n");
			// Create TCP/IP sream socket
      echo("1. Creating socket...\n");
      $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
			// Socket option
      echo("2. Setting socket options...\n");
      socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
			// Bind socket
      echo("3. Binding socket...\n");
      socket_bind($socket, 0, $port);
			// listen to port
      echo("4. Start Listener...\n");
      socket_listen($socket);
			//echo "socket: $socket created\n";
			
			// create & add listening socket to the list
			$this->clients = array($socket);
			
      echo("Controller broadcast is started\n");
      echo("=========================\n");
			// start endless loop, so that our script doesn't stop
			while (true) {
					// manage multipal connections
					$changed = $this->clients;
					// returns the socket resources in $changed array
					socket_select($changed, $null, $null, 0, $null);
					/*
					 * How to read this if below:
					 * "Is someone trying to connect?"
					 */
					if (in_array($socket, $changed)) {
							$socket_new = socket_accept($socket); // accept new socket
							socket_getpeername($socket_new, $ip); // get ip address of connected socket
							echo "IP $ip with $socket_new is connected\n";
							array_push($this->clients, $socket_new);// add socket to client array
							$header = @socket_read($socket_new, 2048); // read data sent by the socket
							$isserver = json_decode($header);
							/*If server is sending message, meaning $isserver->name is set
							 *Then prepare the broadcast message, and broadcast it
							 */
							if (isset($isserver->name) && $isserver->name == "server") {
									// Fetch Controller Name
									$controller = ControllerDevice::select('name', 'iid')->where('mac', $isserver->controller)->first();
									if (! isset($controller)) {
											echo "Controller not found\n";
											$controller = (object) array(
													'name' => null,
													'iid' => null
											);
									}
									
									// Fetch Reader Name
									$reader = Reader::select('name', 'exitid')->where('iid', $controller->iid)
											->where('iface', $isserver->iface)
											->where('a', $isserver->readeraddress)
											->first();
									if (! isset($reader)) {
											echo "Reader not found\n";
											$reader = (object) array(
													'name' => null
											);
									}else{
										if(isset($reader->exitid)){
											// Fetch Room Name
											$room = Room::select('name')->where('id', $reader->exitid)
													->first();
											if (! isset($room)) {
													echo "Room not found\n";
													$room = (object) array(
															'name' => null
													);
											}
										}
									}
									
									
									
									// Fetch Door name & panel name
									$doors = Door::select('name', 'panel')->where('if', $isserver->iface)
											->where('position', $isserver->readeraddress)
											->where('iid', $controller->iid)
											->first();
									if (! isset($doors)) {
											echo "Door not found\n";
											$doors = (object) array(
													'name' => null,
													'panel' => null
											);
									}
									
									// Fetch event name
									$event = EventName::select('name')->where('task', $isserver->taskcode)
											->where('event', $isserver->eventcode)
											->first();
									if (! isset($event)) {
											echo "Event not found\n";
											$event = (object) array(
													'name' => null
											);
									}
									
									// Fetch credential name & access type
									if ($isserver->credential == null) {
											$credential = (object) array(
													'name' => 'None',
													'accesstype' => 'None'
											);
									} else {
											$credential = Credential::select('name', 'accesstype')->where('uniqueid', $isserver->credential)->first();
											if ($credential->accesstype == 1) {
													$credential->accesstype = '(Card AND Pin) OR Card Only';
											} else if ($credential->accesstype == 2) {
													$credential->accesstype = 'Card OR Pin';
											} else if ($credential->accesstype == 3) {
													$credential->accesstype = 'Pin Only';
											}
											if (! isset($credential)) {
													echo "Credential not found\n";
													$credential = (object) array(
															'name' => 'Name not found',
															'accesstype' => 'Access not found'
													);
											}
									}
									
									// passID
									/*if (env('APP_COMPANY') != "BCA") {
											$remedy = (object) array(
													'passid' => "None"
											);
									} else {
										$r_event = Credential::select(DB::raw('(CASE WHEN venrem.passid is null AND emprem.passid is null THEN "None" WHEN venrem.passid is null THEN emprem.passid WHEN emprem.passid is null THEN venrem.passid END) as passid'), 'r_locations.room as r_roomname')
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
										->leftJoin(DB::raw('(SELECT rt1.* FROM r_times rt1 JOIN (SELECT MAX(id)as maxid FROM r_times GROUP BY passid) rt2 ON rt1.id = rt2.maxid AND rt1.id = rt2.maxid) AS r_times'), function($join){
											$join->on('emprem.passid', '=', 'r_times.passid')
											->orOn('venrem.passid', '=', 'r_times.passid');
										})
										->where('credentials.uniqueid', $isserver->credential)
										->whereRaw("(((date_format(str_to_date(right('$isserver->msgtime',10), '%m/%d/%Y'), '%Y/%c/%e') = emprem.date or date_format(str_to_date(right('$isserver->msgtime',10), '%m/%d/%Y'), '%Y/%c/%e') = venrem.date) and (substr('$isserver->msgtime', 1, 8) not between right(r_times.start, 8) and right(r_times.end, 8))) or ((date_format(str_to_date(right('$isserver->msgtime',10), '%m/%d/%Y'), '%Y/%c/%e') = emprem.date or date_format(str_to_date(right('$isserver->msgtime',10), '%m/%d/%Y'), '%Y/%c/%e') = venrem.date) and (substr('$isserver->msgtime', 1, 8) between right(r_times.start, 8) and right(r_times.end, 8))))")
										->first();

										if (! isset($r_event)) {
											echo  "Pass ID Not found\n";
											$remedy = (object) array(
												'passid' => 'None'
											);
										}else{
											$room = $room->name;
											$r_room = $r_event->r_roomname;
											$r_rooms = explode(", ",$r_room);
											
											foreach ($r_rooms as $rr){
												if($room == $rr){
													$remedy=$r_event;
												}else if($room == "ATL" && ($rr == "ATL KACA" || $rr == "LIBRARY")){
													$remedy=$r_event;
												}else if($room == "COMMAND CENTER" || $room == "KORIDOR" || $room == "KORIDOR KIRI" || $room == "KORIDOR KANAN"){
													$remedy=$r_event;
												}
											}
										}
									}*/
									
									// Fetch IO Linker
									if ($isserver->ioevent == null) {} else {
											$event = IoLinkEventName::select('name')->where('extra', $isserver->ioevent)->first();
									}
									
									// BackGround Color & telegram
									if ($isserver->taskcode == null || $isserver->eventcode == null) {
											$color = "none";
									} else {
											$color = "none";
											switch ($isserver->taskcode) {
													case 1:
															switch ($isserver->eventcode) {
																	case 22:
																	case 26:
																	case 27:
																	case 28:
																			$color = "LightGray";
																			break;
															}
															break;
													case 2:
															switch ($isserver->eventcode) {
																	case 20:
																			$color = "LightGreen";
																			break;
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
															switch ($isserver->eventcode) {
																	case 20:
																			switch (substr($isserver->ioevent, 5)) {
																					case 0:
																							$color = "LightGreen";
																							break;
																					case 1:
																							$color = "LightCoral";
																							if (env('APP_COMPANY') == 'BCA') {
																									$accounts = TelegramAccount::where('subscription', 1)->pluck('token')->toArray();
																									$account = implode(',', $accounts);
																									$telegram_msg = "$account " . $doors->name . " " . $event->name;
																									//$this->send_telegram($telegram_msg);
																							}
																							break;
																			}
																			break;
															}
															break;
													default:
															$color = "none";
															break;
											}
									}
									
									// Prepare the message
									$response_text = $this->mask(json_encode(array(
											'name' => 'server',
											'controller' => $controller->name,
											'doors' => $doors->name,
											'panel' => $doors->panel,
											'message' => $isserver->msgtype,
											'msgtime' => $isserver->msgtime,
											'eventname' => $event->name,
											'credential' => $credential->name,
											'accesstype' => $credential->accesstype,
											//'passid' => $remedy->passid,
											'iface' => $isserver->iface,
											'readeraddress' => $isserver->readeraddress,
											'reader' => $reader->name,
											'ioevent' => $isserver->ioevent,
											'id' => $isserver->id,
											'color' => $color
									)));
									$this->send_message($response_text); // send data
							/*
							 * Else, it means websocket is trying to connect
							 * Then perform handshake
							 */
							} else {
									// perform websocket handshake
									$this->perform_handshaking($header, $socket_new, $host, $port);
							}
							$found_socket = array_search($socket, $changed);
							unset($changed[$found_socket]);
					}
					
					// loop through all connected sockets
					// This is used to detect the user sending message to server
					// Or detect the user disconnect
					foreach ($changed as $changed_socket) {
							// check for any incomming data (never happenned)
							$buf = @socket_read($changed_socket, 1024, PHP_NORMAL_READ);
							
							if ($buf === false) {
									// check disconnected client & remove client for $clients array
									$this->clients = array_diff($this->clients, array($changed_socket));
									echo "$ip with $changed_socket is disconnected\n";
							} else {
									// do nothing
							}
					}
			}
			// close the listening socket
			socket_close($socket);
	}

	private function send_telegram($msg)
	{
			// upload a file
			if (file_put_contents("ftp://ems_user:ems_user@10.20.192.26/home/ftptg/incoming/events.txt", $msg)) {} else {}
			
			/*
			 * $token = "421119232:AAF4XHlXvtfM2uZDouoBCZtYC5etbDo-4Bw";
			 * $user_id = 422347483;
			 * //$user_id = 420412343;
			 * $mesg = 'Force Door 1';
			 * $request_params = [
			 * 'chat_id' => $user_id,
			 * 'text' => $mesg
			 * ];
			 * $request_url = 'https://api.telegram.org/bot'.$token.'/sendMessage?'.http_build_query($request_params);
			 *
			 * file_get_contents($request_url);
			 */
	}

	private function send_message($msg)
	{
			// Write the json message to every socket (web interface)
			foreach ($this->clients as $changed_socket) {
					@socket_write($changed_socket, $msg, strlen($msg));
			}
			return true;
	}

	// Unmask incoming framed message
	private function unmask($text)
	{
			$length = ord($text[1]) & 127;
			if ($length == 126) {
					$masks = substr($text, 4, 4);
					$data = substr($text, 8);
			} elseif ($length == 127) {
					$masks = substr($text, 10, 4);
					$data = substr($text, 14);
			} else {
					$masks = substr($text, 2, 4);
					$data = substr($text, 6);
			}
			$text = "";
			for ($i = 0; $i < strlen($data); ++ $i) {
					$text .= $data[$i] ^ $masks[$i % 4];
			}
			return $text;
	}

	// Encode message for transfer to client.
	private function mask($text)
	{
			$b1 = 0x80 | (0x1 & 0x0f);
			$length = strlen($text);
			
			if ($length <= 125)
					$header = pack('CC', $b1, $length);
			elseif ($length > 125 && $length < 65536)
					$header = pack('CCn', $b1, 126, $length);
			elseif ($length >= 65536)
					$header = pack('CCNN', $b1, 127, $length);
			return $header . $text;
    }

    // handshake new client.
    private function perform_handshaking($receved_header, $client_conn, $host, $port)
    {
        $headers = array();
        $lines = preg_split("/\r\n/", $receved_header);
        foreach ($lines as $line) {
            $line = chop($line);
            if (preg_match('/\A(\S+): (.*)\z/', $line, $matches)) {
                $headers[$matches[1]] = $matches[2];
            }
        }
				if (!empty($headers)){
					$secKey = $headers['Sec-WebSocket-Key'];
					$secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
					// hand shaking header
					$upgrade = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" . "Upgrade: websocket\r\n" . "Connection: Upgrade\r\n" . "WebSocket-Origin: $host\r\n" . "WebSocket-Location: ws://$host:$port/demo/shout.php\r\n" . "Sec-WebSocket-Accept:$secAccept\r\n\r\n";
					socket_write($client_conn, $upgrade, strlen($upgrade));
				}
    }
}
