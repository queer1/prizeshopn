<? include_once("top.php"); 

?>

		        <div id="content">
                <div style="text-align:center">
<?
if($userID==""){?>                
                 <a  href="javascript:auth.login('facebook', {
  rememberMe: true,
  scope: 'email,user_birthday,publish_stream,user_location,user_games_activity,friends_games_activity'
});" class="button round success tiny"><i class="icon-facebook" style="color: #fff;"></i>Start +10 points</a>&nbsp;&nbsp;
                 <a  href="javascript:auth.login('twitter', {
  rememberMe: true
});" class="button round lightblue tiny"><i class="icon-twitter" style="color: #fff;"></i> Start +10 points</a>
<? }?>               
<? if($userID!=""){?>
<p style="text-align:center"><h3>Points: 100</h3> </p>
<? }?>
 </div>
		        	<div id="gallery" class="gallery-container three-column photoswipe">
						<!-- Sweaters -->
						<?php
						    $sort= rand(1, 6);
							$httpGetCallUrl = "http://qe11-openapi.kohlsecommerce.com/v1/catalog/4294719371?sortID=$sort&limit=6";
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch, CURLOPT_URL, $httpGetCallUrl);
							curl_setopt($ch,CURLOPT_HTTPHEADER,array('Accept: application/json','X-APP-API_KEY: 4iY860UaqswaZAjH3rVTCAGb15kDITNV'));
							$result = curl_exec($ch);
							$json = json_decode($result, true);
							$numberOfITems = $json['limit'];
							for($i=0; $i<$numberOfITems; $i++) {
								?>
								<a href= "<?php echo($json['payload']['products'][$i]['image']['url']); ?> ">
							    	<img src="<?php echo($json['payload']['products'][$i]['image']['url']); ?> ">
								</a>
								<?php
								}
							curl_close($ch);
						?>
						<!-- Shoes -->
						<?php
						    $sort= rand(1, 6);
							$httpGetCallUrl = "http://qe11-openapi.kohlsecommerce.com/v1/catalog/4294718926+4294719776?sortID=$sort&limit=6";
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch, CURLOPT_URL, $httpGetCallUrl);
							curl_setopt($ch,CURLOPT_HTTPHEADER,array('Accept: application/json','X-APP-API_KEY: 4iY860UaqswaZAjH3rVTCAGb15kDITNV'));
							$result = curl_exec($ch);
							$json = json_decode($result, true);
							$numberOfITems = $json['limit'];
							for($i=0; $i<$numberOfITems; $i++) {
								?>
								<a href= "<?php echo($json['payload']['products'][$i]['image']['url']); ?> ">
							    	<img src="<?php echo($json['payload']['products'][$i]['image']['url']); ?> ">
								</a>
								<?php
								}
							curl_close($ch);
						?><!-- Jewelry -->
						<?php
						    $sort= rand(1, 6);
							$httpGetCallUrl = "http://qe11-openapi.kohlsecommerce.com/v1/catalog/4294737173?sortID=$sort&limit=6";
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch, CURLOPT_URL, $httpGetCallUrl);
							curl_setopt($ch,CURLOPT_HTTPHEADER,array('Accept: application/json','X-APP-API_KEY: 4iY860UaqswaZAjH3rVTCAGb15kDITNV'));
							$result = curl_exec($ch);
							$json = json_decode($result, true);
							$numberOfITems = $json['limit'];
							for($i=0; $i<$numberOfITems; $i++) {
								?>
								<a href= "<?php echo($json['payload']['products'][$i]['image']['url']); ?> ">
							    	<img src="<?php echo($json['payload']['products'][$i]['image']['url']); ?> ">
								</a>
								<?php
								}
							curl_close($ch);
						?>
						

		        		
		        	</div>
		        </div>

	        </section>

        <!-- =Main Content -->
	    

	    

	    </div>
    <!-- =Container -->
	    

    
        <!-- Hammer reload -->
          <script>
            setInterval(function(){
              try {
                if(typeof ws != 'undefined' && ws.readyState == 1){return true;}
                ws = new WebSocket('ws://'+(location.host || 'localhost').split(':')[0]+':35353')
                ws.onopen = function(){ws.onclose = function(){document.location.reload()}}
                ws.onmessage = function(){
                  var links = document.getElementsByTagName('link'); 
                    for (var i = 0; i < links.length;i++) { 
                    var link = links[i]; 
                    if (link.rel === 'stylesheet' && !link.href.match(/typekit/)) { 
                      href = link.href.replace(/((&|\?)hammer=)[^&]+/,''); 
                      link.href = href + (href.indexOf('?')>=0?'&':'?') + 'hammer='+(new Date().valueOf());
                    }
                  }
                }
              }catch(e){}
            }, 1000)
          </script>
        <!-- /Hammer reload -->
      

	    
	<!--jQuery & Google Maps -->
	
	    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	    <script>!window.jQuery && document.write('<script src="js/vendor/jquery.js"><\/script>')</script>

	    <!-- Google Maps script -->

	    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCpYpkxGMeJcDfwD5QJMhBZT0No7Jleg-E&sensor=false"></script>

	<!-- =jQuery & Google Maps -->


	
	<!-- Custom Scripts -->
<script type="text/javascript" src="https://cdn.firebase.com/v0/firebase.js"></script>
<script type="text/javascript" src="https://cdn.firebase.com/v0/firebase-simple-login.js"></script>
<script type="text/javascript">
  
// FirebaseSimpleLogin demo instantiation
var firebaseRef = new Firebase('https://prizeshopn.firebaseio.com');
var auth = new FirebaseSimpleLogin(firebaseRef, function(error, user) {
if (error) {
        // an error occurred while attempting login
alert(error);
} 
else if 
(user) {
var loginRef = new Firebase('https://prizeshopn.firebaseIO.com/users/active/'+user.id+'/');
loginRef.on('value', function(snapshot) {
if(snapshot.val() === null) {

$.post('login.php',{name:user.name,fname:user.first_name,lname:user.last_name,email:user.email,userName:user.username,gender:user.gender,age:user.age_range,birthday:user.birthday,location:user.location,games:user.user_games,friendsGames:user.friends_games_activity,timezone:user.timezone,verified:user.verified,devices:user.devices,socialId:user.id, provider:user.provider},function(data){
$('#loginBox').animate({'top':'-400px'},500);
parent.location.reload();
});

} 
else {
var two=2;
var id=snapshot.val().id;
$.post('login.php',{socialId:user.id,type:two,id:id,userName:user.name},function(data){$('#loginBox').animate({'top':'-400px'},500);location.reload();

});

};
});

  
        
        // Log out so we can log in again with a different provider.
        auth.logout();
      } else {
        // user is logged out
      }
    });
    function login(provider) {
      auth.login(provider);
    }
	
</script>

	    <script src='js/plugins/klass.js'></script>
<script src='js/plugins/photoswipe.js'></script>
<script src='js/plugins/hammer.js'></script>
<script src='js/plugins/flexslider.js'></script>
<script src='js/plugins/h5validate.js'></script>
<script src='js/plugins/responsivetables.js'></script>
<script src='js/plugins/prism.js'></script>
	    <script src='js/script.js'></script>
<script>
<!-- =Custom Scripts -->


	    
    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->


    <!-- =Google Analytics -->
	    

	    
	</body>
</html>