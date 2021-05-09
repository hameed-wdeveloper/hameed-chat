
<link rel="icon" href="favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<img src="favicon.png" alt="" style="
    height: 100px;
    /* background-color: khaki; */
    border: 4px solid 
    black;
    border-radius: 20px;
">
<?php
session_start ();
function loginForm() {
    echo '
	<div class="form-group">
    <div id="loginform">
    <form action="index.php" method="post">
    <h1>Hameed Live Chat</h1><hr/>
    <label for="name">Please enter your name to proceed..</label>
    <input type="text" name="name" id="name" class="my-2 form-control" placeholder="Enter Your Name"/>
    <input type="email" name="email" id="name" class="my-2 form-control" placeholder="Enter Your Email"/>
    <input type="submit" class="btn btn-default" name="enter" id="enter" value="Enter" />
    </form>
    </div>
	</div>
    ';
}
 
if (isset ( $_POST ['enter'] )) {
    if ($_POST ['name'] != "") {
        $_SESSION ['name'] = stripslashes ( htmlspecialchars ( $_POST ['name'] ) );
        $cb = fopen ( "log.html", 'a' );
        fwrite ( $cb, "<div class='msgln'><i>User " . $_SESSION ['name'] . " has joined the chat session.</i><br></div>" );
        fclose ( $cb );
    } else {
        echo '<span class="error">Please Enter a Name</span>';
    }
}
if (isset ( $_POST ['enter'] )) {
    if ($_POST ['email'] != "") {
        $_SESSION ['email'] = stripslashes ( htmlspecialchars ( $_POST ['email'] ) );
        $cb = fopen ( "log.html", 'a' );
        fwrite ( $cb, "<!-- <div class='msgln'><i>User " . $_SESSION ['email'] . " has joined the chat session.</i><br></div>-->" );
        fclose ( $cb );
    } else {
        echo '<span class="error">Please Enter a Email</span>';
    }
}
 
if (isset ( $_GET ['logout'] )) {
    $cb = fopen ( "log.html", 'a' );
    fwrite ( $cb, "<div class='msgln'><i>User " . $_SESSION ['name'] . " has left the chat session.</i><br></div>" );
    fclose ( $cb );
    session_destroy ();
    header ( "Location: index.php" );
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Hameed live chat organisation.</title>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <style>
@import url('https://fonts.googleapis.com/css2?family=Itim&display=swap');
</style>
	<script type="text/javascript" src="js/jquery.min.js"></script>
</head>
<body>
<?php
	if (! isset ( $_SESSION ['name'] )) {
	loginForm ();
	} else {
?>
<div id="wrapper">
	<div id="menu">
	<h1>Hameed live chat!</h1><hr/>
		<p class="welcome"><b>HI - <a><?php echo $_SESSION['name']; ?></a></b></p>
		<p class="logout"><a id="exit" href="#" class="btn btn-default">Exit Live Chat</a></p>
	<div style="clear: both"></div>
	</div>
	<div id="chatbox">
	<?php
		if (file_exists ( "log.html" ) && filesize ( "log.html" ) > 0) {
		$handle = fopen ( "log.html", "r" );
		$contents = fread ( $handle, filesize ( "log.html" ) );
		fclose ( $handle );

		echo $contents;
		}
	?>
	</div>
<form name="message" action="">
	<input name="usermsg" class="form-control" type="text" id="usermsg" placeholder="Create A Message" />
	<input name="submitmsg" class="btn btn-default" type="submit" id="submitmsg" value="Send" />
</form>
</div>
<script type="text/javascript">
$(document).ready(function(){
});
$(document).ready(function(){
    $("#exit").click(function(){
        var exit = confirm("Are you sure you want quit?");
        if(exit==true){window.location = 'index.php?logout=true';}     
    });
});
$("#submitmsg").click(function(){
        var clientmsg = $("#usermsg").val();
        $.post("post.php", {text: clientmsg});             
        $("#usermsg").attr("value", "");
        loadLog;
    return false;
});
function loadLog(){    
    var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
    $.ajax({
        url: "log.html",
        cache: false,
        success: function(html){       
            $("#chatbox").html(html);       
            var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
            if(newscrollHeight > oldscrollHeight){
                $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal');
            }              
        },
    });
}
setInterval (loadLog, 2500);
</script>
<?php
}
?>
</body>
</html>