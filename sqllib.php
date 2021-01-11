<?php


$con = new SQLite3('sql.db');

function getvar($vv) {  // strong type uri filter POST is prior then GET
	       $ss = '';
         if (isset($_GET[$vv]))  $ss = $_GET[$vv];
         if (isset($_POST[$vv])) $ss = $_POST[$vv];
         $ss  = str_replace("'",'x',$ss);    // to prevent sql injection
         $ss  = str_replace("..",'x',$ss);   // to prevent directory access
         $ss  = str_replace("<",'x',$ss);    // to prevent CSS
         $ss  = str_replace(">",'x',$ss);    // to prevent CSS
         if ($ss=="" && isset($_SESSION[$vv]) )  $ss =  $_SESSION[$vv];
         return $ss;
}
function logger($vv) {
   $ho = $_SERVER['SERVER_NAME']."-".date('Y-m');
   $ho = date('Y-m-d');
   $ip = str_pad($_SERVER['REMOTE_ADDR'],15);
   //$fp = fopen("/var/host/logs/$ho.appTracert.log", 'a+');
   $fp = fopen("C:\Apache24\htdocs\003\logs\app-$ho.log", 'a+');
   fwrite($fp, date('Y-m-d G:i:s').'  '.$ip.' '.$vv."\n");
   fclose($fp);
  }
$a     = getvar('a');
$tb  = getvar('tb'); // "home" or 'cart'  ,  or 'pro'
$id    = getvar('id');  
$vv    = getvar('vv');
if ($a=='uudata')  uudata($con,$tb,$id,$vv);
if ($a=='new')  newt($con);
if ($a=='more')  more($con);
if ($a=='delete')  deleterow($con,$id);
if ($a=='search')  search_row($con,$vv);
if ($a=='backup')  backup($con);
if ($a=='analytics')  analytics($con);
if ($a=='toxls3')  toxls3($con);
if ($a=='top6')  top6($con);
if ($a=='failed')  failed($con);
if ($a=='used')  used($con);

function uudata($con,$tb,$id,$vv){
	      // $p2 = ""; 
	      //if ( substr($id,0,5) =='newpd' )  $p2  = get1field("select prdt_name from product where prdt_no = '$vv'");	 
	       $vv = base64_decode(  str_replace(' ','+',$vv) );
				 $sqlc = "UPDATE mlaw2 SET $tb = '$vv' WHERE id = '$id'";
				 logger($sqlc);
         $csc = mysqli_query($con, $sqlc);
	           
         
	}
function newt($con){
	      // $p2 = ""; 
	      //if ( substr($id,0,5) =='newpd' )  $p2  = get1field("select prdt_name from product where prdt_no = '$vv'");	 

				 $sqlc = "SELECT * FROM mlaw2 ORDER BY id DESC limit 1";
				 logger($sqlc);
         $csc = mysqli_query($con, $sqlc);
         $row = $csc->fetch_assoc() ;
         $total = $row['id'];
         $total = $total+1;
	       $sqli = "insert into mlaw2 (id) values ('$total')";
	       $csc = mysqli_query($con, $sqli);
	       $result .= "<tr><td>$total</td><td><input class='change' id='keyword' code='$total' value='$keyword'></td><td><textarea class='change' rows='6' id='content' code='$total'> </textarea></td><td><input class='change' id='url' code='$total'  value=></td><td><input class='change' id='img' code='$total'   value=></td><td><input class='change' id='title' code='$total'  value=></td><td><a id='$total' class='delete' href='#'>刪除</a></td></tr>";
         echo $result,$total;  
	}


function deleterow($con,$id){
	      // $p2 = ""; 
	      //if ( substr($id,0,5) =='newpd' )  $p2  = get1field("select prdt_name from product where prdt_no = '$vv'");	 
				$result = '';
				$sqlc = "DELETE FROM mlaw2 WHERE id= $id";
				mysqli_query($con, $sqlc) or die(logger("無法刪除".mysql_error())); 
				$result = 'ok';
        echo $result;    
}

function backup($con){
	      // $p2 = ""; 
	      //if ( substr($id,0,5) =='newpd' )  $p2  = get1field("select prdt_name from product where prdt_no = '$vv'");	 
				$r = file_get_contents("history.html");
        echo  $r;    
}

function analytics($con){
	      // $p2 = ""; 
	      //if ( substr($id,0,5) =='newpd' )  $p2  = get1field("select prdt_name from product where prdt_no = '$vv'");	 
				$r = file_get_contents("chart.html");
        echo  $r;    
}


function toxls3($con){
	      // $p2 = ""; 
	      //if ( substr($id,0,5) =='newpd' )  $p2  = get1field("select prdt_name from product where prdt_no = '$vv'");	 
				expExcel("mlaw3.xls");  
  	    echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>Excel</title>';
  	    $r = '';
  	    $sqlc="SELECT * FROM mlaw2";
				$csc = mysqli_query($con, $sqlc);
				$r .= "<tr><td>id</td><td>keyword</td><td>content</td><td>url</td><td>img</td><td>title</td></tr>";
				while ($row = $csc->fetch_array(MYSQLI_ASSOC)){
 				  $id=$row['id'];
			    $keyword=$row['keyword'];
  			  $content=$row['content'];
		 			$url=$row['url'];
 				  $img=$row['img'];
 				  $title=$row['title'];
   				 //print_r($result_arr);
  			$r .= "<tr><td>$id</td><td>$keyword</td><td>$content</td><td>$url</td><td>$img</td><td>$title</td></tr>";
				}
  	      	  
  	    $result = "<div class='widget'>select * from mlaw2<table id='stable' data='xx'>$r</table></div>";
        echo $result;    
}

function expExcel($vv) {
         header("Content-type:application/vnd.ms-excel"); // 送出header
         header("Content-Disposition:filename=$vv");  //  指定檔名
	} 



function more($con){
				$last    = getvar('last');  
				$more    = getvar('mores');
	      // $p2 = ""; 
	      //if ( substr($id,0,5) =='newpd' )  $p2  = get1field("select prdt_name from product where prdt_no = '$vv'");	 

				$sqlc = "SELECT * FROM mlaw2  WHERE id BETWEEN $more AND $last ORDER BY id DESC";
				$csc = mysqli_query($con, $sqlc); 
				$result='';

while ($rowc = $csc->fetch_array(MYSQLI_ASSOC)){ 
    $id=$rowc['id'];
    $keyword=$rowc['keyword'];
    $content=$rowc['content'];
    $url=$rowc['url'];
    $img=$rowc['img'];
    $title=$rowc['title'];
    //print_r($result_arr);
   $result .= "<tr><td>$id</td><td><input class='change' id='keyword' code='$id' value='$keyword'></td><td><textarea class='change' rows='6' id='content' code='$id'>$content</textarea></td><td><input id='url' code='$id'  class='change' value=$url></td><td><input  class='change' id='img' code='$id'   value=$img></td><td><input class='change' id='title' code='$id'  value=$title></td><td><a id='$id' href='#'>刪除</a></td></tr>";

}
     echo $result;    
}

function search_row($con,$vv){
$results = $con->query("SELECT * FROM uberhits where chara like '%$vv%' or lv = '$vv'");
$result= '<tr><th>編號</th><th>玩家名稱</th><th>等級</th><th>網址</th><th>狀態</th><th>腳色名稱</th><th>還可以扁</th></tr>';
while ($row = $results->fetchArray(SQLITE3_ASSOC)){ 
	
    $id= $row['id'];
    $name= $row['name'];
    $lv=$row['lv'];
    $link=$row['link'];
    $stat=$row['stat'];
    $chara=$row['chara'];
	$count=$row['count'];
    //print_r($result_arr);
   $result .= "<tr><td>$id</td><td>$name</td><td>$lv</td><td><span class='link' id='$id'>$link</span><button type='button' class='btn btn-clipboard' data-container='body' data-toggle='popover' data-placement='top' id='$id'  onclick='copytext($id)'><img src='https://icons.getbootstrap.com/icons/clipboard.svg'></i></button></td><td>$stat</td><td>$chara</td><td>$count 次</td></tr>";
}
     echo $result;    
}

function top6($con) {
				$results = $con->query('select chara,count(chara) as total from uberhits GROUP BY chara order by total DESC');
				 $result = array();
				 while ($row = $results->fetchArray(SQLITE3_ASSOC)){
		 			$log=$row['chara'];
 				  $total=$row['total'];
 				  $tt = array($log,$total) ;
  		  	array_push($result,$tt);
				}
				$result = json_encode($result);
				echo $result;
	} 
	
function failed($con){
				$sqlc = "select *,count(log) as total from mlaw2_log where status = 'x' GROUP BY log order by total DESC";
				$csc = mysqli_query($con, $sqlc); 
				$result='';
while ($rowc = $csc->fetch_array(MYSQLI_ASSOC)){ 
    $log=$rowc['log'];
    $total=$rowc['total'];
    $result .= "<tr><td>$log</td><td>$total</td><td></td></tr>";

}
     echo $result;    
}
function used($con) {
				 $results = $con->query('select chara,count(chara) as total from uberhits GROUP BY chara order by total DESC limit 1');
				 $row = $results->fetchArray(SQLITE3_ASSOC);
				 $chara = $row['chara'];
				 $results = $con->query('select lv,count(lv) as total from uberhits GROUP BY lv order by total DESC limit 1');
				 $row = $results->fetchArray(SQLITE3_ASSOC);
				 $lv = $row['lv'];
				 $result = array($lv,$chara);
				 $result = json_encode($result);
				echo $result;
	} 