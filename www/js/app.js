angular.module("gsja_volunteer_app", ["ngCordova","ionic","ionMdInput","ionic-material","ion-datetime-picker","ionic.rating","utf8-base64","angular-md5","chart.js","pascalprecht.translate","tmh.dynamicLocale","gsja_volunteer_app.controllers", "gsja_volunteer_app.services"])
	.run(function($ionicPlatform,$window,$interval,$timeout,$ionicHistory,$ionicPopup,$state,$rootScope){

		$rootScope.appName = "GSJA Volunteer App" ;
		$rootScope.appLogo = "data/images/header/logo.png" ;
		$rootScope.appVersion = "1.0" ;
		$rootScope.headerShrink = false ;

		$rootScope.liveStatus = "pause" ;
		$ionicPlatform.ready(function(){
			$rootScope.liveStatus = "run" ;
		});
		$ionicPlatform.on("pause",function(){
			$rootScope.liveStatus = "pause" ;
		});
		$ionicPlatform.on("resume",function(){
			$rootScope.liveStatus = "run" ;
		});


		$rootScope.hide_menu_dashboard = false ;
		$rootScope.hide_menu_dashboard = false ;
		$rootScope.hide_menu_ptt = false ;


		$ionicPlatform.ready(function() {

			localforage.config({
				driver : [localforage.WEBSQL,localforage.INDEXEDDB,localforage.LOCALSTORAGE],
				name : "gsja_volunteer_app",
				storeName : "gsja_volunteer_app",
				description : "The offline datastore for GSJA Volunteer App app"
			});

			if(window.cordova){
				$rootScope.exist_cordova = true ;
			}else{
				$rootScope.exist_cordova = false ;
			}
			//required: cordova plugin add ionic-plugin-keyboard --save
			if(window.cordova && window.cordova.plugins.Keyboard) {
				cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
				cordova.plugins.Keyboard.disableScroll(true);
			}

			//required: cordova plugin add cordova-plugin-statusbar --save
			if(window.StatusBar) {
				StatusBar.styleDefault();
			}


		});
		$ionicPlatform.registerBackButtonAction(function (e){
			if($ionicHistory.backView()){
				$ionicHistory.goBack();
			}else{
				$state.go("gsja_volunteer_app.dashboard");
			}
			e.preventDefault();
			return false;
		},101);
	})


	.filter("to_trusted", ["$sce", function($sce){
		return function(text) {
			return $sce.trustAsHtml(text);
		};
	}])

	.filter("trustUrl", function($sce) {
		return function(url) {
			return $sce.trustAsResourceUrl(url);
		};
	})

	.filter("trustJs", ["$sce", function($sce){
		return function(text) {
			return $sce.trustAsJs(text);
		};
	}])

	.filter("strExplode", function() {
		return function($string,$delimiter) {
			if(!$string.length ) return;
			var $_delimiter = $delimiter || "|";
			return $string.split($_delimiter);
		};
	})

	.filter("strDate", function(){
		return function (input) {
			return new Date(input);
		}
	})
	.filter("phpTime", function(){
		return function (input) {
			var timeStamp = parseInt(input) * 1000;
			return timeStamp ;
		}
	})
	.filter("strHTML", ["$sce", function($sce){
		return function(text) {
			return $sce.trustAsHtml(text);
		};
	}])
	.filter("strEscape",function(){
		return window.encodeURIComponent;
	})
	.filter("strUnscape", ["$sce", function($sce) {
		var div = document.createElement("div");
		return function(text) {
			div.innerHTML = text;
			return $sce.trustAsHtml(div.textContent);
		};
	}])

	.filter("stripTags", ["$sce", function($sce){
		return function(text) {
			return text.replace(/(<([^>]+)>)/ig,"");
		};
	}])

	.filter("chartData", function(){
		return function (obj) {
			var new_items = [];
			angular.forEach(obj, function(child) {
				var new_item = [];
				var indeks = 0;
				angular.forEach(child, function(v){
						if ((indeks !== 0) && (indeks !== 1)){
							new_item.push(v);
						}
						indeks++;
					});
					new_items.push(new_item);
				});
			return new_items;
		}
	})

	.filter("chartLabels", function(){
		return function (obj){
			var new_item = [];
			angular.forEach(obj, function(child) {
			var indeks = 0;
			new_item = [];
			angular.forEach(child, function(v,l) {
				if ((indeks !== 0) && (indeks !== 1)) {
					new_item.push(l);
				}
				indeks++;
			});
			});
			return new_item;
		}
	})
	.filter("chartSeries", function(){
		return function (obj) {
			var new_items = [];
			angular.forEach(obj, function(child) {
				var new_item = [];
				var indeks = 0;
				angular.forEach(child, function(v){
						if (indeks === 1){
							new_item.push(v);
						}
						indeks++;
					});
					new_items.push(new_item);
				});
			return new_items;
		}
	})



.config(["$translateProvider", function ($translateProvider){
	$translateProvider.preferredLanguage("en-us");
	$translateProvider.useStaticFilesLoader({
		prefix: "translations/",
		suffix: ".json"
	});
	$translateProvider.useSanitizeValueStrategy("escapeParameters");
}])


.config(function(tmhDynamicLocaleProvider){
	tmhDynamicLocaleProvider.localeLocationPattern("lib/ionic/js/i18n/angular-locale_{{locale}}.js");
	tmhDynamicLocaleProvider.defaultLocale("en-us");
})



.config(function($stateProvider,$urlRouterProvider,$sceDelegateProvider,$ionicConfigProvider,$httpProvider){
	/** tabs position **/
	$ionicConfigProvider.tabs.position("bottom"); 
	try{
	// Domain Whitelist
		$sceDelegateProvider.resourceUrlWhitelist([
			"self",
			new RegExp('^(http[s]?):\/\/(w{3}.)?youtube\.com/.+$'),
			new RegExp('^(http[s]?):\/\/(w{3}.)?w3schools\.com/.+$'),
		]);
	}catch(err){
		console.log("%cerror: %cdomain whitelist","color:blue;font-size:16px;","color:red;font-size:16px;");
	}
	$stateProvider
	.state("gsja_volunteer_app",{
		url: "/gsja_volunteer_app",
		abstract: true,
		templateUrl: "templates/gsja_volunteer_app-tabs.html",
	})

	.state("gsja_volunteer_app.about_us", {
		url: "/about_us",
		views: {
			"gsja_volunteer_app-about_us" : {
						templateUrl:"templates/gsja_volunteer_app-about_us.html",
						controller: "about_usCtrl"
					},
			"fabButtonUp" : {
						template: '',
					},
		}
	})

	.state("gsja_volunteer_app.dashboard", {
		url: "/dashboard",
		cache:false,
		views: {
			"gsja_volunteer_app-dashboard" : {
						templateUrl:"templates/gsja_volunteer_app-dashboard.html",
						controller: "dashboardCtrl"
					},
			"fabButtonUp" : {
						template: '',
					},
		}
	})

	.state("gsja_volunteer_app.faqs", {
		url: "/faqs",
		views: {
			"gsja_volunteer_app-faqs" : {
						templateUrl:"templates/gsja_volunteer_app-faqs.html",
						controller: "faqsCtrl"
					},
			"fabButtonUp" : {
						template: '',
					},
		}
	})

	.state("gsja_volunteer_app.form_add_new_inventory", {
		url: "/form_add_new_inventory",
		views: {
			"gsja_volunteer_app-form_add_new_inventory" : {
						templateUrl:"templates/gsja_volunteer_app-form_add_new_inventory.html",
						controller: "form_add_new_inventoryCtrl"
					},
			"fabButtonUp" : {
						template: '',
					},
		}
	})

	.state("gsja_volunteer_app.list_barang", {
		url: "/list_barang",
		cache:false,
		views: {
			"gsja_volunteer_app-list_barang" : {
						templateUrl:"templates/gsja_volunteer_app-list_barang.html",
						controller: "list_barangCtrl"
					},
			"fabButtonUp" : {
						template: '',
					},
		}
	})

	.state("gsja_volunteer_app.list_barang_singles", {
		url: "/list_barang_singles/:id",
		cache:false,
		views: {
			"gsja_volunteer_app-list_barang" : {
						templateUrl:"templates/gsja_volunteer_app-list_barang_singles.html",
						controller: "list_barang_singlesCtrl"
					},
			"fabButtonUp" : {
						template: '',
					},
		}
	})

	.state("gsja_volunteer_app.menu_one", {
		url: "/menu_one",
		views: {
			"gsja_volunteer_app-menu_one" : {
						templateUrl:"templates/gsja_volunteer_app-menu_one.html",
						controller: "menu_oneCtrl"
					},
			"fabButtonUp" : {
						template: '',
					},
		}
	})

	.state("gsja_volunteer_app.menu_two", {
		url: "/menu_two",
		views: {
			"gsja_volunteer_app-menu_two" : {
						templateUrl:"templates/gsja_volunteer_app-menu_two.html",
						controller: "menu_twoCtrl"
					},
			"fabButtonUp" : {
						template: '',
					},
		}
	})

	.state("gsja_volunteer_app.ptt", {
		url: "/ptt",
		cache:false,
		views: {
			"gsja_volunteer_app-ptt" : {
						templateUrl:"templates/gsja_volunteer_app-ptt.html",
						controller: "pttCtrl"
					},
			"fabButtonUp" : {
						template: '',
					},
		}
	})

	.state("gsja_volunteer_app.slide_tab_menu", {
		url: "/slide_tab_menu",
		views: {
			"gsja_volunteer_app-slide_tab_menu" : {
						templateUrl:"templates/gsja_volunteer_app-slide_tab_menu.html",
						controller: "slide_tab_menuCtrl"
					},
			"fabButtonUp" : {
						template: '',
					},
		}
	})

	.state("gsja_volunteer_app.test", {
		url: "/test",
		cache:false,
		views: {
			"gsja_volunteer_app-test" : {
						templateUrl:"templates/gsja_volunteer_app-test.html",
						controller: "testCtrl"
					},
			"fabButtonUp" : {
						template: '',
					},
		}
	})

	$urlRouterProvider.otherwise("/gsja_volunteer_app/dashboard");
});
