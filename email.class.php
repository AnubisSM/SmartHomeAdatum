<?php 

function email_send($address,$port,$login,$pwd ,$from ,$to, $subject, $message) { 
 ob_implicit_flush();
 
//include_once "mysql";
 $db_host   = "localhost";
$db_login  = "root";
$db_passwd = "111";
$db_name   = "adatum";
 // ����������� � ���� mysql
$con=mysqli_connect($db_host,$db_login,$db_passwd,$db_name);
if (mysqli_connect_error()) {
    die('������ ����������� (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
}
$con->set_charset("utf8"); // �����
if (mysqli_connect_errno()) {  echo "-> Failed to connect to MySQL: " . mysqli_connect_error();}
// ����������� � ���� mysql

  //  $address = 'smtp.mail.ru'; // ����� smtp-�������
 //   $port    = 25;          // ���� (����������� smtp - 25)
    
 //   $login   = 'sergomanov';    // ����� � �����
 //   $pwd     = '7Admin312';    // ������ � �����
    
  //  $from    = 'sergomanov@mail.ru';  // ����� �����������
  //   $to      = 'sergomanov@mail.ru';  // ����� ����������
    
  //  $subject = 'Message subject ����';       // ���� ���������
  //  $message = 'Message text ������ ���';          // ����� ���������

    try {
        
        // ������� �����
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket < 0) {
            throw new Exception('socket_create() failed: '.socket_strerror(socket_last_error())."\n");
        }

        // ��������� ����� � �������
        echo ' -> Connect to \''.$address.':'.$port.'\' ... ';
        $result = socket_connect($socket, $address, $port);
        if ($result === false) {
            throw new Exception('socket_connect() failed: '.socket_strerror(socket_last_error())."\n");
        } else {
            echo "OK\n";
        }
        
        // ������ ���������� � �������
        read_smtp_answer($socket);
        
        // ������������ ������
        write_smtp_response($socket, 'EHLO '.$login);
        read_smtp_answer($socket); // ����� �������
        
        echo ' -> Authentication ... ';
            
        // ������ ������ �����������
        write_smtp_response($socket, 'AUTH LOGIN');
        read_smtp_answer($socket); // ����� �������
        
        // ��������� �����
        write_smtp_response($socket, base64_encode($login));
        read_smtp_answer($socket); // ����� �������
        
        // ��������� ������
        write_smtp_response($socket, base64_encode($pwd));
        read_smtp_answer($socket); // ����� �������
        
        echo "OK\n";
        echo " -> Check sender address ... ".$from."  ... ";
        
        // ������ ����� �����������
        write_smtp_response($socket, 'MAIL FROM:<'.$from.'>');
        read_smtp_answer($socket); // ����� �������
        
        echo "OK\n";
        echo " -> Check recipient address ... ". $to." ... ";
        
        // ������ ����� ����������
        write_smtp_response($socket, 'RCPT TO:<'.$to.'>');
        read_smtp_answer($socket); // ����� �������
        
        echo "OK\n";
        echo " -> Send message text ... ".$subject." ... ";
        
        // ������� ������ � ������ ������
        write_smtp_response($socket, 'DATA');
        read_smtp_answer($socket); // ����� �������
        
        // ���������� ������
        $message = "To: $to\r\n".$message; // ��������� ��������� ��������� "����� ����������"
        $message = "Subject: $subject\r\n".$message; // ��������� "���� ���������"
        write_smtp_response($socket, $message."\r\n.");
        read_smtp_answer($socket); // ����� �������
        
        echo "OK\n";
        echo ' -> Close connection ... ';
        
        // ������������� �� �������
        write_smtp_response($socket, 'QUIT');
        read_smtp_answer($socket); // ����� �������
        
        echo "OK\n";
		
		//�������� ������������ � ������������� �������
		    $res8 = mysqli_query($con,"SELECT * FROM `run` WHERE run ='1' AND mode='EML'");
			if($res8) {   while($row8 = mysqli_fetch_assoc($res8)) 
						 {
						 $del_command = $row8['id'];
						 mysqli_query($con,"DELETE FROM run WHERE id='$del_command'");
						 echo " -> Removal from the database of used commands.\n\r";
			 }}
			//�������� ������������ � ������������� �������
			
        
    } catch (Exception $e) {
        echo "\nError: ".$e->getMessage();
    }
    
    if (isset($socket)) {
        socket_close($socket);
    }
	}
    
    // ������� ��� ������ ������ �������. ����������� ���������� � ������ ������
    function read_smtp_answer($socket) {
        $read = socket_read($socket, 1024);
        
        if ($read{0} != '2' && $read{0} != '3') {
            if (!empty($read)) {
                throw new Exception('SMTP failed: '.$read."\n");
            } else {
                throw new Exception('Unknown error'."\n");
            }
        }
    }
    
    // ������� ��� �������� ������� �������
    function write_smtp_response($socket, $msg) {
        $msg = $msg."\r\n";
        socket_write($socket, $msg, strlen($msg));
    }
?>