<?php
    $con = new SQLite3('sql.db');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>所有使用者</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script type="text/javascript" src="https://i3spa.com.tw/000/js/jquery-ui.js"></script>
		<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>
<style>
.nav-item{
	color:white;
	
	}
#input{
	position:absolute;
	top:0;
	left:0;
	opacity:0;
	z-index:-10;
	}	
.link{
	display:none;
}	
.popover {
    background: #44bd5e!important;
}
.bs-popover-top .arrow:after {
  border-top-color:#44bd5e;
}
#popOverBox {
  color:white;
}
.container{
	    max-width: 1400px;
}
</style>
  <div> <!-- diff : .container 包住 nav -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark nav nav-pills nav-fill">
      <h1 class="navbar-brand">吳柏HITS</h1>
      <div class="nav-item nav-link active" onclick=backedit(this)>無套名單</div>
  		<div class="nav-item nav-link"onclick=testa(this)>無套統計</div>
 		  <div class="nav-item nav-link disabled"onclick=testg(this)>null</div>
    </nav>
  </div>
<body>
	
	<div id='message'>
		
	</div>
<div  class="container editor">
<h1>一天只會更新一次內容，下面搜尋欄可以搜等級或腳色名稱ex:本多  or 65</h1>
<h1>現在等級30以上都會有紀錄</h1>
<span>搜尋:</span><input id='search'>
<textarea id="input"></textarea>
<table class="table table-bordered table-hover">
    <tr><th>編號</th><th>玩家名稱</th><th>等級</th><th>網址</th><th>狀態</th><th>腳色名稱</th><th>還可以扁</th></tr>
<?php
$results = $con->query('SELECT * FROM uberhits');
while ($row = $results->fetchArray(SQLITE3_ASSOC)){
	$id= $row['id'];
    $name= $row['name'];
    $lv=$row['lv'];
    $link=$row['link'];
    $stat=$row['stat'];
    $chara=$row['chara'];
	$count=$row['count'];
    //print_r($result_arr);
    
     echo "<tr><td>$id</td><td>$name</td><td>$lv</td><td><span class='link' id='$id'>$link</span><button type='button' class='btn btn-clipboard' data-container='body' data-toggle='popover' data-placement='top' id='$id'  onclick='copytext($id)'><img src='https://icons.getbootstrap.com/icons/clipboard.svg'></i></button></td><td>$stat</td><td>$chara</td><td>$count 次</td></tr>";
}
?>
</table>
</div>
<div  class="container backup">
</div>
<div  class="container analytics">
</div>
<script>
var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}} 

/*$(window).scroll(function () {
            if ($(window).scrollTop() == $(document).height() - $(window).height()) {
                appendData();
            }
});*/
$(function (){
    $("[data-toggle='popover']").popover(
	{
	html : true,
	content : '<div id="popOverBox">複製網址成功</div>',			
	}	
	);
});
$("#search").keyup(function() {
	 if ( ($(this).val().length < 1)) return ;
	 search_word = $('#search').val();
	 uri= 'sqllib.php?a=search&vv='+search_word;
	 $.ajax({ url: uri, cache: false  }) .done(function( msg ) { 
    //	 $(th).next().html('<br>'+msg);
    		$('.table').html(msg);
    	 //$(th).css('backgroundColor','rgb(250, 255, 189)' );
    	 $("[data-toggle='popover']").popover({html : true,content : '<div id="popOverBox">複製網址成功</div>'}	);
    	}); 
});






function backedit(th){
    	$('.container').hide();
    	$('.editor').show();
	}

function copytext(tt){
	var text = document.getElementById(tt).innerText;
    setTimeout(function(){ $("[data-toggle='popover']").popover('hide'); }, 1000);
	var input = document.getElementById('input');
	input.value = text;
	input.select();
	document.execCommand("copy");

	
	}

function testa(th){
		uri= 'sqllib.php?a=analytics'; 
	  $.ajax({ url: uri, cache: false  }) .done(function( msg ) { 
    //	 $(th).next().html('<br>'+msg);
    	 //$(th).css('backgroundColor','rgb(250, 255, 189)' );
    	$('.container').hide();
    	$('.analytics').html(msg);
    	$('.analytics').show();
    	});
	}
	
var activeNavItem = $('.nav-item');

activeNavItem.click(function(){
  activeNavItem.removeClass('active');
  $(this).addClass('active');  
});

</script>
</body>
</html>