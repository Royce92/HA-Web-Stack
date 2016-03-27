<?php
error_reporting(0);

error_reporting(E_ALL);
    
Class Haproxy{
    function __construct(){}
    //Initially database needs to be configured in order to keep track on the serveres IP,starage, for scaling.
    //function __construct(){} // Create asbtract templates for installation *TODO


    

/*
        if($this->server == server1){
        
 //run code for server1 / mounting point 
        
        }    
        else{
    run code for server 2,3,4,5 ......
    }
    
    foreach with REQUEST parametors to to call consutrctor
    
    public function HAproxy extends install(){
    {
        
        
        
    }
    
        public function Nginx extends install(){ 

    
    } */
public function POST(){
    
//Generaet key and ssh-copy-id send to nodes 
//Mysql goes on top to store the servers info for scability. 

//Variables
$storage = $_REQUEST['storage'];
$localIP = $_REQUEST['local_IP']; //server1
$localIP2 = $_REQUEST['local_IP2']; //server2
//server 1

$conn = ssh2_connect($_REQUEST['IP']);
ssh2_auth_password($conn, "root", $_REQUEST['password']);
//Hosts
$stream = ssh2_exec($conn, "echo '$localIP server' >> /etc/hosts");
$stream = ssh2_exec($conn, "echo '$localIP2 server2' >> /etc/hosts;");
$stream = ssh2_exec($conn, "hostname server");
$stream = ssh2_exec($conn, "service iptables stop;");
$stream = ssh2_exec($conn, "sleep 2");

//make require directories
$stream = ssh2_exec($conn, "mkdir -p $storage");
$stream = ssh2_exec($conn, "mkdir /var/www;");

//repos
$stream = ssh2_exec($conn, "rpm -Uvh https://mirror.webtatic.com/yum/el6/latest.rpm;");
$stream = ssh2_exec($conn, "sleep 1");

$stream = ssh2_exec($conn, 'wget -P /etc/yum.repos.d http://download.gluster.org/pub/gluster/glusterfs/LATEST/EPEL.repo/glusterfs-epel.repo;');
$stream = ssh2_exec($conn, "sleep 1");

//Gluster
$stream = ssh2_exec($conn, "yum -y install glusterfs-fuse glusterfs-server;");
$stream = ssh2_exec($conn, "sleep 2");
$stream = ssh2_exec($conn, "service glusterd start;");
$stream = ssh2_exec($conn, "sleep 1");
$stream = ssh2_exec($conn, 'gluster peer probe server2;');
$stream = ssh2_exec($conn, "sleep 1");
$stream = ssh2_exec($conn, "gluster volume create file_store replica 2 transport tcp server:$storage server2:$storage force;");
$stream = ssh2_exec($conn, "sleep 2");
$stream = ssh2_exec($conn, "gluster volume start file_store");
$stream = ssh2_exec($conn, "mount.glusterfs server:/file_store /var/www");
$stream = ssh2_exec($conn, "sleep 1");

//php
$stream = ssh2_exec($conn, "yum install -y nginx php56w-fpm php56w-mysql");
$stream = ssh2_exec($conn, "sleep 1");
$stream = ssh2_exec($conn, "sed -i s/\;cgi\.fix_pathinfo\=1/cgi\.fix_pathinfo\=0/g /etc/php.ini");
//start services on boot
$stream = ssh2_exec($conn, "chkconfig glusterd on; chkconfig nginx on; chkconfig php-fpm on;");
//Nginx
$stream = ssh2_exec($conn, "wget https://raw.githubusercontent.com/jb376/Nginx-configuration/master/default -O /etc/nginx/conf.d/default.conf");
$stream = ssh2_exec($conn, "sleep 1");
$stream = ssh2_exec($conn, "chown -Rf nginx:nginx /var/www");
$stream = ssh2_exec($conn, "sleep 1");
$stream = ssh2_exec($conn, "service nginx restart");

//apache

stream_set_blocking($stream, true);
  $output = stream_get_contents($stream);
echo $output;
    fclose($conn);
       
 //server 2    



//iptables

$conn = ssh2_connect($_REQUEST['IP2']);
ssh2_auth_password($conn, "root", $_REQUEST['password']);
$stream = ssh2_exec($conn, "sleep 1");
$stream = ssh2_exec($conn, "service iptables stop;"); //for now
$stream = ssh2_exec($conn, "sleep 1");
$stream = ssh2_exec($conn, "rpm -Uvh https://mirror.webtatic.com/yum/el6/latest.rpm;");
//$stream = ssh2_exec($conn, "iptables -A INPUT -s $localIP -p tcp -j ACCEPT");
$stream = ssh2_exec($conn, "echo '$localIP server' >> /etc/hosts");
$stream = ssh2_exec($conn, "echo '$localIP2 server2' >> /etc/hosts");
$stream = ssh2_exec($conn, "hostname server2");
$stream = ssh2_exec($conn, "service iptables stop");
$stream = ssh2_exec($conn, 'wget -P /etc/yum.repos.d http://download.gluster.org/pub/gluster/glusterfs/LATEST/EPEL.repo/glusterfs-epel.repo');
$stream = ssh2_exec($conn, 'yum install -y glusterfs-server');
$stream = ssh2_exec($conn, "sleep 2");
$stream = ssh2_exec($conn, 'service glusterd start');
$stream = ssh2_exec($conn, "sleep 1");
$stream = ssh2_exec($conn, 'chkconfig glusterd on');
$stream = ssh2_exec($conn, 'gluster peer probe server');
$stream = ssh2_exec($conn, 'yum install -y nginx php56w-fpm php56w-mysql;');
$stream = ssh2_exec($conn, "sleep 1");
$stream = ssh2_exec($conn, "sed -i s/\;cgi\.fix_pathinfo\=1/cgi\.fix_pathinfo\=0/g /etc/php.ini;");
$stream = ssh2_exec($conn, "sleep 1");
$stream = ssh2_exec($conn, "wget https://raw.githubusercontent.com/jb376/Nginx-configuration/master/default -O /etc/nginx/conf.d/default.conf");
$stream = ssh2_exec($conn, "service nginx restart;");
$stream = ssh2_exec($conn, "sleep 1");

$stream = ssh2_exec($conn, "mkdir -p $storage");
$stream = ssh2_exec($conn, "mkdir /var/www;");

$stream = ssh2_exec($conn, "chown -Rf nginx:nginx /var/www");
$stream = ssh2_exec($conn, "service php-fpm start");

$stream = ssh2_exec($conn, 'mount.glusterfs server:/file_store /var/www');


  stream_set_blocking($stream, true);
  $output = stream_get_contents($stream);
echo $output;
    fclose($conn);
    
/* 
    
    


$stream = ssh2_shell($conn, 'xterm');
//$s = ssh2_exec($conn, "ls");

//fwrite($stream,"wget http://45.63.4.194/install.sh".PHP_EOL);
//fwrite( $s, "chmod +x install.sh".PHP_EOL);
//fwrite( $s, './install.sh'.PHP_EOL);
fwrite( $stream, "echo '$localIP glus1wq' >> /etc/hosts".PHP_EOL);
fwrite( $stream, 'touch yass'.PHP_EOL);
fwrite( $stream, 'yum install -y vim'.PHP_EOL);
fwrite( $stream, 'yum install -y glances'.PHP_EOL);

sleep($counter+1);
$counter = 0;
while ($line[] = fgets($stream)){
    echo $line["$counter"] . "<br>";

++$counter;
}


//print_r($line);

    fclose($conn);
*/
}

    
    public function GET(){
    
     /*   
$myfile = fopen("install.sh", "w") or die("Unable to open file!");
$txt = "yum install -y vim \n";
fwrite($myfile, $txt);
fclose($myfile);

$output = shell_exec("chmod +x install.sh");
$output = shell_exec("./install.sh");


echo $output; */
     
include 'views/configuration.html';


    }
}

?>