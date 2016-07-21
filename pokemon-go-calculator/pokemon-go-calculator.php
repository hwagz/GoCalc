<?php
/**
* Plugin Name: Pokemon Go Calculator
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
    wp_register_script( '_JS', plugins_url( '/js/underscore-min.js', __FILE__ ) );
    wp_register_script( 'pokeDataJS', plugins_url( '/js/pokeData.js', __FILE__ ) );
		wp_register_script( 'goCalcJS', plugins_url( '/js/goCalc.js', __FILE__ ) );
		wp_enqueue_script('angularJS');
		wp_enqueue_script('jQJS');
    wp_enqueue_script('_JS');
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
  <div class="appBody" ng-app="goCalc" ng-controller="goCalcCtrl" ng-cloak  tabindex="0">
    <input type="text" ng-model="searchTerm" ng-keydown="keyHandler($event)"><button type="button" ng-click="search(searchTerm)">Search</button>
    <button type="button" ng-click="reset()">Reset</button>
    <table>
      <tr>
        <td ng-click="sort(\'number\')">#</td>
        <td ng-click="sort(\'name\')">Name</td>
        <td ng-click="sort(\'type1\')">Type</td>
        <td ng-click="sort(\'maxCP\')">Max CP</td>
        <td ng-click="sort(\'maxHP\')">Max HP</td>
        <td ng-click="sort(\'baseStamina\')">Stam</td>
        <td ng-click="sort(\'baseAttack\')">Atk</td>
        <td ng-click="sort(\'baseDefense\')">Def</td>
        <td ng-click="sort(\'candyToEvolve\')">CTE</td>
        <td>Current CP?</td>
        <td>Evolved CP Range</td>
      </tr>
      <tr ng-repeat="poke in viewing track by $index">
        <td>{{poke.number}}</td>
        <td>{{poke.name | capitalize}}</td>
        <td>{{typing(poke.type1,poke.type2)}}</td>
        <td>{{poke.maxCP}}</td>
        <td>{{poke.maxHP}}</td>
        <td>{{poke.baseStamina}}</td>
        <td>{{poke.baseAttack}}</td>
        <td>{{poke.baseDefense}}</td>
        <td>{{poke.candyToEvolve || ""}}</td>
        <td><input type="text" ng-model="calcData[$index]" ng-show="poke.candyToEvolve"></td>
        <td>{{cpEst(calcData[$index],poke) || ""}}</td>
      </tr>
    </table>
  </div>
  ';
  return $gocalc_output;
}

// Actually calls the function to allow styles/scripts
add_action('wp_head', 'enqueue_style');
// Calls functions to build the module. Page must have "[osu-my-sites]" included
add_shortcode('pokemon-go-calculator', 'gocalc_handler');
?>
