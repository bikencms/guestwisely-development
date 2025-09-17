jQuery(document).ready(function () {
	if(typeof(_villas365ReadMore) !== "function")
	{
		return;
	}

	let tabs = jQuery(".tabs_fullwidth_map .et_pb_tabs_controls li");

	let policiesTab = null;
	tabs.each(function(index) {
		let tab = jQuery(this);
		let tabLinkText = tab.find("a").text().toLowerCase();

		if((tabLinkText.includes("policies")) ||
			(tabLinkText.includes("richtlinien")) || //de
			(tabLinkText.includes("políticas")) || //es
			(tabLinkText.includes("politique de paiement")) || //fr
			(tabLinkText.includes("politiche")) || //it
			(tabLinkText.includes("políticas")) //pt
		)
		{
			policiesTab = tab;
			return false;
		}
	});

	//If we have the expected tabs then run the policy read more functionality if the tab is clicked.
	if((policiesTab != null) && (policiesTab.length > 0))
	{
		policiesTab.one("click", function() {
			let tabClass = policiesTab.removeClass("et_pb_tab_active")[0].className;
			setTimeout(function() {
				_villas365ReadMore(jQuery(".et_pb_all_tabs ." + tabClass));
			}, 850);
		});
	}
});