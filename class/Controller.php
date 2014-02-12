<?php

class Controller {
	
	protected function dirToArray($dir) {
	    $contents = array();
	    # Foreach node in $dir
	    foreach (scandir($dir) as $node) {
	        # Skip link to current and parent folder
	        if ($node == '.')  continue;
	        if ($node == '..') continue;
	        # Check if it's a node or a folder
	        if (is_dir($dir . DIRECTORY_SEPARATOR . $node)) {
	            # Add directory recursively, be sure to pass a valid path
	            # to the function, not just the folder's name
	            $contents[$node] = $this->dirToArray($dir . DIRECTORY_SEPARATOR . $node);
	        } else {
	            # Add node, the keys will be updated automatically
	            $contents[] = $node;
	        }
	    }
	    # done
	    return $contents;
	}
	
}