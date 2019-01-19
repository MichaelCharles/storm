<?php

// Based on BinaryTide's terminal function.
// https://www.binarytides.com/execute-shell-commands-php/

namespace Storm;

class Terminal {
	
	function __construct($context = false) {
		$this->context = $context;
	}
	
	function run($command)
		{
		if ($this->context) {
			$command = "(cd ".$this->context."; ".$command.")";
		}
		
		// system

		if (function_exists('system'))
			{
			ob_start();
			system($command, $return_var);
			$output = ob_get_contents();
			ob_end_clean();
			}

		// passthru

		  else
		if (function_exists('passthru'))
			{
			ob_start();
			passthru($command, $return_var);
			$output = ob_get_contents();
			ob_end_clean();
			}

		// exec

		  else
		if (function_exists('exec'))
			{
			exec($command, $output, $return_var);
			$output = implode("n", $output);
			}

		// shell_exec

		  else
		if (function_exists('shell_exec'))
			{
			$output = shell_exec($command);
			}
		  else
			{
			$output = 'Command execution not possible on this system';
			$return_var = 1;
			}

		return array(
			'output' => $output,
			'status' => $return_var
		);
		}
	}
