                <!-- </CONTENT> -->
            </div>
        </div>

        <!-- <FOOTER> -->
        <div id="footer">
            <div id="project_categories">
                <h3>
                    Project Categories
                </h3>

                <ol class="categories">
					<li><a href="<?php echo url::base(); ?>projects/view/category/1/">2D Animation</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/2/">3D Animation</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/3/">3D Modeling</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/4/">Application Programming</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/5/">Digital Paintings</a></li>
                </ol>


                <ol class="categories">
                    <li><a href="<?php echo url::base(); ?>projects/view/category/6/">Documents &amp; Articles</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/7/">Games</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/8/">Homework</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/9/">Interface Design</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/10/">Literature &amp; Fiction</a></li>
                </ol>

                <ol class="categories">
                    <li><a href="<?php echo url::base(); ?>projects/view/category/11/">Magazines &amp; Newsletters</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/12/">Miscellaneous</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/13/">Music</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/14/">Photography</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/15/">Posters &amp; Presentations</a></li>
                </ol>

                <ol class="categories">
                    <li><a href="<?php echo url::base(); ?>projects/view/category/16/">Sound Effects</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/17/">Vector Art</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/18/">Video &amp; Film</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/19/">Website Programming</a></li>
                </ol>
				<div style="clear: both;"></div>
            </div>

			<div id="copyright">
				Running on <a href="<?php echo url::base(); ?>site/version/">live.WIPUP</a> - 
				The Eadrax Team &copy; 2011
				<br /><br />
				<a href="<?php echo url::base(); ?>site/development/">Give back to WIPUP</a> - <a href="<?php echo url::base(); ?>site/search/">Search</a> - <a href="<?php echo url::base() .'site/legal/'; ?>">Legal Information</a>
			</div>
        </div>

        <!-- </FOOTER> -->
<?php if (($this->uri->segment(1) == 'users' && ($this->uri->segment(2) == 'register' || $this->uri->segment(2) == 'login' || $this->uri->segment(2) == 'rpx')) || $this->uri->string() == 'site' ) { ?>
		<script type="text/javascript">
		  var rpxJsHost = (("https:" == document.location.protocol) ? "https://" : "http://static.");
		  document.write(unescape("%3Cscript src='" + rpxJsHost +
		"rpxnow.com/js/lib/rpx.js' type='text/javascript'%3E%3C/script%3E"));
		</script>
		<script type="text/javascript">
		  RPXNOW.overlay = true;
		  RPXNOW.language_preference = 'en';
		</script>
<?php } ?>
	</body>
</html>
