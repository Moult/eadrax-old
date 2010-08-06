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
					<li><a href="<?php echo url::base(); ?>projects/view/category/1/">2D Animation</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/2/">3D Animation</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/3/">3D Modeling</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/4/">Application Programming</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/5/">Digital Paintings</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/6/">Documents &amp; Articles</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/7/">Games</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/8/">Homework</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/9/">Interface Design</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/10/">Literature &amp; Fiction</a></li>
                </ol>

                <ol id="categories_2">
                    <li><a href="<?php echo url::base(); ?>projects/view/category/11/">Magazines &amp; Newsletters</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/12/">Miscellaneous</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/13/">Music</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/14/">Posters</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/15/">Presentations</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/16/">Sound Effects</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/17/">Vector Art</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/18/">Video</a></li>
                    <li><a href="<?php echo url::base(); ?>projects/view/category/19/">Website Programming</a></li>
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
			<a href="<?php echo url::base(); ?>site/tour/">What is WIPUP?</a> - <a href="<?php echo url::base(); ?>site/sponsor/">Sponsor</a> - <a href="<?php echo url::base(); ?>site/development/">Development</a> - <a href="<?php echo url::base(); ?>site/search/">Search</a> - <a href="<?php echo url::base() .'site/legal/'; ?>">Legal Information</a>
        </div>
        <!-- </FOOTER> -->
	</body>
</html>
