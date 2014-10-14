/**
 * Polyfills
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

/**
 * Атрибут form
 * https://developer.mozilla.org/en-US/docs/Web/HTML/Element/button#attr-form
 */
if ( ! document.createElement( 'button' ).hasOwnProperty( 'form' ) ) {
	jQuery( document ).on( 'click', 'button[form]', function () {
		jQuery( $( this ).attr( 'form' ) ).submit();
	});
}