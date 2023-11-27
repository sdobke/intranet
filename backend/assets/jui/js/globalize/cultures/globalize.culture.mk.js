/*
 * Globalize Culture mk
 *
 * http://github.com/jquery/globalize
 *
 * Copyright Software Freedom Conservancy, Inc.
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * This file was generated by the Globalize Culture Generator
 * Translation: bugs found in this file need to be fixed in the generator
 */
(function( window, undefined ) {
var Globalize;
if ( typeof require !== "undefined" &&
	typeof exports !== "undefined" &&
	typeof module !== "undefined" ) {
	// Assume CommonJS
	Globalize = require( "globalize" );
} else {
	// Global variable
	Globalize = window.Globalize;
}
Globalize.addCultureInfo( "mk", "default", {
	name: "mk",
	englishName: "Macedonian (FYROM)",
	nativeName: "македонски јазик",
	language: "mk",
	numberFormat: {
		",": ".",
		".": ",",
		percent: {
			",": ".",
			".": ","
		},
		currency: {
			pattern: ["-n $","n $"],
			",": ".",
			".": ",",
			symbol: "ден."
		}
	},
	calendars: {
		standard: {
			"/": ".",
			firstDay: 1,
			days: {
				names: ["недела","понеделник","вторник","среда","четврток","петок","сабота"],
				namesAbbr: ["нед","пон","втр","срд","чет","пет","саб"],
				namesShort: ["не","по","вт","ср","че","пе","са"]
			},
			months: {
				names: ["јануари","февруари","март","април","мај","јуни","јули","август","септември","октомври","ноември","декември",""],
				namesAbbr: ["јан","фев","мар","апр","мај","јун","јул","авг","сеп","окт","ное","дек",""]
			},
			AM: null,
			PM: null,
			patterns: {
				d: "dd.MM.yyyy",
				D: "dddd, dd MMMM yyyy",
				t: "HH:mm",
				T: "HH:mm:ss",
				f: "dddd, dd MMMM yyyy HH:mm",
				F: "dddd, dd MMMM yyyy HH:mm:ss",
				M: "dd MMMM",
				Y: "MMMM yyyy"
			}
		}
	}
});
}( this ));
