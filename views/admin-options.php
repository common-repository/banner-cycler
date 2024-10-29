<script type="text/javascript">
	jQuery(function() {
		jQuery('#pacycler_cycler').change(function() {
			var id = jQuery(this).val();
			window.location = '<?php echo PACYCLER_OPTIONSPAGEURL; ?>&view=cycler&pacycler_id=' + id;
		});
		jQuery('#pacycler_delete').click(function() {
			var ans = confirm("Are you sure you want to delete this cycler and all associated slides?");
			return ans;
		});
	});
</script>

<div id="pacycler-admin" class="wrap">
	
    <h2>Banner Cycler</h2>
    
    <?php include('sub-menu.php'); ?>
    
    <?php if (isset($message)) : ?>
    <div id="message" class="updated fade below-h2">
    	<p><?php echo $message; ?></p>
    </div>
    <?php endif; ?>
    
    <h3><?php echo (isset($current_cycler) ? 'Edit' : 'Add'); ?> Cycler</h3>
    
    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
    	<input type="hidden" name="pacycler_postback" value="1" />
    	<input type="hidden" name="pacycler_doaction" value="pacycler_editcycler" />
    	<input type="hidden" name="pacycler_id" value="<?php if (isset($current_cycler->cycler_id)) echo $current_cycler->cycler_id; ?>" />
        <table class="form-table">
        	<tr class="form-required">
                <th scope="row">
                    <label for="pacycler_name">Cycler</label>
                </th>
                <td>
                    <select id="pacycler_cycler" name="pacycler_cycler">
                        <option value="">Add a new cycler</option>
                        <?php foreach($cyclers as $cycler) : ?>
                            <option <?php if ($current_cycler->cycler_id == $cycler->cycler_id) echo 'selected="selected"'; ?> value="<?php echo $cycler->cycler_id; ?>">
                                <?php echo $cycler->cycler_name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select> 
                    <?php if ($current_cycler->cycler_id) : ?>
                    	<a href="<?php echo PACYCLER_OPTIONSPAGEURL; ?>&view=cycler&pacycler_action=delete&pacycler_cyclerid=<?php echo $current_cycler->cycler_id; ?>" style="margin-left:20px;" href="#">Delete</a>
                   	<?php endif; ?>
                </td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row">
                    <label for="pacycler_name">Cycler Name</label>
                </th>
                <td>
                    <input type="text" class="regular-text" name="pacycler_name" value="<?php if (isset($current_cycler)) echo htmlspecialchars($current_cycler->cycler_name); ?>" />
                </td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row">
                    <label for="pacycler_jquerycall">jQuery Call</label>
                </th>
                <td>
                    <textarea class="regular-text" id="pacycler_jquerycall" name="pacycler_jquerycall"><?php if (isset($current_cycler)) echo $current_cycler->jquery_call; ?></textarea>
                    <p class="description indicator-hint">Insert the jQuery call that will be used to display this slideshow. Script here will be called within jQuery(document).ready().</p>
                </td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row">
                    <label for="pacycler_htmltemplate">HTML Template</label>
                </th>
                <td>
                    <textarea class="regular-text" id="pacycler_htmltemplate" name="pacycler_htmltemplate"><?php if (isset($current_cycler)) echo $current_cycler->html_template; ?></textarea>
                    <p class="description indicator-hint">Enter the HTML that will wrap the slideshow. Place %items% where you want to display the items that will appear in the "Item Template" format below.</p>
                </td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row">
                    <label for="pacycler_htmltemplate">Item Template</label>
                </th>
                <td>
                    <textarea class="regular-text" id="pacycler_itemtemplate" name="pacycler_itemtemplate"><?php if (isset($current_cycler)) echo $current_cycler->item_template; ?></textarea>
                    <p class="description indicator-hint">Specify the HTML template for each item displayed by this cycler. You may use the following fields for slide data: %templatepath%, %text1%, %text2%, %text3%, %text4%, %image1%, %image2%, %image3%</p>
                </td>
            </tr>
        </table>
        <br />
        <div id="poststuff">
            <div id="slide-textfields" class="stuffbox">
                <h3><label>Slide Text Field Labels</label></h3>
                
                <table class="form-table">
                    <?php for ($i=0; $i<PACYCLER_NUMTEXTFIELDS; $i++) : ?>
                        <tr class="form-field">
                        
                            <th scope="row"><input type="text" class="regular-text" name="pacycler_text<?php echo ($i+1); ?>" value="<?php if (isset($current_cycler)) echo $current_cycler->{'text' . ($i+1)}; ?>" /></th>
                        </tr>
                    <?php endfor; ?>
                </table>
                <br />
            </div>
            
            <div id="slide-imagefields" class="stuffbox">
                <h3><label>Slide Image Field Labels</label></h3>
                
                <table class="form-table">
                    <?php for ($i=0; $i<3; $i++) : ?>
                        <tr class="form-field">
                            <th scope="row"><input type="text" class="regular-text" name="pacycler_image<?php echo ($i+1); ?>" value="<?php if (isset($current_cycler)) echo $current_cycler->{'image' . ($i+1)}; ?>"/></th>
                        </tr>
                    <?php endfor; ?>
                </table>
                <br />
            </div>
       	</div>
        
        <p class="submit">
        	<input class="button-primary" type="submit" value="Save Changes" name="pacycler_save"/>
        	<input class="button-secondary" type="submit" value="Delete" id="pacycler_delete" name="pacycler_delete"/>
        </p>
   	</form>
</div>