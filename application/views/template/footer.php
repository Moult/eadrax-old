                <!-- </CONTENT> -->
            </div>
        </div>

        <!-- <FOOTER> -->
        <div id="footer">
            <div id="project_categories">
                <h3>
                    Project Categories
                </h3>

                <ol id="categories_1">
                    <li><a href="#">2D Animation</a></li>
                    <li><a href="#">3D Animation</a></li>
                    <li><a href="#">3D Modeling</a></li>
                    <li><a href="#">Application Programming</a></li>
                    <li><a href="#">Digital Paintings</a></li>
                    <li><a href="#">Documents &amp; Articles</a></li>
                    <li><a href="#">Games</a></li>
                    <li><a href="#">Homework</a></li>
                    <li><a href="#">Interface Design</a></li>
                    <li><a href="#">Literature &amp; Fiction</a></li>
                </ol>

                <ol id="categories_2">
                    <li><a href="#">Magazines &amp; Newsletters</a></li>
                    <li><a href="#">Miscellaneous</a></li>
                    <li><a href="#">Music</a></li>
                    <li><a href="#">Posters</a></li>
                    <li><a href="#">Presentations</a></li>
                    <li><a href="#">Sound Effects</a></li>
                    <li><a href="#">Vector Art</a></li>
                    <li><a href="#">Video</a></li>
                    <li><a href="#">Website Programming</a></li>
                </ol>
            </div>

            <div id="latest_wips">
                <h3>
					Latest WIPs
                </h3>

					<?php foreach ($latest_data as $wip) { ?>
					<div class="latest_wip" style="float: left; height: 180px; text-align: center;">

							<div style="display: table-cell; height: 100px; margin-bottom: 5px; text-align: center;">
								<div style="border: 0px solid #F00; width: 110px; position: relative; top: 50px; height: <?php echo $wip['thumb_height']; ?>px; margin-top: -<?php echo $wip['thumb_offset']; ?>px; text-align: center;">
									<a href="<?php echo url::base() .'updates/view/'. $wip['id']; ?>"><img src="<?php echo $wip['filename0']; ?>" title="<?php echo $wip['summary']; ?>" alt="update icon" <?php if (!strpos($wip['filename0'], 'images/icons')) { echo 'style="border: 1px solid #999; padding: 1px;"'; } ?> /></a>
								</div>
							</div>

							<p>
								<?php if ($wip['pid'] != 1) { ?><a href="<?php echo url::base() .'projects/view/'. $wip['uid'] .'/'. $wip['pid'] .'/'; ?>"><?php } ?><?php echo $wip['project_name']; ?><?php if ($wip['pid'] != 1) { ?></a><?php } ?>
							</p>

							<p style="font-size: 10px;">
								By <?php if ($wip['uid'] != 1) { ?><a href="<?php echo url::base() .'profiles/view/'. $wip['user_information']['username'] .'/';?>"><?php } ?><?php echo $wip['user_information']['username']; ?><?php if ($wip['uid'] != 1) { ?></a><?php } ?>
							</p>

						</div>
					<?php } ?>

                <div id="browse_more">
					<a href="<?php echo url::base(); ?>projects/view/"><img src="<?php echo url::base(); ?>images/browse_more.png" alt="Browse More" /></a>
                </div>
            </div>
        </div>

        <div id="copyright">
            The Eadrax Team &copy; 2010
            <br /><br />
			<a href="http://github.com/Moult/Eadrax">Source</a> - <a href="<?php echo url::base(); ?>site/sponsor/">Sponsor</a> - <a href="<?php echo url::base(); ?>site/development/">Development</a> - <a href="#">Back to top</a>
        </div>
        <!-- </FOOTER> -->
	</body>
</html>
