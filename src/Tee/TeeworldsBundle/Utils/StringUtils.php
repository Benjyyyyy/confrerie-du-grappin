<?php

namespace Tee\TeeworldsBundle\Utils;

class StringUtils
{
	public static function isEmpty( $str ) {
		if( $str == null)
			return true;
		if( $str == "")
			return true;
		if( strlen( $str ) == 0 )
			return true;
		return false;
	}
}
