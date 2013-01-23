<?php

class Util_OptionsParser {
	public static function getOptions() {
		global $argv;
	
	    $options = array(
	        'jobs' => array(),
	        'verbose' => false,
	        'dry-run' => false,
	        'patch' => null,
	        'pretty' => false,
	        'staged-only' => false,
	        'poll_for_completion' => false,
	        'callback' => null
	        );

	    // Using the evil @ operator here because Console_Getopt
	    // is still PHP4 and spews a bunch of deprecation warnings:
	    $ret = @Console_Getopt::getopt($argv, 'h?vnp:g:cPs');

	    if ($ret instanceOf PEAR_Error) {
	        error_log($ret->getMessage());
	        self::showHelp();
	    }

	    list($opt, $args) = $ret;

	    foreach ($opt as $tuple) {
	        list($k, $v) = $tuple;

	        switch($k) {
	            case 'h':
	            case '?':
	                self::showHelp();
	                break;

	            case 'P':
	                $options['poll_for_completion'] = true;
	                $options['pretty'] = true;
	                break;

	            case 'v':
	                $options['verbose'] = true;
	                break;

	            case 'n':
	                $options['dry-run'] = true;
	                break;

	            case 'p':
	                $options['patch'] = $v;
	                break;

	            case 'c':
	                $options['poll_for_completion'] = true;
	                break;

	            case 's':
	                $options['staged-only'] = true;
	                break;

	            case 'g':
	                $options['callback'] = $v;
	                break;
	        }
	    }

	    if (count($args)) {
	        $options['jobs'] = $args;
	    }

	    return $options;
	}

	/**
	 * Display the help menu.
	 */
	public static function showHelp() {
	    print <<<eof
USAGE: try [options] suite [tests ...]

OPTIONS:
    -h          show help
    -n          create diff, but do not send to Hudson
    -v          verbose (show shell commands as they're run)
    -p path     don't generate diffs; use custom patch file instead
    -c          poll for job completion and print results
    -P          print subtasks progressively as they complete (implies c)
    -s          use staged changes only to generate the diff
eof
		;
	    print "\n\n";
	    exit(0);
	}
}