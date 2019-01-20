<?php

namespace Storm;

class Filesystem extends \Symfony\Component\Filesystem\Filesystem {
    function getHome() {
			if (isset($_SERVER['HOME'])) {
				$path = $_SERVER['HOME'];
				$finalChar = substr($path, -1);
				if ($finalChar != "/") {
					$path .= "/";
				}
				return $path;
			} elseif (isset($_SERVER['USERPROFILE'])) {
				$path = $_SERVER['USERPROFILE'];
				$finalChar = substr($path, -1);
				if ($finalChar != '\\') {
					$path = $path . '\\';
				} 
				return $path;
			} elseif (isset($_SERVER['HOMEPATH']) &&
			         isset($_SERVER['HOMEDRIVE'])) {
				$path = $_SERVER['HOMEDRIVE'].$_SERVER['HOMEPATH'];
				$finalChar = substr($path, -1);
				if ($finalChar != '\\') {
					$path = $path . '\\';
				} 
				return $path;
			} else {
				return "./";
			}
		}
}