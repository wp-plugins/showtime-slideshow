<?php

class aframework
{

	var $PLUGIN_FRAMEWORK_VERSION = "1.0";

	var $PARENT_MENU_DASHBOARD = "index.php";
	var $PARENT_MENU_ARTICLE = "edit.php";
	var $PARENT_MENU_MEDIA = "upload.php";
	var $PARENT_MENU_LINKS = "link-manager.php";
	var $PARENT_MENU_PAGES = "edit-pages.php";
	var $PARENT_MENU_COMMENTS = "edit-comments.php";
	var $PARENT_MENU_PRESENTATION = "themes.php";
	var $PARENT_MENU_PLUGINS = "plugins.php";
	var $PARENT_MENU_USERS = "users.php";
	var $PARENT_MENU_TOOLS = "tools.php";
	var $PARENT_MENU_OPTIONS = "options-general.php";
	
	var $ICON = '/showtime/showtime.png'; 


	var $ACCESS_LEVEL_ADMINISTRATOR = 8;
	var $ACCESS_LEVEL_EDITOR = 3;
	var $ACCESS_LEVEL_AUTHOR = 2;
	var $ACCESS_LEVEL_CONTRIBUTOR = 1;
	var $ACCESS_LEVEL_SUBSCRIBER = 0;

	var $CONTENT_BLOCK_TYPE_MAIN = "content-block-type-main";
	var $CONTENT_BLOCK_TYPE_MAIN_BLANK = "content-block-type-main-blank";
	var $CONTENT_BLOCK_TYPE_SIDEBAR = "content-block-type-sidebar";

	var $CONTENT_BLOCK_INDEX_TITLE = 0;
	var $CONTENT_BLOCK_INDEX_TYPE = 1;
	var $CONTENT_BLOCK_INDEX_FUNCTION = 2;
	var $CONTENT_BLOCK_INDEX_FUNCTION_CLASS = 0;
	var $CONTENT_BLOCK_INDEX_FUNCTION_NAME = 1;

	var $OPTION_PARAMETER_NOT_FOUND = 'Not found...';

	var $OPTION_INDEX_VALUE = 0;
	var $OPTION_INDEX_DESCRIPTION = 1;
	var $OPTION_INDEX_TYPE = 2;
	var $OPTION_INDEX_VALUES_ARRAY = 3;

	var $OPTION_TYPE_TEXTBOX = "text";
	var $OPTION_TYPE_TEXTAREA = "textarea";
	var $OPTION_TYPE_CHECKBOX = "checkbox";
	var $CHECKBOX_UNCHECKED = "";
	var $CHECKBOX_CHECKED = "on";
	var $OPTION_TYPE_RADIOBUTTONS = "radio";
	var $OPTION_TYPE_PASSWORDBOX = "password";
	var $OPTION_TYPE_COMBOBOX = "combobox";
	var $OPTION_TYPE_FILEBROWSER = "file";
	var $OPTION_TYPE_HIDDEN = "hidden";
	
	var $_pluginTitle = "";
	var $_pluginVersion = "";
	var $_pluginSubfolderName = "";
	var $_pluginFileName = "";
	var $_pluginHome = "";
	var $_pluginComments = "";
	var $_pluginRate = "";
	var $_pluginMyPlugins = "";
	var $_pluginContact = "";
	var $_pluginAdminMenuTitle = "";
	var $_pluginAdminMenuPageTitle = "";
	var $_pluginAdminMenuPageSlug = "";
	
	var $_pluginAdminMenuParentMenu = "";
	var $_pluginAdminMenuMinimumAccessLevel = "";
	var $_pluginOptionsArray = array();
	var $_pluginAdminMenuBlockArray = array();

	function GetOptionsArray() {
		return $this->_pluginOptionsArray;
	}
	
	function Initialize( $title, $version, $subfolderName, $fileName, $pluginhome ) {
		$this->_pluginTitle = $title;
		$this->_pluginVersion = $version;
		$this->_pluginSubfolderName = $subfolderName;
		$this->_pluginFileName = $fileName;
		$this->_pluginHome = $pluginhome;

	}
	
	
	function RegisterAdministrationPage( $parentMenu, $minimumAccessLevel, $adminMenuTitle, $adminMenuPageTitle, $adminMenuPageSlug ) 
	{
		global $wp_version;
		$this->_pluginAdminMenuParentMenu = $parentMenu;
		$this->_pluginAdminMenuMinimumAccessLevel = $minimumAccessLevel;
		
		$tempTitle = "<style type='text/css'>.tag {border:1px solid #ccc; background:#ddd; -border-radius:10px; -webkit-border-radius:10px; -moz-border-radius:10px; padding:0 7px 1px 7px; color:#fff;text-shadow:0 0 2px #666; display:block; float:right; margin:1px 0;} textarea{width:100%;height:200px}</style>";
		/*
		if ( version_compare( $wp_version, '2.6.999', '>' ) ) {
			$tempTitle .= '<style type="text/css">';
			$tempTitle .= "	#$adminMenuPageSlug {";
			$tempTitle .= "		height:16px;width:16px;float:left;margin-right:3px;margin-top:2px;";
			$tempTitle .= '		background: transparent url("' . trailingslashit( get_bloginfo('url') ) . '?' . $this->_pluginImage . '=' . $adminMenuIconFile . '") no-repeat scroll 0px 0px;';
			$tempTitle .= "	}";
			$tempTitle .= "	#$adminMenuPageSlug.hover, li.current a.current #$adminMenuPageSlug {";
			$tempTitle .= '		background: transparent url("' . trailingslashit( get_bloginfo('url') ) . '?' . $this->_pluginImage . '=' . $adminMenuIconFile . '") no-repeat scroll 0px -16px;';
			$tempTitle .= "	}";
			$tempTitle .= "</style>";
			$tempTitle .= '<div id="' . $adminMenuPageSlug . '"></div>';
		}
*/
		$this->_pluginAdminMenuTitle = $tempTitle . $adminMenuTitle;
		$this->_pluginAdminMenuPageTitle = $adminMenuPageTitle;
		$this->_pluginAdminMenuPageSlug = $adminMenuPageSlug;
		
		add_action( 'admin_menu', array( $this, '_AddAdministrationPage' ) );
	}
	
	function image_to_editor( $shcode, $html ) {
		return $html;
	}
	
	function _AddAdministrationAjax() {
		wp_enqueue_script( 'postbox' );
		wp_enqueue_script( 'post' );
	}
	
	function _AddAdministrationPage() {
		add_submenu_page(	$this->_pluginAdminMenuParentMenu,
							$this->_pluginAdminMenuPageTitle,
							$this->_pluginAdminMenuTitle,
							$this->_pluginAdminMenuMinimumAccessLevel,
							$this->_pluginAdminMenuPageSlug,
							array( $this, '_DisplayPluginAdministrationPage' )
						);
	}
	
	function AddAdministrationPageBlock( $blockId, $blockTitle, $blockType, $blockFunctionPtr, $blockInside ) {
		$this->_pluginAdminMenuBlockArray[$blockId] = array( $blockTitle, $blockType, $blockFunctionPtr, $blockInside );
	}
	
	function _DisplayAdministrationPageBlocks( $blockType ) {
		if( is_array( $this->_pluginAdminMenuBlockArray ) ) {
			foreach( $this->_pluginAdminMenuBlockArray AS $blockKey=>$blockValue ) {
				if( $blockValue[$this->CONTENT_BLOCK_INDEX_TYPE] == $blockType ) {
					switch( $blockType ) {
						case $this->CONTENT_BLOCK_TYPE_SIDEBAR:
							if ( $blockValue[3] ) {
							?>
                            <div id="tagsdiv" class="postbox">
                                <div title="<? _e( 'Click to toggle', TXTDOMAIN ); ?>" class="handlediv"><br/></div>
                                <h3 class="hndle"><span><?php echo( $blockValue[$this->CONTENT_BLOCK_INDEX_TITLE] ); ?></span></h3>
                                <div class="inside">
									<?php
							}
                                    $blockClass = $blockValue[$this->CONTENT_BLOCK_INDEX_FUNCTION][$this->CONTENT_BLOCK_INDEX_FUNCTION_CLASS];
                                    $blockFunction = $blockValue[$this->CONTENT_BLOCK_INDEX_FUNCTION][$this->CONTENT_BLOCK_INDEX_FUNCTION_NAME];
                                    $blockClass->$blockFunction();
							if ( $blockValue[3] ) {
                                    ?>
                                </div>
                            </div>
							<?php
							}
							break;
						case $this->CONTENT_BLOCK_TYPE_MAIN:
							if ( $blockValue[3] ) {
							?>
                            <div class="postbox" id="submitdiv">
                                <div title="<? _e( 'Click to toggle', TXTDOMAIN ); ?>" class="handlediv"><br/></div>
                                <h3 class="hndle"><span><?php echo( $blockValue[$this->CONTENT_BLOCK_INDEX_TITLE] ); ?></span></h3>
                                <div class="inside">
                                <div class="submitbox" id="submitpost">
									<?php
							}
                                    $blockClass = $blockValue[$this->CONTENT_BLOCK_INDEX_FUNCTION][$this->CONTENT_BLOCK_INDEX_FUNCTION_CLASS];
                                    $blockFunction = $blockValue[$this->CONTENT_BLOCK_INDEX_FUNCTION][$this->CONTENT_BLOCK_INDEX_FUNCTION_NAME];
                                    $blockClass->$blockFunction();
							if ( $blockValue[3]) {
                                    ?>
                                </div>
                            </div>
							<?php
							}
                            break;
						default:
							break;
					}
				}
			}
		}
	}
	
	function _DisplayFadingMessageBox( $htmlMessage, $type ) {
		switch($type) {
			case 'update':
				echo( '<div id="message" class="updated fade">' );
				break;
			case 'error':
				echo( '<div id="message" class="error">' );
				break;
		}
		echo( $htmlMessage );
		echo( '</div>' );
	}

	function _DisplayPluginAdministrationPage() {
		require_once( WP_PLUGIN_DIR."/showtime-slideshow/include/1col1sb.php");
	}

	function AddOption( $optionType, $optionName, $optionValue, $optionDescription, $optionValuesArray = '' ) {
		$this->_pluginOptionsArray[$optionName] = array( $optionValue, $optionDescription, $optionType, $optionValuesArray );
	}

	function RegisterOptions( $pluginFile ) {
		register_activation_hook( $pluginFile, array( $this, '_RegisterPluginOptions' ) );
	}
	 
	function _RegisterPluginOptions() {
		if( is_array( $this->_pluginOptionsArray ) ) {
			global $wpdb;
		
			$registeredOptions = $wpdb->get_results( "SELECT * FROM $wpdb->options ORDER BY option_name" );
		
			foreach( $this->_pluginOptionsArray AS $optionKey => $optionValue ) {
				$optionFound = false;
				foreach( (array) $registeredOptions AS $registeredOption ) {
					$registeredOption->option_name = attribute_escape( $registeredOption->option_name );
					if( $optionKey == $registeredOption->option_name ) {
						$optionFound = true;
					}
				}
				if( $optionFound == false ) {
					update_option( $optionKey, $optionValue[$this->OPTION_INDEX_VALUE] );
				}
			}
		}
	}

	function _UnregisterPluginOptions() {
		if( is_array( $this->_pluginOptionsArray ) ) {
			foreach( $this->_pluginOptionsArray AS $optionKey => $optionValue ) {
				delete_option( $optionKey );
			}
		}
	}

	function _IsPluginInstalled() {
		$pluginInstalled = true;
		if( is_array( $this->_pluginOptionsArray ) ) {
			global $wpdb;
		
			$registeredOptions = $wpdb->get_results( "SELECT * FROM $wpdb->options ORDER BY option_name" );
		
			foreach( $this->_pluginOptionsArray AS $optionKey => $optionValue ) {
				$optionFound = false;
				foreach( (array) $registeredOptions AS $registeredOption ) {
					$registeredOption->option_name = attribute_escape( $registeredOption->option_name );
					if( $optionKey == $registeredOption->option_name ) {
						$optionFound = true;
					}
				}
		
				if( $optionFound == false ) {
					$pluginInstalled = false;
					break;
				}
			}
		}
		return $pluginInstalled;
	}
	
	function _UpdatePluginOptions( &$requestArray )	{
		
		foreach( $this->_pluginOptionsArray AS $optionKey => $optionValueArray ) {
			update_option( $optionKey, $requestArray[$optionKey] );
		}

		$updatedMessage = sprintf( __( '<p>The %s plugin options have been updated in the database.</p>', TXTDOMAIN ), $this->_pluginTitle );
		$this->_DisplayFadingMessageBox( $updatedMessage, 'update' );
	}

	function _ResetPluginOptions() {
		foreach( $this->_pluginOptionsArray AS $optionKey => $optionValueArray ) {
			update_option( $optionKey, $optionValueArray[$this->OPTION_INDEX_VALUE] );
		}

		$resetMessage = sprintf( __( '<p>The %s plugin options have been reset to default values in the database.</p>', TXTDOMAIN ), $this->_pluginTitle );
		$this->_DisplayFadingMessageBox( $resetMessage, 'update' );
	}

	function GetOptionValue( $optionName ) {
		$optionValue = get_option( $optionName );
		
		return $optionValue;
	}
	
	function GetOptionType( $optionName ) {
		$optionDescription = $this->OPTION_PARAMETER_NOT_FOUND;
		
		if( array_key_exists( $optionName, $this->_pluginOptionsArray ) ) {
			$optionDescription = $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_TYPE];
		}
		
		return $optionDescription;
	}
	
	function GetOptionDescription( $optionName ) {
		$optionDescription = $this->OPTION_PARAMETER_NOT_FOUND;
		
		if( array_key_exists( $optionName, $this->_pluginOptionsArray ) ) {
			$optionDescription = $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_DESCRIPTION];
		}
		
		return $optionDescription;
	}
	
	function GetOptionValuesArray( $optionName ) {
		$optionValuesList = $this->OPTION_PARAMETER_NOT_FOUND;
		
		if( array_key_exists( $optionName, $this->_pluginOptionsArray ) ) {
			$optionValues = $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_VALUES_ARRAY];
			if( is_array( $optionValues ) ) {
				$optionValuesList = '';
		
				foreach( $optionValues AS $optionValue ) {
					$optionValuesList .= ',' . $optionValue;
				}
		
				$optionValuesList = trim( $optionValuesList, ',' );
			} else {
				$optionValuesList = $optionValues;
			}
		}
		
		return $optionValuesList;
	}
	
	function DisplayPluginOption( $optionName ) {
		$optionMarkup = '';
	
		if( array_key_exists( $optionName, $this->_pluginOptionsArray ) ) {
			switch( $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_TYPE] ) {
				case $this->OPTION_TYPE_TEXTBOX:
					$optionMarkup = '<input type="text" title="' . $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_DESCRIPTION] . '" name="' . $optionName . '" id="' . $optionName . '" value="' . get_option( $optionName ) . '" size="8" /> ';
					break;
				case $this->OPTION_TYPE_TEXTAREA:
					$optionMarkup = '<textarea title="' . $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_DESCRIPTION] . '" name="' . $optionName . '" id="' . $optionName . '">' . get_option( $optionName ) . '</textarea> ';
					break;
				case $this->OPTION_TYPE_CHECKBOX:
					$checkBoxValue = ( get_option( $optionName ) == true ) ? 'checked="checked"' : '';
					$optionMarkup .= '<input type="checkbox" title="' . $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_DESCRIPTION] . '" name="' . $optionName . '" id="' . $optionName . '" ' . $checkBoxValue . ' /> ';
					break;
				case $this->OPTION_TYPE_RADIOBUTTONS:
					$optionIdCount = 0;
					$optionMarkup = '';
					$valuesArray = $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_VALUES_ARRAY];
					if( is_array( $valuesArray ) ) {
						foreach( $valuesArray AS $valueName ) {
							$temp = array_keys( $valuesArray, $valueName );
							$selectedValue = ( get_option( $optionName ) == $temp[0] ) ? 'checked="checked"' : '';
							$optionMarkup .= '<input type="radio" title="' . $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_DESCRIPTION] . '" name="' . $optionName . '" id="' . $optionName . $optionIdCount . '" value="' . $valueName . '" ' . $selectedValue . ' /> ';
							$optionMarkup .= $valueName;
							$optionMarkup .= '<br />';
							$optionIdCount++;
						}
					}
					break;
				case $this->OPTION_TYPE_PASSWORDBOX:
					$optionMarkup = '<input title="' . $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_DESCRIPTION] . '" type="password" name="' . $optionName . '" id="' . $optionName . '" value="' . get_option( $optionName ) . '" /> ';
					break;
				case $this->OPTION_TYPE_COMBOBOX:
					$optionIdCount = 0;
					$optionMarkup = '<select title="' . $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_DESCRIPTION] . '" name="' . $optionName . '" id="' . $optionName . '" >';
					$valuesArray = $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_VALUES_ARRAY];
					if( is_array( $valuesArray ) ) {
						foreach( $valuesArray AS $valueName ) {
							$temp = array_keys( $valuesArray, $valueName );
							$selectedValue = ( get_option( $optionName ) == $temp[0] ) ? 'selected="selected"' : '';
							$optionMarkup .= '<option label="' . $temp[0] . '" value="' . $temp[0] . '" ' . $selectedValue . ' >' . $valueName . '</option>';
							$optionIdCount++;
						}
					
						$optionMarkup .= '</select>';
					}
					break;
				case $this->OPTION_TYPE_FILEBROWSER:
					$optionMarkup = '<input title="' . $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_DESCRIPTION] . '" type="file" name="' . $optionName . '" id="' . $optionName . '" /> ';
					break;
				case $this->OPTION_TYPE_HIDDEN:
					$optionMarkup .= '<input type="hidden" name="' . $optionName . '" id="' . $optionName . '" value="' . get_option( $optionName ) . '" /> ';
					break;
				default:
					$optionMarkup = '';
					break;
			}
		}
	echo( $optionMarkup );
	}
	

}
?>
