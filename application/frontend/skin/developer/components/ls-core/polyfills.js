/**
 * Bind
 */
if ( ! Function.prototype.bind ) {
	Function.prototype.bind = function ( obj ) {
	    if ( typeof this !== "function" ) {
	        throw new TypeError("Function.prototype.bind - what is trying to be bound is not callable");
	    }

	    var slice = [].slice,
	        args = slice.call(arguments, 1),
	        self = this,
	        nop = function () {},
	        bound = function () {
	            return self.apply( this instanceof nop ? this : ( obj || {} ), args.concat( slice.call( arguments ) ) );
	        };

	    nop.prototype = this.prototype;
	    bound.prototype = new nop();

	    return bound;
	};
}