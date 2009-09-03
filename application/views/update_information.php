<h2>
	<img src="<?php echo url::base(); ?>images/icons/globe_48.png" width="48" height="48" class="icon" alt="" />
	You should share it.
</h2>

<p>
	It doesn't matter if your update looks horrible, <strong>your update is a valued part of the creation process</strong> and deserves every right to be proudly showcased out there for the world to see. At WIPUP, we believe that <strong>this in-between is just as important and breathtakingly awesome as the finished product iself</strong> - it's the hidden spectacular adventure behind the creation. Anything from a brainwave of an idea to a tiny nudge of a pixel - we want you to use WIPUP to suit your workflow.
</p>

<p>
	You can start sharing this update with your friends by directing them to this URL: <input type="text" value="<?php echo url::base(); ?>updates/view/<?php echo $uid; ?>/" />
</p>

<?php if ($this->logged_in) { ?>
<p>
	Or link them straight to your profile: <input type="text" value="<?php echo url::base(); ?>profile/view/<?php echo $this->uid; ?>/" />
</p>
<?php } ?>
