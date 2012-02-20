				<!-- </CONTENT> -->
			</div>
		</div>

        <!-- <FOOTER> -->
        <div id="footer">
			<div id="footer_wrapper">
				<div id="copyright">
					Running on <a href="<?php echo url::base(); ?>site/version/">live.WIPUP</a> - 
					The Eadrax Team &copy; 2012 - 
					<a href="<?php echo url::base(); ?>site/development/">Contribute</a> - <a href="<?php echo url::base() .'site/legal/'; ?>">Legal Information</a><br /><br />
				</div>
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
