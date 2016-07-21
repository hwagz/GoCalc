"use strict";

// WordPress stole the jQ $, so we'll invent our own currency! jDollars. Jollars.
var $j=jQuery.noConflict();


angular
  .module('goCalc',[])
  .controller('goCalcCtrl', ['$scope','$filter','$http','$compile',function($scope, $filter, $http, $compile) {
    $scope.allData = allData;
    $scope.viewing = $scope.allData;
    $scope.calcData = [];

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
              if($scope.allData[j].familyID===family){
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
    // Estimates CP
    $scope.cpEst = function(cp, multiplier){
      var range = 0.2;
      var min = multiplier-range;
      var max = multiplier+range;
      var range = " "
      if (max*cp) {
        range = Math.floor(min*cp)+" "+Math.floor(max*cp);
      }
      return range;

    };
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
