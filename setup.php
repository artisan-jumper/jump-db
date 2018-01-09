<?php

/*
 * Copyright 2018 Artisan Jumper.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

class Colors {
    private $foreground_colors = array();
    private $background_colors = array();

    public function __construct() {
      // Set up shell colors
      $this->foreground_colors['black'] = '0;30';
      $this->foreground_colors['dark_gray'] = '1;30';
      $this->foreground_colors['blue'] = '0;34';
      $this->foreground_colors['light_blue'] = '1;34';
      $this->foreground_colors['green'] = '0;32';
      $this->foreground_colors['light_green'] = '1;32';
      $this->foreground_colors['cyan'] = '0;36';
      $this->foreground_colors['light_cyan'] = '1;36';
      $this->foreground_colors['red'] = '0;31';
      $this->foreground_colors['light_red'] = '1;31';
      $this->foreground_colors['purple'] = '0;35';
      $this->foreground_colors['light_purple'] = '1;35';
      $this->foreground_colors['brown'] = '0;33';
      $this->foreground_colors['yellow'] = '1;33';
      $this->foreground_colors['light_gray'] = '0;37';
      $this->foreground_colors['white'] = '1;37';

      $this->background_colors['black'] = '40';
      $this->background_colors['red'] = '41';
      $this->background_colors['green'] = '42';
      $this->background_colors['yellow'] = '43';
      $this->background_colors['blue'] = '44';
      $this->background_colors['magenta'] = '45';
      $this->background_colors['cyan'] = '46';
      $this->background_colors['light_gray'] = '47';
    }

    // Returns colored string
    public function getColoredString($string, $foreground_color = null, $background_color = null) {
      $colored_string = "";

      // Check if given foreground color found
      if (isset($this->foreground_colors[$foreground_color])) {
        $colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
      }
      // Check if given background color found
      if (isset($this->background_colors[$background_color])) {
        $colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
      }

      // Add string and end coloring
      $colored_string .=  $string . "\033[0m";

      return $colored_string;
    }

    // Returns all foreground color names
    public function getForegroundColors() {
      return array_keys($this->foreground_colors);
    }

    // Returns all background color names
    public function getBackgroundColors() {
      return array_keys($this->background_colors);
    }
  }

$run = new Colors();
$url = shell_exec('heroku config:get CLEARDB_DATABASE_URL');
$url_array = parse_url($url);

if (preg_match("/\b(?:(?:mysql):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url)) {
  $server = $url_array["host"];
  $username = $url_array["user"];
  $password = $url_array["pass"];
  $db = substr($url_array["path"], 1);

  shell_exec("heroku config:set DB_HOST={$server}");
  shell_exec("heroku config:set DB_DATABASE={$db}");
  shell_exec("heroku config:set DB_USERNAME={$username}");
  shell_exec("heroku config:set DB_PASSWORD={$password}");

  echo " ▸    " . $run->getColoredString( "Thanks for using jump-db (", "purple", "white" ). $run->getColoredString( "https://github.com/3runoDesign/jump-db", "green", "white" ) . $run->getColoredString( ").", "purple", "white" ) . "\n";
} else {
  echo " ▸    Sorry! We cannot find a valid ". $run->getColoredString( "CLEARDB_DATABASE_URL", "red", "white" ) ." variable.";
  echo "\n ▸    Please run " . $run->getColoredString( "heroku addons:create cleardb:ignite", "purple", "white" ) . ".\n\n\n\n";
}
