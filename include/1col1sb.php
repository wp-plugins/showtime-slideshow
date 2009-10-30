<div class="wrap">
    <div class="icon32" style="padding-right:10px">
    <img width="40" height="40" src="<?php echo plugins_url($path = $this->_pluginSubfolderName . '/showtime/showtime.png'); ?>" /><br/></div>
	<?php
	if( $_REQUEST['plugin_options_update'] ) {
        // Update the plugin's options.
		$this->_UpdatePluginOptions( $_REQUEST );
	} else if( $_REQUEST['plugin_options_reset'] ) {
        // Reset the plugin's options.
        $this->_ResetPluginOptions();
	} else if( $_REQUEST['plugin_options_uninstall'] ) {
        // Uninstall the plugin by removing the plugin options from the Wordpress database.
        $this->_UnregisterPluginOptions();
    }

    if( $this->_IsPluginInstalled() ) {
    ?>
    
    
    <h2><?php echo( $this->_pluginTitle ); ?></h2>
    <form id="org_myplugins_mpf" action="<?php echo( $_pluginAdminMenuParentMenu ); ?>?page=<?php echo( $this->_pluginAdminMenuPageSlug ) ?>" method="post">

        <div id="poststuff" class="metabox-holder has-right-sidebar">
            <!-- Plugin Sidebar -->
            
            <div id="side-info-column" class="inner-sidebar">

                <?php
                // Load the Sidebar blocks first...
                $this->_DisplayAdministrationPageBlocks( $this->CONTENT_BLOCK_TYPE_SIDEBAR );
                ?>

        </div>
        
        <!-- Plugin Main Content -->
        <div id="post-body">
            <div id="post-body-content" class="has-sidebar-content">
                <div id="normal-sortables" class="meta-box-sortables ui-sortable" style="position: relative;">
                    <?php
                    // Then load the main content blocks...
                    $this->_DisplayAdministrationPageBlocks( $this->CONTENT_BLOCK_TYPE_MAIN );
                    ?>
                </div>
            </div>
        </div>
        <br class="clear" />
    </form>
    <?php
    } else {
        // Update the URL to perform deactivation of the specified plugin.
        $deactivateUrl = 'plugins.php?action=deactivate&amp;plugin=' . $this->_pluginSubfolderName . '/' . $this->_pluginFileName . '.php';
        if( function_exists( 'wp_nonce_url' ) ) {
            $actionName = 'deactivate-plugin_' . $this->_pluginSubfolderName . '/' . $this->_pluginFileName . '.php';
            $deactivateUrl = wp_nonce_url( $deactivateUrl, $actionName );
        }
        // Remind the user to deactivate the plugin.
        $uninstalledMessage = sprintf( __( '<p>All of the %s plugin options have been deleted from the database.</p>', TXTDOMAIN ), $this->_pluginTitle );
        $uninstalledMessage .= sprintf( __( '<p><strong><a href="%1$s">Click here</a></strong> to finish the uninstallation and deactivate the %2$s plugin.</p>', TXTDOMAIN ), $deactivateUrl, $this->_pluginTitle );
        $this->_DisplayFadingMessageBox( $uninstalledMessage, 'error' );
    }
    ?>
    

</div>