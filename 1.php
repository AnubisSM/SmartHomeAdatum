<?php
error_reporting(E_ALL); 
ini_set("display_errors", 1); 
 include 's-head.php';
 include "ucs2cp1251.class.php";
 echo "-> ".$date_today." ".$time_today." Starting MOD-RS485 ............................................\n";
 


//�������� ����������� ���������� � ��������� ��������
$result= mysqli_query($con, "SELECT * FROM data WHERE id='5'"); $row=mysqli_fetch_array($result); $namedev = $row['state'];
*
list ($nameport, $numport) = dev_detect($namedev); 
echo "-> ".$date_today." ".$time_today." Device name: ".$nameport." Port: ".$numport."\n"; 
if($nameport) {
//�������� ����������� ���������� � ��������� ��������

//������� ��� �����
$resulttty= mysqli_query($con, "SELECT * FROM data WHERE id='19'"); $rowtty=mysqli_fetch_array($resulttty); $nametty = $rowtty['state'];
//������� ��� �����

//������� ��� �����
$ipservelocal= mysqli_query($con, "SELECT * FROM data WHERE id='21'"); $rowipservelocal=mysqli_fetch_array($ipservelocal); $nameipservelocal = $rowipservelocal['state'];
//������� ��� �����



   
	
	
	

$fp=fopen("/dev/".$nametty.$numport."","w+");
if(!$fp) {echo "---------------------++++++++++++++********************";}
 fclose($fp);

// ��������� ����� ��� �� ��� �����
$logist=shell_exec("stty  -F /dev/".$nametty.$numport." -echo");
// ��������� ����� ��� �� ��� �����

	}

 ?>