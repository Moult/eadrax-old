<script src="<?php echo url::base(); ?>js/scrollable.js"></script>
<?php foreach ($projects as $pid => $p_name) { ?>
<h2>
	<!-- this is statically linked and should not be -->
	<img src="/images/icons/coffee_mug.png" width="48" height="48" class="icon" alt="" />
	<?php echo $p_name; ?>'s Timeline
</h2>

<?php if (empty($timelines[$pid])) { ?>

<p>
	Nothing to see here!
</p>

<?php } else { ?>

<div class="scrollableWrapper" style="margin-left: auto; margin-right: auto;">

	<div class="navi"></div>

	<!-- navigator -->
	<!-- prev link -->
	<a class="prev"></a>
	<!-- root element for scrollable -->
	<div class="scrollable">
		<div id="thumbs">
			<?php echo $timelines[$pid]; ?>
		</div>
	</div>
	<!-- next link -->
	<a class="next"></a>
	<!-- let rest of the page float normally -->
	<br clear="all" />
</div>
<?php } } ?>
