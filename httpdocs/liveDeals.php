<? include_once("top.php"); ?>
<style>
	.gm-style-iw{
		width: auto !important;
	}
</style>

		        <div id="content" class="contact">

		        	<div class="hero" id="map-container-api"></div>

		        	
					<div class="wrapped-content">
						<h2>Contact</h2>

						<p>And because absurdly against reindeer <mark class="yellow">amphibiously hello excluding</mark> hence input until coasted dear climbed as jeepers alas unlike juggled some gerbil as rhinoceros inconsiderately a boastful one panda jellyfish timorously across yikes along much the beneath goodness successfully <a href="#">lackadaisically</a> and a a meadowlark less and darn some after due much.</p>
						<p>Suavely as darn cassowary wow swam joyfully well since and the circa <strong>forlorn hedgehog</strong> bandicoot touched via yellow re-laid krill malicious hyena the fragrant raccoon neutral ape a held explicit wasp that normally darn felicitously grimy scurrilous the.</p>

        				<div class="widget">
        					<h3>Address</h3>
        					<address>
							<span class="mark-text">Now Inc.</span>
							<br>
							Magical Ocean Avenue, 540/10
							<br>
							San Francisco, 889 02
							</address>
        				</div>

						<div class="widget">
							<h3>Social Networks</h3>
							<ul class="inline-list">
		        				<li><a class="icon-twitter" href="#"></a></li>
		        				<li><a class="icon-facebook" href="#"></a></li>
		        				<li><a class="icon-dribbble" href="#"></a></li>
		        				<li><a class="icon-youtube" href="#"></a></li>
		        				<li><a class="icon-rss" href="#"></a></li>
		        			</ul>
						</div>

						<div class="widget">
							<h3>Skills</h3>
        					<div class="progress radius large-12 lightblue"><span class="meter" style="width: 48%">&nbsp;&nbsp;&nbsp;&nbsp;PHP</span></div>
        					<div class="progress radius large-12 green"><span class="meter" style="width: 90%">&nbsp;&nbsp;&nbsp;&nbsp;CSS/HTML</span></div>
        					<div class="progress radius large-12 orange"><span class="meter" style="width: 78%">&nbsp;&nbsp;&nbsp;&nbsp;JavaScript</span></div>
						</div>

													<form id="contact-form" class="full-width" action="" data-abide>
								<h3>Send us an e-mail!</h3>
		        				
        						<p>
        							<label for="name">Your Name <span class="mark-text">(Required)</span></label>
    					
        							<input type="text" name="name" id="name" required>
        						</p>
        				
        						<p>
        							<label for="e-mail">Your E-mail <span class="mark-text">(Required)</span></label>
        				
        							<input type="email" class="h5-email" name="e-mail" id="e-mail" required>
        						</p>
        				
        						<p>
        							<label for="subject">Subject</label>
        				
        							<input type="text" name="subject" id="subject">
        						</p>
        				
        						<p>
        							<label for="text">Your Message <span class="mark-text">(Required)</span></label>
        				
        							<textarea name="text" id="text" cols="30" rows="10" required></textarea>
        						</p>
        				
        						<p>
        							<input class="button radius" type="submit" value="Send">
        							<input class="push-right push-small button radius secondary" type="reset" value="Reset">
        						</p>
	        				</form>
						
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
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="http://www.keith-wood.name/js/jquery.countdown.js"></script>
<link rel="stylesheet" href="http://www.keith-wood.name/css/jquery.countdown.css"/>

	    <script src='js/plugins/klass.js'></script>
<script src='js/plugins/photoswipe.js'></script>
<script src='js/plugins/hammer.js'></script>
<script src='js/plugins/flexslider.js'></script>
<script src='js/plugins/h5validate.js'></script>
<script src='js/plugins/responsivetables.js'></script>
<script src='js/plugins/prism.js'></script>
	    <script src='js/script.js'></script>
<script language="JavaScript" src="js/countdown.js"></script>



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