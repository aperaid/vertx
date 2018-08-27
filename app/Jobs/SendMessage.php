<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMessage implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $Message;
    public $Address;
    public $ServicePort = 4070;
    private $Socket;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($Message)
    {
      $this->Address = getHostByName(getHostName());
      $length = str_pad(strlen($Message) + 13, 6, '0', STR_PAD_LEFT);
      $this->Message = '0000;'.$length.';%'.$Message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $this->CreateConnection();
      $this->SendMessage();
      $this->CloseConnection();
    }

    private function CreateConnection(){
      /* Create a TCP/IP socket. */
      $this->Socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
      if ($this->Socket === false) {
          //echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
      } else {
          //echo "Create Socket OK.\n";
      }

      //echo "Attempting to connect to '.$this->Address.' on port '.$this->ServicePort.'...\n";
			echo "$this->Socket, $this->Address, $this->ServicePort, $this->Message";
      $result = socket_connect($this->Socket, $this->Address, $this->ServicePort);
      if ($result === false) {
          //echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($this->Socket)) . "\n";
      } else {
          //echo "Socket Connect OK.\n";
      }
    }

    private function CloseConnection(){
      socket_close($this->Socket);
    }

    private function SendMessage(){
      //echo "Sending message $this->Message...";
      socket_write($this->Socket, $this->Message, strlen($this->Message));
    }

}
