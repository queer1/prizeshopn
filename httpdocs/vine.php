<? include_once("top.php"); ?>


		        <div id="content" class="blog">

		        	

				    <article id="format-quote" class="format-quote">
			            <p class="icon-bg icon-quote"></p>
				        <div class="wrapped-content">
				            <blockquote>
				                &ldquo;Vine videos from Kohls fashionista's.&ldquo;
				            </blockquote>
				        </div>
				    </article>

		        	
		        	<article class="format-video" style="margin-top:-23px">
		        		<div style="text-align:center;">
<iframe class="vine-embed" src="https://vine.co/v/b39intXxu6e/embed/postcard" width="320" height="320" frameborder="0"></iframe><script async src="//platform.vine.co/static/scripts/embed.js" charset="utf-8"></script>
		        		</div>
		        		<div class="wrapped-content" style="margin-top:-13px">
		        			<p>
		        				<a href="#" class="button round full-width"><i class="icon-facebook" style="color: #FFF;"></i> <i class="icon-twitter" style="color: #FFF;"></i>&nbsp; Share & get 10 points</a>
		        			</p>
		        		</div>
		        	</article>

		        	<article class="format-video" style="margin-top:-53px">
		        		<div style="text-align:center;">
<iframe class="vine-embed" src="https://vine.co/v/bYHEDt75JgU/embed/postcard" width="320" height="320" frameborder="0"></iframe><script async src="//platform.vine.co/static/scripts/embed.js" charset="utf-8"></script>
		        		</div>
		        		<div class="wrapped-content" style="margin-top:-19px">
		        			<p>
		        				<a href="#" class="button round full-width"><i class="icon-facebook" style="color: #FFF;"></i> <i class="icon-twitter" style="color: #FFF;"></i>&nbsp; Share & get 10 points</a>
		        			</p>
		        		</div>
		        	</article>




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