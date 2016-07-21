"use strict";

// WordPress stole the jQ $, so we'll invent our own currency! jDollars. Jollars.
var $j=jQuery.noConflict();


angular
  .module('goCalc',[])
  .controller('goCalcCtrl', ['$scope','$filter','$http','$compile',function($scope, $filter, $http, $compile) {
    $scope.allData = allData;
    $scope.viewing = $scope.allData;
    $scope.calcData = [];
    var sorted = false;
    // Sorts by column
    $scope.sort = function(field){
      $scope.viewing = _.sortBy($scope.viewing,field);
      if(sorted){
        $scope.viewing.reverse();
      }
      sorted=!sorted;
    };
    // Searches for a specific pokemon
    $scope.search = function(name){
      $scope.calcData = [];
      if(name){
        name = name.toLowerCase();
        var results = [];
        var found = false;
        for(var i=0,len=$scope.allData.length;i<len && !found;i++){
          if($scope.allData[i].name.includes(name)){
            var family = $scope.allData[i].familyID;
            for(var j=0;j<len;j++){
              if($scope.allData[j].familyID===family && results.indexOf($scope.allData[j])<0){
                results.push($scope.allData[j]);
              }
            }
          }
        }
        $scope.viewing = results;
      }
    };
    // Resets, clearing the search term
    $scope.reset = function(){
      $scope.viewing = $scope.allData;
      $scope.searchTerm = "";
      $scope.calcData = [];
    }
    $scope.findEvoCPMax = function(num){
      for(var i=0,len=$scope.allData.length;i<len;i++){
        if($scope.allData[i].number==(num+1)){
          return $scope.allData[i].maxCP;
        }
      }
    }
    // Estimates CP
    $scope.cpEst = function(cp, poke){
      var range = 0.15;
      // Eevee handler
      if(cp && poke.evoMultiplier==="eevee"){
        var cpMax = $scope.allData[133].maxCP;
        var max = Math.floor(cp*(2.7+range));
        range = Math.floor(cp*(2-range))+" ";
        range += max<=cpMax ? max : cpMax;
      }
      else {
        var min = poke.evoMultiplier-range;
        var max = poke.evoMultiplier+range;
        var range = " ";
        if(cp){
          range = "CP out of range";
          if(cp>=10 && cp<=poke.maxCP){
            if (max*cp) {
              range = Math.floor(min*cp)+" ";
              var cpMax = Math.floor(max*cp);
              var evoCPMax = $scope.findEvoCPMax(poke.number);
              range += cpMax<= evoCPMax ? cpMax : evoCPMax
            }
          }
        }
      }
      return range;
    };
    // Outputs type string
    $scope.typing = function(t1,t2){
      var type = $filter('capitalize')(t1);
      if(t2){
        type+="/"+$filter('capitalize')(t2);
      }
      return type;
    }
    // Handles keyboard input
    $scope.keyHandler = function(e){
      e = e.keyCode;
      //console.log(e);
      if(e==13){
        $scope.search($scope.searchTerm);
      }
    };

  }])
  .filter('capitalize', function() {
    return function(input) {
      return (!!input) ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() : '';
    }
});
