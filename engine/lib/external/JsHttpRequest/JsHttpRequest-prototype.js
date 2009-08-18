/**
 * JsHttpRequest & Prototype integration module.
 * Include this file just after the JsHttpRequest and Prototype inclusion.
 *
 * @license LGPL
 * @author Dmitry Koterov, http://en.dklab.ru/lib/JsHttpRequest/
 * @version 5.x $Id$
 */

Ajax.Request.prototype._jshr_setOptions = Ajax.Request.prototype.setOptions;
Ajax.Request.prototype.setOptions = function(options) {
    // Support for whole form & form element sending.
    var parameters = options.parameters;
    options.parameters = {};
    this.transport._jshr_send = this.transport.send;
    this.transport.send = function(body) {
        return this._jshr_send(body || parameters);
    }
    this._jshr_setOptions(options);
}

Ajax.getTransport = function() {
    return new JsHttpRequest();
}

Ajax.Request.prototype.evalResponse = Prototype.emptyFunction;
