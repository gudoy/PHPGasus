<?php

class cssGroupsIterator extends RecursiveIteratorIterator
{
	public function valid()
	{
		// Do the group name match the pattern {$filename}.{$extension}
		//parent::valid();
		return !preg_match('/^.*\.(css|scss|sass|less)$/', $this->current());
		//return is_array($this->current) || ( is_string($this->current) && !preg_match('/^.*\.(css|scss|sass|less)$/', $this->current() ));
	}
}

?>