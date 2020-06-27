const { registerPlugin } = wp.plugins;
import CSSPanel, { PANEL_ICON } from "./components/css-panel";

registerPlugin( "bm-page-specific-css", {
	render: CSSPanel,
	icon: PANEL_ICON
} );
