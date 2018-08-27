<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\EventHappened;

class StartControllerServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start A S P H A Controller Server';

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
    public function handle()
    {
      /***** Controller Server *****/
      $host = 'localhost'; //host
      $port = '4070'; //port
      $null = NULL; //null var

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

      $clients = array($socket);

      echo("Controller server is started\n");
      echo("=========================\n");
      while (true) {
      	$changed = $clients;
      	socket_select($changed, $null, $null, 0, $null);

      	//Changed will be the server #resource IF new connection is being made
      	if (in_array($socket, $changed)) {
          //New connection incoming
      		$socket_new = socket_accept($socket); //accept new socket
      		socket_getpeername($socket_new, $ip); //get ip address of connected socket
          if ($ip != env('APP_IP')){
      		  echo "Client ".$ip." is connected\n";
      		  $clients[$ip] = $socket_new; //add socket to client array
					} else {
					  array_push($clients, $socket_new); //add 'send_message' socket to clients array
					}
      		//Clear the changed array (the server) so it won't go to the foreach below
      		$found_socket = array_search($socket, $changed);
      		unset($changed[$found_socket]);
      	}

      	//Changed will be the client #resource IF message from client is incoming
      	foreach ($changed as $changed_socket) {
          // Reading incoming message first 10 chars
      		$buf = @socket_read($changed_socket, 10, PHP_NORMAL_READ); //to check $buf is filled
					socket_getpeername($changed_socket, $ip);

      		if ($buf === false) { //if $buf empty
      			$clients = array_diff($clients, array($changed_socket)); // Remove client from $clients array
            if ($ip != env('APP_IP'))
      			   echo "$ip is disconnected\n";
      		} else {
      			if($ip == env('APP_IP')) { //server to controller
							$buf .= @socket_read($changed_socket, 2, PHP_NORMAL_READ);
							$length = substr ($buf, 5, 6);
							$buf .= @socket_read($changed_socket, $length-12, PHP_NORMAL_READ);
      				/*********************************************
      				*   Message from server 0000;000037;%0060;0010;%192.168.100.1
              *   unmask[0] = 0000;XXXXXX;
              *   unmask[1] = 0060;0010; (the message)
              *   unmask[2] = 192.168.xxx.xxx (the controller) or 0 (all controller)
              *********************************************/
      				$unmask = explode("%", $buf);
      				$message = $unmask[1];
              $controllerip = $unmask[2];
              echo "$message to $controllerip\n";
      				
      				if(isset($clients[$controllerip])) {
           			$this->send_message($message, array($clients[$controllerip]));
              } else {
                 echo "Controller isn't connected\n";
              }
      			} else { //controller to server
							$length = substr ($buf, 5, 4);
							$buf .= @socket_read($changed_socket, $length-10, PHP_NORMAL_READ);
      				/*********************************************
      				*    Message from controller
              *********************************************/
              echo "$ip : $buf\n";
							if(!in_array(substr($buf,0,4), ['1027', '1012', '1080'])) //ignore these event
								event(new EventHappened($buf."%".$ip));
              /* Lupa buat apa
              if (isset($clients[env('APP_IP')])) {
  				      $this->send_message($buf, array($clients[getHostByName(getHostName())]));
              } else {
                echo "Can't send message to server\n";
              }
              */
      			}
          }
      	}
      }
      socket_close($socket);
    }


    /**
     * Send message with (message, client's socket).
     *
     * @return true
     */
    private function send_message($msg, $clients)
    {
			// Write the json message to every socket (web interface)
      foreach($clients as $changed_socket)
      {
        socket_write($changed_socket,$msg,strlen($msg));
      }
      return true;
    }
}
