const { __ } = wp.i18n;
const { PluginSidebar, PluginSidebarMoreMenuItem } = wp.editPost;
import AceEditor from "react-ace";

// These has to come after the AceEditor import.
import "ace-builds/src-noconflict/ext-language_tools";
import "ace-builds/src-noconflict/mode-css";
import "ace-builds/src-noconflict/theme-github";

export const PANEL_ICON = "editor-code";

export default function CSSPanel() {
	return (
		<>
			<PluginSidebarMoreMenuItem
				target="bm-page-specific-css-sidebar"
				icon={PANEL_ICON}
			>
				{__( "Page Specific CSS", "bm-page-specific-css" )}
			</PluginSidebarMoreMenuItem>

			<PluginSidebar
				name="bm-page-specific-css-sidebar"
				title={__( "Page Specific CSS", "bm-page-specific-css" )}
				icon={PANEL_ICON}
			>
				<AceEditor
					onChange={onChange}
					width="100%"
					mode="css"
					theme="github"
					name="bm-page-specific-css"
					fontSize={14}
					tabSize={4}
					showPrintMargin={false}
					showGutter={true}
					highlightActiveLine={true}
					value={getCSSValue()}
					enableBasicAutocompletion={true}
					enableLiveAutocompletion={true}
					enableSnippets={true}
					editorProps={{ $blockScrolling: true }}
				/>
			</PluginSidebar>
		</>
	);
}

function getCSSValue() {
	const meta = wp.data.select( "core/editor" ).getEditedPostAttribute( "meta" );

	if ( meta.page_specific_styles ) {
		return meta.page_specific_styles;
	}
	return "";
}

function onChange( newValue ) {
	wp.data.dispatch( "core/editor" ).editPost( {
		meta: {
			page_specific_styles: newValue,
		},
	} );
}
