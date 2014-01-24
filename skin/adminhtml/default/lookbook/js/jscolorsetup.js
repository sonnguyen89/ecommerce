Event.observe(window, 'load', function() {
	function jsColor(mainId, exceptions){
		var selection = 'input.input-text:not('+ exceptions +')';
		var selected_items = $$(mainId)[0].select(selection);
		selected_items.each(function(val){
			new jscolor.color(val);
		});
	}
	jsColor('#meigee_design_base');
});