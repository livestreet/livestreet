/**
 * JavaScript-hooks
 *
 * @example
 *
 * - inject function call/code at top of function body
 * ls.hook.inject([ls.lang,'get'], function(){ls.msg.notice('lang debug');})});
 * ls.hook.inject([ls,'ajax'], 'alert(url)');
 *
 * - add and call hooks
 * ls.hook.add('somefunc_hook1_name', function(param1, param2){ ... });
 *
 * function someFunc(..params..){
 * 	//code
 * 	ls.hook.run('somefunc_hook1_name', [param1,param2], thisArg);
 * 	//code
 * }
 *
 * @author Sergey S Yaglov
 * @link http://livestreet.ru/profile/1d10t
 */
var ls = ls || {};

ls.hook = (function ($) {
	this.hooks = {};

	this.cloneFunc = function(func,as_text,no_def) {
		var f;
		if($.type(func)=='string'){
			eval('f = '+func+';');
		}else if($.type(func)=='array'){
			f = func[0][func[1]];
		}else{
			f = func;
		}
		if($.type(f)=='function'){
			var fbody = f.toString().replace(/^(function)([^\(]*)\(/gi, '$1 (');
			if(typeof as_text!='undefined' && as_text){
				if(typeof no_def!='undefined' && no_def){
					return fbody.replace(/^[^\{]*\{/gi, '').replace(/\}$/gi, '');
				}else{
					return fbody;
				}
			}
			return eval('('+fbody+')');
		}
		return function(){};
	};

	/**
	 * @param func functionName|object[parentObject,functionName] Name of function that will be modified
	 * @param funcInj function|string Function or code to be injected
	 * @param marker string
	 */
	this.inject = function(func,funcInj,marker) {
		var funcBody = ls.hook.cloneFunc(func, 1);
		var funcDefinition = ($.type(func)=='string'?func:($.type(func)=='array'?'func[0][func[1]]':'func'))+' = ';
		var replaceFrom = /\{/m;
		var replaceTo = '{ ';
		if($.type(marker) == 'string'){
			//replaceFrom = new RegExp('(\'\\*'+marker+'\\*\'[\r\n\t ]*;?)', 'm');
			replaceFrom = new RegExp('(ls\\.hook\\.marker\\(([\'"])'+marker+'(\\2)\\)[\\r\\n\\t ]*;?)','m');
			replaceTo = '$1';
		}
		if($.type(funcInj)=='function'){
			var funcInjName = 'funcInj'+Math.floor(Math.random()*1000000);
			eval('window["'+funcInjName+'"] = funcInj;');
			eval(funcDefinition + funcBody.replace(replaceFrom,replaceTo+funcInjName+'.apply(this, arguments); '));
		}else{
			eval(funcDefinition  + funcBody.replace(replaceFrom,replaceTo+funcInj+'; '));
		}
	};

	this.add = function(name,callback,priority) {
		var priority = priority || 0;
		if(typeof ls.hook.hooks[name] == 'undefined'){
			ls.hook.hooks[name] = [];
		}
		ls.hook.hooks[name].push({
			'callback': callback,
			'priority': priority
		});
	};

	this.run = function(name,params,o) {
		var params = params || [];
		var hooks = ls.hook.hooks;
		if(typeof hooks[name] != 'undefined'){
			hooks[name].sort(function(a,b){
				return a.priority > b.priority ?
					1
					: (a.priority < b.priority ? -1 : 0)
					;
			});
			$.each(hooks[name], function(i){
				var callback = hooks[name][i].callback;
				if($.type(callback) == 'function'){
					callback.apply(o, params);
				}else if($.type(callback) == 'array'){
					//console.log(callback);
					callback[0][callback[1]].apply(o, params);
				}else if($.type(callback) == 'string'){
					eval('(function(){'+callback+'}).apply(o, params);');
				}else{
					ls.dev.debug('cant call hook "'+name+'"['+i+']');
				}
			});
		}
	};

	this.marker = function(name){
		// noop
	};

	return this;
}).call(ls.hook || {},jQuery);