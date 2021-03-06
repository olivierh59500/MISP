<div class="feed index">
	<h2><?php echo __('Feeds');?></h2>
	<div class="pagination">
        <ul>
        <?php
        $this->Paginator->options(array(
            'update' => '.span12',
            'evalScripts' => true,
            'before' => '$(".progress").show()',
            'complete' => '$(".progress").hide()',
        ));

            echo $this->Paginator->prev('&laquo; ' . __('previous'), array('tag' => 'li', 'escape' => false), null, array('tag' => 'li', 'class' => 'prev disabled', 'escape' => false, 'disabledTag' => 'span'));
            echo $this->Paginator->numbers(array('modulus' => 20, 'separator' => '', 'tag' => 'li', 'currentClass' => 'active', 'currentTag' => 'span'));
            echo $this->Paginator->next(__('next') . ' &raquo;', array('tag' => 'li', 'escape' => false), null, array('tag' => 'li', 'class' => 'next disabled', 'escape' => false, 'disabledTag' => 'span'));
        ?>
        </ul>
    </div>
		<div class="tabMenuFixedContainer" style="display:inline-block;">
    		<span class="tabMenuFixed tabMenuFixedCenter tabMenuSides useCursorPointer <?php echo $scope == 'default' ? 'tabMenuActive' : ''; ?>" onclick="window.location='/feeds/index/scope:default'">Default feeds</span>
    		<span class="tabMenuFixed tabMenuFixedCenter tabMenuSides useCursorPointer <?php echo $scope == 'custom' ? 'tabMenuActive' : ''; ?> " onclick="window.location='/feeds/index/scope:custom'">Custom Feeds</span>
    		<span class="tabMenuFixed tabMenuFixedCenter tabMenuSides useCursorPointer <?php echo $scope == 'all' ? 'tabMenuActive' : ''; ?> " onclick="window.location='/feeds/index/scope:all'">All Feeds</span>
    </div>
	<table class="table table-striped table-hover table-condensed">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('source_format', 'Feed Format');?></th>
			<th><?php echo $this->Paginator->sort('provider');?></th>
			<th><?php echo $this->Paginator->sort('url');?></th>
			<th>Target</th>
			<th>Publish</th>
			<th>Delta Merge</th>
			<th>Override IDS</th>
			<th><?php echo $this->Paginator->sort('distribution');?></th>
			<th><?php echo $this->Paginator->sort('tag');?></th>
			<th><?php echo $this->Paginator->sort('enabled');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr><?php
foreach ($feeds as $item):
	$rules = array();
	$rules = json_decode($item['Feed']['rules'], true);
	$fieldOptions = array('tags', 'orgs');
	$typeOptions = array('OR' => array('colour' => 'green', 'text' => 'allowed'), 'NOT' => array('colour' => 'red', 'text' => 'blocked'));
	$ruleDescription = '';
	foreach ($fieldOptions as $fieldOption) {
		foreach ($typeOptions as $typeOption => $typeData) {
			if (isset($rules[$fieldOption][$typeOption]) && !empty($rules[$fieldOption][$typeOption])) {
				$ruleDescription .= '<span class=\'bold\'>' .
				ucfirst($fieldOption) . ' ' .
				$typeData['text'] . '</span>: <span class=\'' .
				$typeData['colour'] . '\'>';
				foreach ($rules[$fieldOption][$typeOption] as $k => $temp) {
					if ($k != 0) $ruleDescription .= ', ';
					$ruleDescription .= h($temp);
				}
				$ruleDescription .= '</span><br />';
			}
		}
	}
?>
	<tr>
		<td class="short"><?php echo h($item['Feed']['id']); ?>&nbsp;</td>
		<td>
			<?php
				echo h($item['Feed']['name']);
				if ($item['Feed']['default']):
				?>
					<img src="<?php echo $baseurl;?>/img/orgs/MISP.png" width="24" height="24" style="padding-bottom:3px;" />
				<?php
					endif;
			?>
		</td>
		<td><?php echo $feed_types[$item['Feed']['source_format']]['name']; ?>&nbsp;</td>
		<td><?php echo h($item['Feed']['provider']); ?>&nbsp;</td>
		<td><?php echo h($item['Feed']['url']); ?>&nbsp;</td>
		<td class="shortish">
		<?php
			if (in_array($item['Feed']['source_format'], array('freetext', 'csv'))):
				if ($item['Feed']['fixed_event']):
					if (isset($item['Feed']['event_error'])):
				?>
					<span class="red bold">Error: Invalid event!</span>
				<?php
					else:
						if ($item['Feed']['event_id']):
						?>
							<a href="<?php echo $baseurl;?>/events/view/<?php echo h($item['Feed']['event_id']); ?>">Fixed event <?php echo h($item['Feed']['event_id']); ?></a>
						<?php
						else:
							echo 'New fixed event';
						endif;
					endif;
				endif;
			else:
				echo ' ';
			endif;
		 ?>
		</td>
		<?php
			if ($item['Feed']['source_format'] != 'misp'):
		?>
				<td><span class="<?php echo ($item['Feed']['publish'] ? 'icon-ok' : 'icon-remove'); ?>"></span></td>
				<td><span class="<?php echo ($item['Feed']['delta_merge'] ? 'icon-ok' : 'icon-remove'); ?>"></span></td>
				<td><span class="<?php echo ($item['Feed']['override_ids'] ? 'icon-ok' : 'icon-remove'); ?>"></span></td>
		<?php
			else:
		?>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
		<?php
			endif;
		?>
		<td <?php if ($item['Feed']['distribution'] == 0) echo 'class="red"'; ?>>
		<?php
			echo $item['Feed']['distribution'] == 4 ? '<a href="' . $baseurl . '/sharing_groups/view/' . h($item['SharingGroup']['id']) . '">' . h($item['SharingGroup']['name']) . '</a>' : $distributionLevels[$item['Feed']['distribution']] ;
		?>
		</td>
		<td>
		<?php if ($item['Feed']['tag_id']): ?>
			<a href="<?php echo $baseurl;?>/events/index/searchtag:<?php echo h($item['Tag']['id']); ?>" class=tag style="background-color:<?php echo h($item['Tag']['colour']);?>;color:<?php echo $this->TextColour->getTextColour($item['Tag']['colour']);?>"><?php echo h($item['Tag']['name']); ?></a>
		<?php else: ?>
			&nbsp;
		<?php endif;?>
		</td>
		<td class="short"><span class="<?php echo ($item['Feed']['enabled'] ? 'icon-ok' : 'icon-remove'); ?>"></span><span class="short <?php if (!$item['Feed']['enabled'] || empty($ruleDescription)) echo "hidden"; ?>" data-toggle="popover" title="Filter rules" data-content="<?php echo $ruleDescription; ?>"> (Rules)</span>
		<td class="short action-links">
			<?php
				if (!isset($item['Feed']['event_error'])) {
					echo $this->Html->link('', array('action' => 'previewIndex', $item['Feed']['id']), array('class' => 'icon-search', 'title' => 'Explore the events remotely'));
					if ($item['Feed']['enabled']) echo $this->Html->link('', array('action' => 'fetchFromFeed', $item['Feed']['id']), array('class' => 'icon-download', 'title' => 'Fetch all events'));
				}
			?>
			<a href="<?php echo $baseurl;?>/feeds/edit/<?php echo h($item['Feed']['id']); ?>"><span class="icon-edit" title="edit">&nbsp;</span></a>
			<?php echo $this->Form->postLink('', array('action' => 'delete', h($item['Feed']['id'])), array('class' => 'icon-trash', 'title' => 'Delete'), __('Are you sure you want to permanently remove the feed (%s)?', h($item['Feed']['name']))); ?>
			<a href="<?php echo $baseurl;?>/feeds/view/<?php echo h($item['Feed']['id']); ?>.json" title="Download feed metadata as JSON" download><span class="icon-download"></span></a>
		</td>
	</tr><?php
endforeach; ?>
	</table>
	<p>
    <?php
    echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
    ));
    ?>
    </p>
    <div class="pagination">
        <ul>
        <?php
            echo $this->Paginator->prev('&laquo; ' . __('previous'), array('tag' => 'li', 'escape' => false), null, array('tag' => 'li', 'class' => 'prev disabled', 'escape' => false, 'disabledTag' => 'span'));
            echo $this->Paginator->numbers(array('modulus' => 20, 'separator' => '', 'tag' => 'li', 'currentClass' => 'active', 'currentTag' => 'span'));
            echo $this->Paginator->next(__('next') . ' &raquo;', array('tag' => 'li', 'escape' => false), null, array('tag' => 'li', 'class' => 'next disabled', 'escape' => false, 'disabledTag' => 'span'));
        ?>
        </ul>
    </div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		popoverStartup();
	});
</script>
<?php
	echo $this->element('side_menu', array('menuList' => 'feeds', 'menuItem' => 'index'));
?>
