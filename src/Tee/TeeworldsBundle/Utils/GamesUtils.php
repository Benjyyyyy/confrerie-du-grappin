<?php
namespace Tee\TeeworldsBundle\Utils;

use Tee\TeeworldsBundle\Entity\Player;
use Tee\TeeworldsBundle\Entity\Statistics;
use Tee\TeeworldsBundle\Entity\Game;
use Tee\TeeworldsBundle\Entity\Weapon;

class GamesUtils
{
	public static $weapons = array( 0 => 'Marteau',
									1 => 'Pistolet',
									2 => 'Fusil à pompe',
									3 => 'Lance grenade',
									4 => 'LAZORRR',
									5 => 'Ninjaaaa' );
				 
	public static $titles = array("kill" => "Kill",
									"death" => "Death",
									"weaponSuicide" => "Suicide",
									"suicide" => "Suicide Piques",
									"teamKill" => "Teamkill",
									"ratio" => "ratio",
									"flagGrab" => "Drapeaux ramassés",
									"flagReturn" => "Drapeaux ramenés",
									"flagCapture" => "Drapeaux capturés",
									"weapon" => "Armes"
									);

	public static $headers = array("kill" => array("Nom", "Nombre de victimes"),
									"death" => array("Nom", "Nombre de mort"),
									"weaponSuicide" => array("Nom", "Nombre de suicide"),
									"suicide" => array("Nom", "Nombre de suicide"),
									"teamKill" => array("Nom", "Nombre de teamkill"),
									"ratio" => array("Nom", "Ratio Victime/Mort"),
									"flagGrab" => array("Nom", "Drapeaux ramassés"),
									"flagReturn" => array("Nom", "Drapeaux ramenés"),
									"flagCapture" => array("Nom", "Drapeaux capturés"),
									"weapon" => array("Arme", "Victimes"),
									);
	
	public static function getData( $statistics, $statName, $method )
    {
    	$data = array();
    	$data['name'] = $statName;
        $data['title'] = GamesUtils::$titles[ $statName];
    	$data['headers'] = GamesUtils::$headers[ $statName];
    	$data['values'] = array();
    	foreach( $statistics as $s )
    	{
    		$reflectionMethod = new \ReflectionMethod('Tee\TeeworldsBundle\Entity\Statistics', $method);
			$value = $reflectionMethod->invoke($s);
            if( $value == null )
                $value = 0;
            $data['values'][ $s->getPlayer()->getNickname() ] = $value;
    	}
        if( $statName == "death" || $statName == "suicide" || $statName == "weaponSuicide" || $statName == "teamKill" )
            asort( $data['values'] );
        else arsort( $data['values'] );
    	return $data;
    }

    public static function getDataWeapon( $weapons, $statName )
    {
    	$data = array();
        $data['name'] = $statName;
    	$data['title'] = GamesUtils::$titles[ $statName];
    	$data['headers'] = GamesUtils::$headers[ $statName];
    	$data['values'] = array();
    	foreach( $weapons as $w )
    	{
			$data['values'][ $w->getName() ] = $w->getFrag();
    	}
        arsort($data['values']);
    	return $data;
    }

    public static function getTotalData( $statistics, $statName )
    {
        $data = array();
        $data['name'] = $statName;
        $data['title'] = GamesUtils::$titles[ $statName];
        $data['headers'] = GamesUtils::$headers[ $statName];
        $data['values'] = array();
        foreach( $statistics as $s )
        {
            if( $statName == "ratio" )
            {
                $value = GamesUtils::calculateRatio( $s['kill'], $s['death'], $s['suicide'], $s['weaponSuicide']);
            }
            else {
                $value = $s[$statName];
                if( $value == null )
                    $value = 0;
            }
            
            $data['values'][ $s['player'] ] = $value;
        }
        if( $statName == "death" || $statName == "suicide" || $statName == "weaponSuicide" || $statName == "teamKill" )
            asort( $data['values'] );
        else arsort( $data['values'] );
        return $data;
    }
	
    public static function calculateRatio( $kill, $death, $suicide, $weaponSuicide )
    {
        $allDeath = intval($death) + intval($suicide) + intval($weaponSuicide);
        if( $allDeath == 0 )
            return $kill;
        else return round( $kill/$allDeath, 2);
    }

    public static function getTotalDataWeapon( $weapons, $statName )
    {
        $data = array();
        $data['name'] = $statName;
        $data['title'] = GamesUtils::$titles[ $statName];
        $data['headers'] = GamesUtils::$headers[ $statName];
        $data['values'] = array();
        foreach( $weapons as $w )
        {
            $data['values'][ $w['name'] ] = $w['kill'];
        }
        arsort($data['values']);
        return $data;
    }

    public static function parseFolder( $container )
    {
        $nbUpdate = 0;
        $dirname = realpath( './' ).'/upload/upload/';
        //$dirname = '/datas/vol2/w4a142484/var/www/florianlucas.fr/htdocs/dev/teeworld/upload/upload/';
        $dir = opendir($dirname); 
        while($file = readdir($dir)) {
            if($file != '.' && $file != '..' && !is_dir($dirname.$file))
            {
                //echo '<a href="'.$dirname.$file.'">'.$file.'</a>';
                $handle = fopen($dirname.$file, 'r');
                /*Si on a réussi à ouvrir le fichier*/
                if ($handle)
                {
                    $hash = md5_file( $dirname.$file );
                    $game = $container->getServiceGame()->getGameByHash( $hash );
                    if( $game == null )
                    {
                        $nbUpdate++;
                        $game = new Game();
                        $game->setDate( GamesUtils::extractDateTime( $handle ) );
                        $game->setHash( $hash );
                        $game = $container->getServiceGame()->save( $game );

                        GamesUtils::parseLog( $handle, $container, $game );
                    }
                    fclose($handle);
                }
            }
        }
        closedir($dir);
        return $nbUpdate;
    }

    public static function realPath()
    {
        return realpath( './' );
    }

	public static function extractDateTime( $handle )
    {
		$date = new \DateTime();
		while (!feof($handle))
        {
            $line = fgets($handle); // On lit la ligne courante
			$date->setTimestamp( hexdec( GamesUtils::extractString( $line, "[", "]") ) );
			break;
		}
		return $date;
	}
	
    public static function parseLog( $handle, $container, $game )
    {
        $kill = array();
        $death = array();
        $teamkill = array();
        $suicide = array();
        $suicidePique = array();
		$flagGrab = array();
		$flagReturn = array();
		$flagCapture = array();
        $weapon = array();
		

        /*Tant que l'on est pas à la fin du fichier*/
        while (!feof($handle))
        {
            $line = fgets($handle); // On lit la ligne courante
			
			if( GamesUtils::is( $line, '[KILL]' ) )
			{
				$killer = GamesUtils::extractKiller( $line );
				$victim = GamesUtils::extractVictim( $line );
				GamesUtils::incrementArray( $kill, $killer );
				GamesUtils::incrementArray( $death, $victim );
				GamesUtils::incrementArray( $weapon, GamesUtils::extractWeapon( $line ) );
			}
			else if( GamesUtils::is( $line, '[TEAMKILL]' ) )
			{
				$killer = GamesUtils::extractKiller( $line );
				GamesUtils::incrementArray( $teamkill, $killer );
            }
			else if( GamesUtils::is( $line, '[SUICIDE]' ) )
			{
				$killer = GamesUtils::extractKiller( $line );
				if( GamesUtils::isWeaponDeath( $line ) )
					GamesUtils::incrementArray( $suicide, $killer );
				else GamesUtils::incrementArray( $suicidePique, $killer );
            }
			else if( GamesUtils::is( $line, 'flag_return' ) )
			{
				$player = GamesUtils::extractPlayer( $line );
				GamesUtils::incrementArray( $flagReturn, $player );
            }
			else if( GamesUtils::is( $line, 'flag_grab' ) )
			{
				$player = GamesUtils::extractPlayer( $line );
				GamesUtils::incrementArray( $flagGrab, $player );
            }
			else if( GamesUtils::is( $line, 'flag_capture' ) )
			{
				$player = GamesUtils::extractPlayer( $line );
				GamesUtils::incrementArray( $flagCapture, $player );
            }
        }

        $players = $container->getServicePlayer()->findAll();
        $playersArray = array();
        foreach( $players as $p )
        {
            $playersArray[ $p->getNickname() ] = $p;
        }
        
        foreach ($kill as $player => $value)
        {
            if( array_key_exists($player, $playersArray) )
                $current = $playersArray[ $player ];
            else
            {
                $current = new Player();
                $current->setNickName( $player );
                $current = $container->getServicePlayer()->save($current);
            }
            $statistics = new Statistics();
            $statistics->setGame( $game );
            $statistics->setPlayer( $current );
            $statistics->setFrag( GamesUtils::getArrayValue($kill, $player) );
            $statistics->setDeath( GamesUtils::getArrayValue($death, $player) );
            $statistics->setSuicide( GamesUtils::getArrayValue($suicidePique, $player) );
            $statistics->setWeaponSuicide( GamesUtils::getArrayValue($suicide, $player) );
            $statistics->setTeamkill( GamesUtils::getArrayValue($teamkill, $player) );
			$statistics->setFlagCapture( GamesUtils::getArrayValue($flagCapture, $player) );
			$statistics->setFlagReturn( GamesUtils::getArrayValue($flagReturn, $player) );
			$statistics->setFlagGrab( GamesUtils::getArrayValue($flagGrab, $player) );
            $container->getServiceStatistics()->save($statistics);
        }
        
        foreach ($weapon as $w => $frag)
        {
            $weapon = new Weapon();
            $weapon->setGame( $game );
            $weapon->setName($w);
            $weapon->setFrag($frag);
            $container->getServiceWeapon()->save($weapon);
        }
    }

    public static function getArrayValue( $arr, $offset )
    {
        if( array_key_exists($offset, $arr) )
            return $arr[$offset];
        return null;
    }
	
	public static function is( $str, $needle )
	{
		return strpos($str, $needle) !== false;
	}

	public static function extractKiller( $str )
	{
		return GamesUtils::extractString( $str, "killer='", "'");
	}
	
	public static function extractPlayer( $str )
	{
		$player = GamesUtils::extractString( $str, "player='", "'");
		$player = GamesUtils::extractStartString( $player, ':' );
		return $player;
	}


	public static function isWeaponDeath( $str )
	{
		$weap = intval( GamesUtils::extractString( $str, "weapon=", " ") );
		return $weap >= 0 ;
	}


	public static function extractWeapon( $str )
	{
		$weap = intval( GamesUtils::extractString( $str, "weapon=", " ") );
		if( isset( GamesUtils::$weapons[ $weap ] ) )
			return GamesUtils::$weapons[ $weap ];
		return null;
	}

	public static function extractVictim( $str )
	{
		return GamesUtils::extractString( $str, "victim='", "'");
	}
	
	public static function extractStartString($str, $start)
	{
		if( !GamesUtils::is( $str, $start ) )
			return $str;
		return substr( $str, strpos($str, $start) + strlen( $start ) );
	}

	public static function extractString($str, $start, $end)
	{
		if( !GamesUtils::is( $str, $start ) || !GamesUtils::is( $str, $end ) )
			return null;
		$temp = substr( $str, strpos($str, $start) + strlen( $start ) );
		return substr( $temp, 0, strpos( $temp, $end ) );
	}
	
	public static function incrementArray( &$array, $key )
	{
		if (array_key_exists($key, $array))
			$array[$key] += 1;
		else $array[$key] = 1;
	}
}

?>
