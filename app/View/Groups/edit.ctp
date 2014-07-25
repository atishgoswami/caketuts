<!-- File: /app/View/Groups/add.ctp -->

<h1>Edit Group</h1>
<?php
	echo $this->Form->create('Group');
		echo $this->Form->input('alias');
		echo $this->Form->input('parent_id');
	echo $this->Form->end('Save Group');
?>