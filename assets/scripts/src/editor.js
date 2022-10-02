const { registerPlugin } = wp.plugins;
import CSSPanel, { PANEL_ICON } from "./components/css-panel";

registerPlugin( "bm-page-specific-css", {
	render() {
		return <CSSPanel />;
	},
	icon: PANEL_ICON
} );
