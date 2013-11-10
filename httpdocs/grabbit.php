<? include_once("top.php"); ?>


		        <div id="content">
<style>
#timer {
width:48%;
float:left;
text-align:center
}

#right {
width:48%;
float:right;
text-align:center
}
</style>
<div style="min-height:200px; margin-top:13px">
<div id="timer">

</div>
<? $gameId=140; ?>
<script>
var refreshId = setInterval(function()
{
     $('#timer').load('game/gameTimer.php?id=<?php echo $gameId ?>').fadeIn("slow");
}, 1000);

</script>

<div id="right">
<a href="#" class="button round large" style="padding:13px">Join</a><br>
<a href="#" class="button round success large" style="padding:13px">Grab Friends</a>
</div>
                </div>
<div style="padding:7px; text-align:center; margin-top:-43px">
<a href="#" class="button round green tiny">Grabbit Hole</a>&nbsp;<a href="#" class="button round lightblue tiny">Grabbit</a>&nbsp;
<a href="#" class="button round red tiny">Slap Grabber</a>
</div>
		        	<div id="gallery" class="gallery-container two-column photoswipe">
		        		<!-- Shoes -->
						<?php
						    $sort= rand(1, 6);
							$httpGetCallUrl = "http://qe11-openapi.kohlsecommerce.com/v1/catalog/4294718926+4294719776?sortID=$sort&limit=1";
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

	    <script src='js/plugins/klass.js'></script>
<script src='js/plugins/photoswipe.js'></script>
<script src='js/plugins/hammer.js'></script>
<script src='js/plugins/flexslider.js'></script>
<script src='js/plugins/h5validate.js'></script>
<script src='js/plugins/responsivetables.js'></script>
<script src='js/plugins/prism.js'></script>
	    <script src='js/script.js'></script>

	<!-- =Custom Scripts -->


	    
    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->

	    <script>
	        var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
	        (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
	        g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
	        s.parentNode.insertBefore(g,s)}(document,'script'));
	    </script>

    <!-- =Google Analytics -->
	    

	    
	</body>
</html>