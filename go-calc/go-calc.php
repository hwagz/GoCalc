<?php
/**
* Plugin Name: Go Calc
* Description: Calculates information for Pokemon Go
* Author: Slowbro202
* Version: 1.0
* License: GPLv3
*/

// Blocks access to file other than through WP's normal processes
defined( 'ABSPATH' ) or die( 'Unauthorized access.' );

// Allows WordPress to enable and use necessary styles/scripts
function enqueue_style(){
		wp_register_style( 'styleCSS', plugins_url( '/css/goStyle.css', __FILE__ ) );
		wp_enqueue_style( 'styleCSS' );

		wp_register_script( 'angularJS', plugins_url( '/js/angular.min.js', __FILE__ ) );
		wp_register_script( 'jQJS', plugins_url( '/js/jquery.min.js', __FILE__ ) );
    wp_register_script( 'pokeDataJS', plugins_url( '/js/pokeData.js', __FILE__ ) );
		wp_register_script( 'goCalcJS', plugins_url( '/js/goCalc.js', __FILE__ ) );
		wp_enqueue_script('angularJS');
		wp_enqueue_script('jQJS');
    wp_enqueue_script('pokeDataJS');
		wp_enqueue_script('goCalcJS');
	}

function gocalc_handler() {
  // Runs function that actually does the work of the plugin
  $gocalc_output = gocalc_function();
  // Sends back text to replace shortcode in post
  return $gocalc_output;
}

function gocalc_function() {
  $gocalc_output = '
  <div class="appBody" ng-app="goCalc" ng-controller="goCalcCtrl" ng-cloak ng-keydown="keyHandler($event)" tabindex="0">
    <input type="text" ng-model="searchTerm"><button type="button" ng-click="search(searchTerm)">Search</button>
    <table>
      <tr>
        <td>Name</td>
        <td>Type</td>
        <td>Type</td>
        <td>Base Stamina</td>
        <td>Base Attack</td>
        <td>Base Defense</td>
        <td>Candy to Evolve</td>
        <td>Current CP?</td>
        <td>Evolved CP Range</td>
      </tr>
      <tr ng-repeat="poke in viewing track by $index">
        <td>{{poke.name | capitalize}}</td>
        <td>{{poke.type1 | capitalize}}</td>
        <td>{{poke.type2 || "" | capitalize}}</td>
        <td>{{poke.stats.baseStamina}}</td>
        <td>{{poke.stats.baseAttack}}</td>
        <td>{{poke.stats.baseDefense}}</td>
        <td>{{poke.candyToEvolve || ""}}</td>
        <td><input type="text" ng-model="calcData[$index]" ng-show="poke.candyToEvolve"></td>
        <td>{{cpEst(calcData[$index],poke.evoMultiplier) || ""}}</td>
      </tr>
    </table>
    <button type="button" ng-click="reset()">Reset</button>

  </div>
  ';
  return $gocalc_output;
}

// Actually calls the function to allow styles/scripts
add_action('wp_head', 'enqueue_style');
// Calls functions to build the module. Page must have "[osu-my-sites]" included
add_shortcode('go-calc', 'gocalc_handler');
?>
