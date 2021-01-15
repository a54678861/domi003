<?php
$con = new SQLite3('sql.db');
function getvar($vv) { 
	     $ss = '';
         if (isset($_GET[$vv]))  $ss = $_GET[$vv];
         if (isset($_POST[$vv])) $ss = $_POST[$vv];
         return $ss;
}
function logger($vv) {
   $ho = $_SERVER['SERVER_NAME']."-".date('Y-m');
   $ho = date('Y-m-d');
   $ip = str_pad($_SERVER['REMOTE_ADDR'],15);
   $fp = fopen("C:\Apache24\htdocs\003\logs\app-$ho.log", 'a+');
   fwrite($fp, date('Y-m-d G:i:s').'  '.$ip.' '.$vv."\n");
   fclose($fp);
  }
$a     = getvar('a');
$tb  = getvar('tb'); 
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
	$vv = base64_decode(  str_replace(' ','+',$vv) );
	$sqlc = "UPDATE mlaw2 SET $tb = '$vv' WHERE id = '$id'";
    $csc = mysqli_query($con, $sqlc);   
	}
function newt($con){
	$sqlc = "SELECT * FROM mlaw2 ORDER BY id DESC limit 1";
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
	$result = '';
	$sqlc = "DELETE FROM mlaw2 WHERE id= $id";
	mysqli_query($con, $sqlc) or die(logger("無法刪除".mysql_error())); 
	$result = 'ok';
    echo $result;    
}

function backup($con){
	$r = file_get_contents("history.html");
     echo  $r;    
}

function analytics($con){
	$r = file_get_contents("chart.html");
    echo  $r;    
}
function more($con){
	$last    = getvar('last');  
	$more    = getvar('mores');
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