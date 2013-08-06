/*
 *jQuery browser plugin detection 1.0.3
 * http://plugins.jquery.com/project/jqplugin
 * Checks for plugins / mimetypes supported in the browser extending the jQuery.browser object
 * Copyright (c) 2008 Leonardo Rossetti motw.leo@gmail.com
 * MIT License: http://www.opensource.org/licenses/mit-license.php
   THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
   IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
   FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
   AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
   LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
   OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
   THE SOFTWARE.
*/
(function ($) {
	//$.getScript("http://java.com/js/deployJava.js");

	//checks if browser object exists
	if (typeof $.browser === "undefined" || !$.browser) {
		var browser = {};
		$.extend(browser);
	}
	var pluginList = {
		flash: {
			activex: ["ShockwaveFlash.ShockwaveFlash", "ShockwaveFlash.ShockwaveFlash.3", "ShockwaveFlash.ShockwaveFlash.4", "ShockwaveFlash.ShockwaveFlash.5", "ShockwaveFlash.ShockwaveFlash.6", "ShockwaveFlash.ShockwaveFlash.7"],
			plugin: /flash/gim
		},
		sl: {
			activex: ["AgControl.AgControl"],
			plugin: /silverlight/gim
		},
		pdf: {
			activex: ["acroPDF.PDF.1", "PDF.PdfCtrl.1", "PDF.PdfCtrl.4", "PDF.PdfCtrl.5", "PDF.PdfCtrl.6"],
			plugin: /adobe\s?acrobat/gim
		},
		qtime: {
			activex: ["QuickTime.QuickTime", "QuickTimeCheckObject.QuickTimeCheck.1", "QuickTime.QuickTime.4"],
			plugin: /quicktime/gim
		},
		wmp: {
			activex: ["WMPlayer.OCX", "MediaPlayer.MediaPlayer.1"],
			plugin: /(windows\smedia)|(Microsoft)/gim
		},
		shk: {
			activex: ["SWCtl.SWCtl", "SWCt1.SWCt1.7", "SWCt1.SWCt1.8", "SWCt1.SWCt1.9", "ShockwaveFlash.ShockwaveFlash.1"],
			plugin: /shockwave/gim
		},
		rp: {
			activex: ["RealPlayer", "rmocx.RealPlayer G2 Control.1"],
			plugin: /realplayer/gim
		}
	};
	var isSupported = function (p) {
		if (window.ActiveXObject) {
			$.browser[p] = false;
			
			for (i = 0; i < pluginList[p].activex.length; i++) {
				try {
					new ActiveXObject(pluginList[p].activex[i]);
					$.browser[p] = true;
				} catch (e) {}	
			}
		} else {
			$.each(navigator.plugins, function () {
				if (this.name.match(pluginList[p].plugin)) {
					$.browser[p] = true;
					return false;
				} else {
					$.browser[p] = false;
				}
			});
		}
	};
	
	$.each(pluginList, function (i, n) {
		isSupported(i);
	});
	//uses sun script to detect if java plugin is available
	/*
	$.getScript("http://java.com/js/deployJava.js", function () {
		if (deployJava.versionCheck("1.6.0+") || deployJava.versionCheck("1.4") || deployJava.versionCheck("1.5.0*")) {
			$.browser.java = true;
		} else {
			$.browser.java = false;
		}
	});
	*/
})(jQuery);