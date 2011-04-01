function split(val) { return val.split( /,\s*/ ); }
function extractLast(term) { return split(term).pop(); }

function autocompleteAdd(obj, sPath, multiple) {
	if (multiple) {
		obj.bind("keydown", function(event) {
			if ( event.keyCode === $.ui.keyCode.TAB && $( this ).data( "autocomplete" ).menu.active ) {
				event.preventDefault();
			}
		})
		.autocomplete({
			source: function(request, response) {
				$.ajax({
					url: sPath, 
					type: 'post',
					data: { 
						value: extractLast(request.term),
						security_ls_key: LIVESTREET_SECURITY_KEY
					}, 
					success: function(data) {
						response(data.aItems);
					}
				});
			},
			search: function() {
				var term = extractLast(this.value);
				if (term.length < 2) {
					return false;
				}
			},
			focus: function() {
				return false;
			},
			select: function(event, ui) {
				var terms = split(this.value);
				terms.pop();
				terms.push(ui.item.value);
				terms.push("");
				this.value = terms.join(", ");
				return false;
			}
		});
	} else { 
		obj.autocomplete({
			source: function(request, response) {
				$.ajax({
					url: sPath, 
					type: 'post',
					data: { 
						value: extractLast(request.term),
						security_ls_key: LIVESTREET_SECURITY_KEY
					}, 
					success: function(data) {
						response(data.aItems);
					}
				});
			}
		});
	}
}
