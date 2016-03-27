<?php      
class install{
    private $Hostname;
    private $serverIP;
    private $serverPassword;
    private $serverLocalIP;
    private $serverStorage;
    
    public function __construct($hostname,$ip, $password, $localIP, $storage){
        $this->Hostname = $hostname;
        $this->serverIP = $ip;
        $this->serverPassword = $password;
        $this->serverLocalIP = $localIP;
        $this->serverStorage = $storage;
    }
        public function GlusterFs() //running all the codes together for testing purposes
        {
                $conn = ssh2_connect($this->serverIP);
                ssh2_auth_password($conn, "root",$this->serverPassword);

                       $number_of_installation = 1 ;
                while(2 >= $number_of_installation){
                $host = $this->serverLocalIP["local".$number_of_installation] ." " .  $this->serverLocalIP["hostname".$number_of_installation];
                $stream = ssh2_exec($conn, "echo '$host' >> /etc/hosts");
                echo $host;
                ++$number_of_installation;
                    }
                  
                    $stream = ssh2_exec($conn, "hostname $this->Hostname");
                    $stream = ssh2_exec($conn, "service iptables stop;");
                    $stream = ssh2_exec($conn, "sleep 2");
                    
                    //make require directories
                    $stream = ssh2_exec($conn, "mkdir -p $this->serverStorage");
                    $stream = ssh2_exec($conn, "mkdir /var/www;");
                    
                    //repos
                    $stream = ssh2_exec($conn, "rpm -Uvh https://mirror.webtatic.com/yum/el6/latest.rpm;");
                    $stream = ssh2_exec($conn, "sleep 1");
                    
                    $stream = ssh2_exec($conn, 'wget -P /etc/yum.repos.d http://download.gluster.org/pub/gluster/glusterfs/LATEST/EPEL.repo/glusterfs-epel.repo;');
                    $stream = ssh2_exec($conn, "sleep 5");
                    
                    //Gluster
                    $stream = ssh2_exec($conn, "yum -y install glusterfs-fuse glusterfs-server;");
                    $stream = ssh2_exec($conn, "sleep 3");
                    $stream = ssh2_exec($conn, "service glusterd start;");
                    $stream = ssh2_exec($conn, "sleep 5");
                    $stream = ssh2_exec($conn, 'gluster peer probe server2;');
                    $stream = ssh2_exec($conn, "sleep 1");
                    $stream = ssh2_exec($conn, "gluster volume create file_store replica 2 transport tcp server:$this->serverStorage server2:$this->serverStorage force;");
                    $stream = ssh2_exec($conn, "sleep 2");
                    $stream = ssh2_exec($conn, "gluster volume start file_store");
                    $stream = ssh2_exec($conn, "mount.glusterfs server:/file_store /var/www");
                    $stream = ssh2_exec($conn, "sleep 1");
                    
                    //php
                    $stream = ssh2_exec($conn, "yum install -y nginx php56w-fpm php56w-mysql");
                    $stream = ssh2_exec($conn, "sleep 2");
                    $stream = ssh2_exec($conn, "sed -i s/\;cgi\.fix_pathinfo\=1/cgi\.fix_pathinfo\=0/g /etc/php.ini");
                    //start services on boot
                    $stream = ssh2_exec($conn, "chkconfig glusterd on; chkconfig nginx on; chkconfig php-fpm on;");
                    //Nginx
                    $stream = ssh2_exec($conn, "wget https://raw.githubusercontent.com/jb376/Nginx-configuration/master/default -O /etc/nginx/conf.d/default.conf");
                    $stream = ssh2_exec($conn, "sleep 1");
                    $stream = ssh2_exec($conn, "chown -Rf nginx:nginx /var/www");
                    $stream = ssh2_exec($conn, "sleep 5");
                    $stream = ssh2_exec($conn, "service nginx restart");
                    $stream = ssh2_exec($conn, "sleep 2");


                  stream_set_blocking($stream, true);
                $output = stream_get_contents($stream);
                echo $output;
                    fclose($conn);
   
        }
        //one or two HA servers 
        public function HAproxy(){ 
            if($this->Hostname == "haproxy")
            {
                echo "this code is for server1 haproxy";
            
            }
            else{
                echo "this code is for server2,3 ,4 HA proxy";
                
            }
            
        }
        
            public function DatabaseServer(){ 
            if($this->Hostname == "database")
            {
                echo "this code is for server1 database";
            
            }
            else{
                echo "this code is for server2,3 ,4 HA proxy";
            }
        }
}

class installer
{
    public static function create($host,$ip, $password, $localIP, $storage)
    {
        return new install($host,$ip, $password, $localIP, $storage);
    }
}

$t_server = $_REQUEST["counter"];

$counter = 1;

while ($t_server >= $counter){
    $server = $_REQUEST["server"]["$counter"];
    #print_r($server);
   $host =  $_REQUEST["hostname".$counter];
    $runscript = installer::create($host,$server["ip"],$server["password"],$_REQUEST,"/store");
    print_r($runscript->GlusterFs());
        ++$counter;

    
};
#$list = array($server+$counter)
//print_r($runscript->Haproxy());

        
?>