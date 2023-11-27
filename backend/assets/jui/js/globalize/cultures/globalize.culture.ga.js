/*
 * Globalize Culture ga
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
Globalize.addCultureInfo( "ga", "default", {
	name: "ga",
	englishName: "Irish",
	nativeName: "Gaeilge",
	language: "ga",
	numberFormat: {
		currency: {
			pattern: ["-$n","$n"],
			symbol: "€"
		}
	},
	calendars: {
		standard: {
			firstDay: 1,
			days: {
				names: ["Dé Domhnaigh","Dé Luain","Dé Máirt","Dé Céadaoin","Déardaoin","Dé hAoine","Dé Sathairn"],
				namesAbbr: ["Domh","Luan","Máir","Céad","Déar","Aoi","Sath"],
				namesShort: ["Do","Lu","Má","Cé","De","Ao","Sa"]
			},
			months: {
				names: ["Eanáir","Feabhra","Márta","Aibreán","Bealtaine","Meitheamh","Iúil","Lúnasa","Meán Fómhair","Deireadh Fómhair","Samhain","Nollaig",""],
				namesAbbr: ["Ean","Feabh","Már","Aib","Bealt","Meith","Iúil","Lún","M.Fómh","D.Fómh","Samh","Noll",""]
			},
			AM: ["r.n.","r.n.","R.N."],
			PM: ["i.n.","i.n.","I.N."],
			patterns: {
				d: "dd/MM/yyyy",
				D: "d MMMM yyyy",
				t: "HH:mm",
				T: "HH:mm:ss",
				f: "d MMMM yyyy HH:mm",
				F: "d MMMM yyyy HH:mm:ss",
				M: "dd MMMM",
				Y: "MMMM yyyy"
			}
		}
	}
});
}( this ));
