<!-- File: /app/View/Posts/view.ctp -->

<h1><?php echo __("Add Permission"); ?></h1>

<div>

<?php

	echo $this->Form->create("Group");

	echo $this->Form->input("aco_id");
	echo $this->Form->input("aro_id");
	echo $this->Form->input("grant_id");

	echo $this->Form->end(__("Submit"));
?>

</div>
