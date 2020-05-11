<?php

require_once dirname(__DIR__, 2) . '/libs/vendor/autoload.php';

class Db {
     private $server_ip;
     private $dbuser;
     private $dbpass;
     private $dbname;
     private $charset;

     public function connect() {
          // Get values from cnf file
          $cnf = json_decode(file_get_contents(dirname(__FILE__, 2) . '/json/cnf.json'),true);

          // Set values
          $this->server_ip = $cnf['DB']['server_ip'];
          $this->dbuser = $cnf['DB']['username'];
          $this->dbpass = $cnf['DB']['password'];
          $this->dbname = $cnf['DB']['dbname'];
          $this->charset = $cnf['DB']['charset'];

          try {
               $dsn = "mysql:host=" . $this->server_ip . ";dbname=" . $this->dbname . ";charset=" . $this->charset;
               $pdo = new PDO($dsn, $this->dbuser, $this->dbpass);
               $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
               return $pdo;
          } catch (PDOException $e) {
               return $e->getMessage();
          }
     }

     public function getUsers($pdo) {
          try {
               $users = $pdo->query("SELECT * FROM Users")->fetchAll(PDO::FETCH_ASSOC);
          } catch (PDOException $e) {
               return false;
          }
          return $users;
     }

     public function getNetDevices($pdo) {
          try {
               $netdev = $pdo->query("SELECT * FROM Net_devices")->fetchAll(PDO::FETCH_ASSOC);
          } catch (PDOException $e) {
               return $e;
          }
          return $netdev;
     }

     public function getPorts($mac,$pdo) {
          try {
               $ports_db = $pdo->query("SELECT * FROM Ports WHERE MACND='$mac'")->fetchAll(PDO::FETCH_ASSOC);
          } catch (PDOException $e) {
               return $e;
          }
          return $ports_db;
     }

     public function newPorts($mac,$ports,$pdo) {
          for ($i=0; $i < count($ports); $i++) {
               try {
                    $new_ports = $pdo->query("INSERT INTO Ports VALUES (null,'$ports[$i]',null,null,'$mac',null)");
               } catch (PDOException $e) {
                    return $e;
               }
          }
          return true;
     }

     public function getLocations($pdo) {
          try {
               $locations = $pdo->query("SELECT LOCATION FROM Ports")->fetchAll(PDO::FETCH_ASSOC);
          } catch (PDOException $e) {
               return $e;
          }
          return $locations;
     }

     public function updatePorts($pdo,$newloc,$portn,$mac) {
          try {
               $upd_location = $pdo->query("UPDATE Ports SET LOCATION='$newloc' WHERE MACND='$mac' AND NAME='$portn'");
          } catch (PDOException $e) {
               return $e;
          }
          return true;
     }

     public function getClients($pdo) {
          try {
               $clients_db = $pdo->query("SELECT MACEQ FROM Medical_eq")->fetchAll(PDO::FETCH_ASSOC);
          } catch (PDOException $e) {
               return $e;
          }
          return $clients_db;
     }

     public function newClient($pdo,$sql) {
          try {
               $new_client = $pdo->query($sql);
          } catch (PDOException $e) {
               return $e;
          }
          return true;
     }

     public function getBlankMac($pdo) {
          // Generate random mac
          $hex_chars = '0123456789ABCDEF';
          $random_mac = 'FF:';
          $random_mac .= substr(str_shuffle($hex_chars),0,2) . ':' . substr(str_shuffle($hex_chars),0,2) . ':';
          $random_mac .= substr(str_shuffle($hex_chars),0,2) . ':' . substr(str_shuffle($hex_chars),0,2);
          $random_mac .= ':FF';

          // Check that mac in the db
          try {
               $exists_mac = $pdo->query("SELECT MACEQ FROM Medical_eq WHERE MACEQ='$random_mac'")->fetchAll(PDO::FETCH_ASSOC);
          } catch (PDOException $e) {
               return $e;
          }
          while (count($exists_mac)>0) {
               $random_mac = 'FF:';
               $random_mac .= substr(str_shuffle($hex_chars),0,2) . ':' . substr(str_shuffle($hex_chars),0,2) . ':';
               $random_mac .= substr(str_shuffle($hex_chars),0,2) . ':' . substr(str_shuffle($hex_chars),0,2);
               $random_mac .= ':FF';
               $exists_mac = $pdo->query("SELECT MACEQ FROM Medical_eq WHERE MACEQ='$random_mac'")->fetchAll(PDO::FETCH_ASSOC);
          }
          return $random_mac;
     }

     public function getBlankIp($pdo) {
          // Generate random ip
          $random_ip = '111.';
          $random_ip .=  rand(0,255) . '.' . rand(0,255);
          $random_ip .= '.111';

          // Check that IP in the DB
          try {
               $exists_ip = $pdo->query("SELECT IP_ADDR FROM Ports WHERE IP_ADDR='$random_ip'")->fetchAll(PDO::FETCH_ASSOC);
          } catch (PDOException $e) {
               return $e;
          }
          while (count($exists_ip)>0) {
               // Generate random ip
               $random_ip = '111.';
               $random_ip .=  rand(0,255) . '.' . rand(0,255);
               $random_ip .= '.111';

               // Check that IP in the DB
               try {
                    $exists_ip = $pdo->query("SELECT IP_ADDR FROM Ports WHERE IP_ADDR='$random_ip'")->fetchAll(PDO::FETCH_ASSOC);
               } catch (PDOException $e) {
                    return $e;
               }
          }
          return $random_ip;
     }

     public function ClientIsNd($pdo,$macnd,$pname) {
          $pname_prepared = substr(trim($pname),0,2) . '%' . substr(trim($pname),2,5);
          $is_nd_sql = "SELECT ID FROM Ports INNER JOIN Medical_eq ON Ports.MACEQ = Medical_eq.MACEQ ";
          $is_nd_sql .= "WHERE Ports.NAME LIKE '$pname_prepared' AND Medical_eq.TYPE = 'Network Device' AND Ports.MACND='$macnd'";

          try {
               $is_nd = $pdo->query($is_nd_sql)->fetchAll(PDO::FETCH_ASSOC);
          } catch (PDOException $e) {
               return $e;
          }
          if (count($is_nd) == 0) {
               return false;
          } else {
               return true;
          }
     }

     public function UpdNCliPort($pdo,$macnd,$pname,$maceq,$ipnd) {
          $pname_prepared = substr(trim($pname),0,2) . '%' . substr(trim($pname),2,5);
          $datetime = date('Y:m:d H:i:s');
          // Create new network device
          try {
               $new_client_net = $pdo->query("INSERT INTO Medical_eq VALUES ('$maceq','Network Device',null,null,'$datetime')");
          } catch (PDOException $e) {
               return $e;
          }

          try {
               $update_port = $pdo->query("UPDATE Ports SET IP_ADDR='$ipnd',MACEQ='$maceq' WHERE MACND='$macnd' AND NAME LIKE '$pname_prepared'");
          } catch (PDOException $e) {
               return $e;
          }
          return true;
     }

     public function UpdCliPort($pdo,$maceq,$macnd,$ip_addr,$pname) {
          $pname_prepared = substr(trim($pname),0,2) . '%' . substr(trim($pname),2,5);

          try {
               $delete_old_port_cli = $pdo->query("UPDATE Ports SET IP_ADDR=null,MACEQ=null WHERE MACEQ='$maceq'");
          } catch (PDOException $e) {
               return $e;
          }


          try {
               $set_new_port_cli = $pdo->query("UPDATE Ports SET IP_ADDR='$ip_addr',MACEQ='$maceq' WHERE NAME LIKE '$pname_prepared' AND MACND='$macnd'");
          } catch (PDOException $e) {
               return $e;
          }

          return true;
     }

     public function ClearPortCli($pdo,$maceq) {
          try {
               $clear_port = $pdo->query("UPDATE Ports SET IP_ADDR=null,MACEQ=null WHERE MACEQ='$maceq'");
          } catch (PDOException $e) {
               return $e;
          }
          return true;
     }

     public function checkPortMac($pdo,$pname,$maceq,$macnd) {
          $pname_prepared = substr(trim($pname),0,2) . '%' . substr(trim($pname),2,5);
          try {
               $check_port_mac = $pdo->query("SELECT ID FROM Ports WHERE NAME LIKE '$pname_prepared' AND MACND='$macnd' AND MACEQ='$maceq'")->fetchAll(PDO::FETCH_ASSOC);
          } catch (PDOException $e) {
               return $e;
          }
          if (count($check_port_mac) == 0) {
               return false;
          } else {
               return true;
          }
     }

     public function updLastSeen($pdo, $maceq = false) {
          $datetime = date('Y-m-d H:i:s');
          if (!$maceq) {
               $sql = "UPDATE Medical_eq SET LAST_SEEN='$datetime' WHERE TYPE='Network Device'";
          } else {
               $sql = "UPDATE Medical_eq SET LAST_SEEN='$datetime' WHERE MACEQ='$maceq'";
          }

          try {
               $upd_last_seen = $pdo->query($sql);
          } catch (PDOException $e) {
               return $e;
          }
     }
}

class Telnet {

	private $host;
	private $port;
	private $timeout;
	private $stream_timeout_sec;
	private $stream_timeout_usec;

	private $socket  = NULL;
	private $buffer = NULL;
	private $prompt;
	private $errno;
	private $errstr;
	private $strip_prompt = TRUE;

	private $NULL;
	private $DC1;
	private $WILL;
	private $WONT;
	private $DO;
	private $DONT;
	private $IAC;

	private $global_buffer = '';

	const TELNET_ERROR = FALSE;
	const TELNET_OK = TRUE;

	/**
	 * Constructor. Initialises host, port and timeout parameters
	 * defaults to localhost port 23 (standard telnet port)
	 *
	 * @param string $host Host name or IP addres
	 * @param int $port TCP port number
	 * @param int $timeout Connection timeout in seconds
	 * @param string $prompt Telnet prompt string
	 * @param float $streamTimeout Stream timeout in decimal seconds
	 * @return void
	 */
	public function __construct($host = '127.0.0.1', $port = '23', $timeout = 10, $prompt = '', $stream_timeout = 1) {
		$this->host = $host;
		$this->port = $port;
		$this->timeout = $timeout;
		$this->setPrompt($prompt);
		$this->setStreamTimeout($stream_timeout);

		// set some telnet special characters
		$this->NULL = chr(0);
		$this->DC1 = chr(17);
		$this->WILL = chr(251);
		$this->WONT = chr(252);
		$this->DO = chr(253);
		$this->DONT = chr(254);
		$this->IAC = chr(255);

		$this->connect();
	}

	/**
	 * Destructor. Cleans up socket connection and command buffer
	 *
	 * @return void
	 */
	public function __destruct() {
		// clean up resources
		$this->disconnect();
		$this->buffer = NULL;
		$this->global_buffer = NULL;
	}

	/**
	 * Attempts connection to remote host. Returns TRUE if successful.
	 *
	 * @return boolean
	 */
	public function connect() {
		// check if we need to convert host to IP
		if (!preg_match('/([0-9]{1,3}\\.){3,3}[0-9]{1,3}/', $this->host)) {
			$ip = gethostbyname($this->host);

			if ($this->host == $ip) {
				throw new Exception("Cannot resolve $this->host");
			} else {
				$this->host = $ip;
			}
		}

		// attempt connection - suppress warnings
		$this->socket = @fsockopen($this->host, $this->port, $this->errno, $this->errstr, $this->timeout);

		if (!$this->socket) {
			throw new Exception("Cannot connect to $this->host on port $this->port");
		}

		if (!empty($this->prompt)) {
			$this->waitPrompt();
		}

		return self::TELNET_OK;
	}

	/**
	 * Closes IP socket
	 *
	 * @return boolean
	 */
	public function disconnect() {
		if ($this->socket) {
			if (! fclose($this->socket)) {
				throw new Exception("Error while closing telnet socket");
			}
			$this->socket = NULL;
		}
		return self::TELNET_OK;
	}

	/**
	 * Executes command and returns a string with result.
	 * This method is a wrapper for lower level private methods
	 *
	 * @param string $command Command to execute
	 * @param boolean $add_newline Default TRUE, adds newline to the command
	 * @return string Command result
	 */
	public function exec($command, $add_newline = TRUE) {
		$this->write($command, $add_newline);
		$this->waitPrompt();
		return $this->getBuffer();
	}

	/**
	 * Sets the string of characters to respond to.
	 * This should be set to the last character of the command line prompt
	 *
	 * @param string $str String to respond to
	 * @return boolean
	 */
	public function setPrompt($str = '$') {
		return $this->setRegexPrompt(preg_quote($str, '/'));
	}

	/**
	 * Sets a regex string to respond to.
	 * This should be set to the last line of the command line prompt.
	 *
	 * @param string $str Regex string to respond to
	 * @return boolean
	 */
	public function setRegexPrompt($str = '\$') {
		$this->prompt = $str;
		return self::TELNET_OK;
	}

	/**
	 * Sets the stream timeout.
	 *
	 * @param float $timeout
	 * @return void
	 */
	public function setStreamTimeout($timeout) {
		$this->stream_timeout_usec = (int)(fmod($timeout, 1) * 1000000);
		$this->stream_timeout_sec = (int)$timeout;
	}

	/**
	 * Set if the buffer should be stripped from the buffer after reading.
	 *
	 * @param $strip boolean if the prompt should be stripped.
	 * @return void
	 */
	public function stripPromptFromBuffer($strip) {
		$this->strip_prompt = $strip;
	} // function stripPromptFromBuffer

	/**
	 * Gets character from the socket
	 *
	 * @return void
	 */
	protected function getc() {
		stream_set_timeout($this->socket, $this->stream_timeout_sec, $this->stream_timeout_usec);
		$c = fgetc($this->socket);
		$this->global_buffer .= $c;
		return $c;
	}

	/**
	 * Clears internal command buffer
	 *
	 * @return void
	 */
	public function clearBuffer() {
		$this->buffer = '';
	}

	/**
	 * Reads characters from the socket and adds them to command buffer.
	 * Handles telnet control characters. Stops when prompt is ecountered.
	 *
	 * @param string $prompt
	 * @return boolean
	 */
	protected function readTo($prompt) {
		if (!$this->socket) {
			throw new Exception("Telnet connection closed");
		}

		// clear the buffer
		$this->clearBuffer();

		$until_t = time() + $this->timeout;
		do {
			// time's up (loop can be exited at end or through continue!)
			if (time() > $until_t) {
				throw new Exception("Couldn't find the requested : '$prompt' within {$this->timeout} seconds");
			}

			$c = $this->getc();

			if ($c === FALSE) {
				if (empty($prompt)) {
					return self::TELNET_OK;
				}
				throw new Exception("Couldn't find the requested : '" . $prompt . "', it was not in the data returned from server: " . $this->buffer);
			}

			// Interpreted As Command
			if ($c == $this->IAC) {
				if ($this->negotiateTelnetOptions()) {
					continue;
				}
			}

			// append current char to global buffer
			$this->buffer .= $c;

			// we've encountered the prompt. Break out of the loop
			if (!empty($prompt) && preg_match("/{$prompt}$/", $this->buffer)) {
				return self::TELNET_OK;
			}

		} while ($c != $this->NULL || $c != $this->DC1);
	}

	/**
	 * Write command to a socket
	 *
	 * @param string $buffer Stuff to write to socket
	 * @param boolean $add_newline Default TRUE, adds newline to the command
	 * @return boolean
	 */
	protected function write($buffer, $add_newline = TRUE) {
		if (!$this->socket) {
			throw new Exception("Telnet connection closed");
		}

		// clear buffer from last command
		$this->clearBuffer();

		if ($add_newline == TRUE) {
			$buffer .= "\n";
		}

		$this->global_buffer .= $buffer;
		if (!fwrite($this->socket, $buffer) < 0) {
			throw new Exception("Error writing to socket");
		}

		return self::TELNET_OK;
	}

	/**
	 * Returns the content of the command buffer
	 *
	 * @return string Content of the command buffer
	 */
	protected function getBuffer() {
		// Remove all carriage returns from line breaks
		$buf =  preg_replace('/\r\n|\r/', "\n", $this->buffer);
		// Cut last line from buffer (almost always prompt)
		if ($this->strip_prompt) {
			$buf = explode("\n", $buf);
			unset($buf[count($buf) - 1]);
			$buf = implode("\n", $buf);
		}
		return trim($buf);
	}

	/**
	 * Returns the content of the global command buffer
	 *
	 * @return string Content of the global command buffer
	 */
	public function getGlobalBuffer() {
		return $this->global_buffer;
	}

	/**
	 * Telnet control character magic
	 *
	 * @param string $command Character to check
	 * @return boolean
	 */
	protected function negotiateTelnetOptions() {
		$c = $this->getc();

		if ($c != $this->IAC) {
			if (($c == $this->DO) || ($c == $this->DONT)) {
				$opt = $this->getc();
				fwrite($this->socket, $this->IAC . $this->WONT . $opt);
			} else if (($c == $this->WILL) || ($c == $this->WONT)) {
				$opt = $this->getc();
				fwrite($this->socket, $this->IAC . $this->DONT . $opt);
			} else {
				throw new Exception('Error: unknown control character ' . ord($c));
			}
		} else {
			throw new Exception('Error: Something Wicked Happened');
		}

		return self::TELNET_OK;
	}

	/**
	 * Reads socket until prompt is encountered
	 */
	protected function waitPrompt() {
		return $this->readTo($this->prompt);
	}
}

class CiscoNetDeviceTelnet {
     private $conn;
     private $pass;
     private $mac;
     private $type;
     private $ip_addr;
     private $ssh;
     private $telnet;
     private $nports;
     private $brand;
     private $model;

     public function __construct($conn, $pass) {
          $this->conn = $conn;
          $this->pass = $pass;
          $this->brand = 'Cisco';
     }

     public function login() {
          $this->conn->exec("\n");
          $this->conn->exec($this->pass);
          if ($this->conn->exec("\n") == '') {
               return false;
          } else {
               return true;
          }
     }

     public function getMac($conn) {
          $conn->exec('terminal length 0');
          $show_ver = $conn->exec('show version');
          $arr = explode("\n", $show_ver);
          $mac = substr($arr[21], 34, 51);
          return $mac;
     }

     public function addData($mac,$type,$ip_addr,$ssh,$telnet,$nports,$model) {
          $this->mac = $mac;
          $this->type = $type;
          $this->ip_addr = $ip_addr;
          $this->ssh = $ssh;
          $this->telnet = $telnet;
          $this->nports = $nports;
          $this->model = $model;
     }

     public function intoDB($pdo) {
          try {
               $new_cnd = $pdo->query("INSERT INTO Net_devices VALUES ('$this->mac','$this->type','$this->ip_addr','$this->ssh','$this->telnet','$this->nports','$this->brand','$this->model','$this->pass')");
          } catch (PDOException $e) {
               return $e;
          }
          return true;
     }

     public function getPorts($conn) {
          $conn->exec('terminal length 0');
          $ports_raw = $conn->exec('show interfaces');
          $ports_lines = explode("\n",$ports_raw);
          $ports_lines_clean = array();
          $ports = array();
          $ni = 0;
          $pi = 0;
          for ($i=22; $i < count($ports_lines); $i++) {
               $ports_lines_clean[$ni] = $ports_lines[$i];
               if (substr($ports_lines_clean[$ni],0,1) != ' ') {
                    $ports_lines_clean_exploded = explode(' ',$ports_lines_clean[$ni]);
                    $ports[$pi] = $ports_lines_clean_exploded[0];
                    $pi++;
               }
               $ni++;
          }
          return $ports;
     }

     public function getConnDevs($conn) {
          $conn->exec('terminal length 0');
          $conn_devices = $conn->exec('sh mac address-table DYNAMIC');
          return $conn_devices;
     }

     public function getIp($conn,$mac) {
          $conn->exec('terminal length 0');
          $mac = $conn->exec('sh ip arp ' . $mac);
          return $mac;
     }
}

class Bot {
     private $botToken;
     private $website;
     private $message_data;
     private $adminId;
     private $groupId;


     public function __construct() {
          // Open file
          $cnf = fopen(dirname(__FILE__, 2) . '/json/cnf.json','r');
          if (!$cnf) {
               loginError('Error interno','Error al abrir el fichero de configuración desde login.php',4);
               die();
          }
          fclose($cnf);

          // Get token and generate website

          $this->botToken = json_decode(file_get_contents(dirname(__FILE__, 2) . '/json/cnf.json'),true)['Telegram']['BotToken'];
          $this->website = 'https://api.telegram.org/bot' . $this->botToken;

          // Get admin data
          $this->adminId = json_decode(file_get_contents(dirname(__FILE__, 2) . '/json/cnf.json'),true)['Telegram']['AdminId'];
          $this->groupId = json_decode(file_get_contents(dirname(__FILE__, 2) . '/json/cnf.json'),true)['Telegram']['GroupId'];
     }

     public function newMsg() {
          // Get json and decode
          $update = file_get_contents('php://input');
          $update = json_decode($update, TRUE);
          $message = $update["message"]["text"];
          $command = explode(' ',trim($message))[0];

          // Get data from message
          $this->message_data = $update['message'];

          // Return command
          return $command;
     }

     public function get($dat) {
          switch ($dat) {
               case 'chatId':
                    return $this->message_data['chat']['id'];
                    break;
               case 'all':
                    return $this->message_data;
                    break;
          }
     }

     public function sendMessage($response,$chatId = 'F') {
          if ($chatId == 'F') {
               $chatId = $this->message_data['chat']['id'];
          }
          $url = $this->website . '/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response);
          file_get_contents($url);
     }

     private function isAdmin() {
          if ($this->message_data['from']['id'] == $this->adminId) {
               return true;
          } else {
               return false;
          }
     }

     private function isGroup() {
          if ($this->message_data['chat']['id'] == $this->groupId) {
               return true;
          } else {
               return false;
          }
     }
}


?>
