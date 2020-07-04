const { select } = wp.data;
const { registerPlugin } = wp.plugins;
import CSSPanel, { PANEL_ICON } from "./components/css-panel";

registerPlugin( "bm-page-specific-css", {
	render() {
		const postType = select( "core/editor" ).getCurrentPostType();

		if ( "page" !== postType ) {
			return null;
		}

		return <CSSPanel />;
	},
	icon: PANEL_ICON
} );
