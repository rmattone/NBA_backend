<?php

namespace App\Enums;

class NBATeamsEnum
{
    protected static $teamMap = [
        1 => 1610612737, // Atlanta Hawks
        2 => 1610612738, // Boston Celtics
        3 => 1610612751, // Brooklyn Nets
        4 => 1610612766, // Charlotte Hornets
        5 => 1610612741, // Chicago Bulls
        6 => 1610612739, // Cleveland Cavaliers
        7 => 1610612742, // Dallas Mavericks
        8 => 1610612743, // Denver Nuggets
        9 => 1610612765, // Detroit Pistons
        10 => 1610612744, // Golden State Warriors
        11 => 1610612745, // Houston Rockets
        12 => 1610612754, // Indiana Pacers
        13 => 1610612746, // Los Angeles Clippers
        14 => 1610612747, // Los Angeles Lakers
        15 => 1610612763, // Memphis Grizzlies
        16 => 1610612748, // Miami Heat
        17 => 1610612749, // Milwaukee Bucks
        18 => 1610612750, // Minnesota Timberwolves
        19 => 1610612740, // New Orleans Pelicans
        20 => 1610612752, // New York Knicks
        21 => 1610612760, // Oklahoma City Thunder
        22 => 1610612753, // Orlando Magic
        23 => 1610612755, // Philadelphia 76ers
        24 => 1610612756, // Phoenix Suns
        25 => 1610612757, // Portland Trail Blazers
        26 => 1610612758, // Sacramento Kings
        27 => 1610612759, // San Antonio Spurs
        28 => 1610612761, // Toronto Raptors
        29 => 1610612762, // Utah Jazz
        30 => 1610612764, // Washington Wizards
    ];

    public static function getNBATeamId($teamId)
    {
        return static::$teamMap[$teamId] ?? null;
    }
}
