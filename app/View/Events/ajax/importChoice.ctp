<div class="popover_choice">
	<legend><?php echo __('Choose the format that you would like to use for the import'); ?></legend>
	<div class="popover_choice_main" id ="popover_choice_main">
		<table style="width:100%;">
		<?php foreach ($imports as $k => $import): ?>
			<tr style="border-bottom:1px solid black;" class="templateChoiceButton">
				<td style="padding-left:10px; text-align:center;width:100%;" onClick="importChoiceSelect('<?php echo h($import['url']); ?>', '<?php echo h($k); ?>', '<?php echo $import['ajax'] ? h($import['target']) : "false"; ?>')"><?php echo h($import['text']); ?></td>
			</tr>
		<?php endforeach; ?>
		</table>
	</div>
	<div class="templateChoiceButton templateChoiceButtonLast" onClick="cancelPopoverForm();">Cancel</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		resizePopoverBody();
	});

	$(window).resize(function() {
		resizePopoverBody();
	});
</script>
