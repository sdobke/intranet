/*
 * Globalize Culture sa
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
Globalize.addCultureInfo( "sa", "default", {
	name: "sa",
	englishName: "Sanskrit",
	nativeName: "संस्कृत",
	language: "sa",
	numberFormat: {
		groupSizes: [3,2],
		percent: {
			groupSizes: [3,2]
		},
		currency: {
			pattern: ["$ -n","$ n"],
			groupSizes: [3,2],
			symbol: "रु"
		}
	},
	calendars: {
		standard: {
			"/": "-",
			days: {
				names: ["रविवासरः","सोमवासरः","मङ्गलवासरः","बुधवासरः","गुरुवासरः","शुक्रवासरः","शनिवासरः"],
				namesAbbr: ["रविवासरः","सोमवासरः","मङ्गलवासरः","बुधवासरः","गुरुवासरः","शुक्रवासरः","शनिवासरः"],
				namesShort: ["र","स","म","ब","ग","श","श"]
			},
			months: {
				names: ["जनवरी","फरवरी","मार्च","अप्रैल","मई","जून","जुलाई","अगस्त","सितम्बर","अक्तूबर","नवम्बर","दिसम्बर",""],
				namesAbbr: ["जनवरी","फरवरी","मार्च","अप्रैल","मई","जून","जुलाई","अगस्त","सितम्बर","अक्तूबर","नवम्बर","दिसम्बर",""]
			},
			AM: ["पूर्वाह्न","पूर्वाह्न","पूर्वाह्न"],
			PM: ["अपराह्न","अपराह्न","अपराह्न"],
			patterns: {
				d: "dd-MM-yyyy",
				D: "dd MMMM yyyy dddd",
				t: "HH:mm",
				T: "HH:mm:ss",
				f: "dd MMMM yyyy dddd HH:mm",
				F: "dd MMMM yyyy dddd HH:mm:ss",
				M: "dd MMMM"
			}
		}
	}
});
}( this ));
