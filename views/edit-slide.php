<script type="text/javascript">
	jQuery(function() {
		jQuery('#pacycler_cycler').change(function() {
			var id = jQuery(this).val();
			window.location = '<?php echo PACYCLER_OPTIONSPAGEURL; ?>&view=slide&pacycler_id=' + id;
		});
		/*jQuery('#pacycler_delete').click(function() {
			var ans = confirm("Are you sure you want to delete this cycler and all associated slides?");
			return ans;
		});*/
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
    
    <div id="col-container">
		<div id="col-right">
        	<div class="col-wrap">
        		<form id="slides-filter" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
    				<input type="hidden" name="pacycler_postback" value="1" />
                	<input type="hidden" name="action" value="pacycler_actions" />
          			<div class="tablenav">
                    	<div class="alignleft actions">
                        
                            <select id="pacycler_cycler" name="pacycler_cycler">
                                <option value="">Select a cycler...</option>
                                <?php foreach($cyclers as $cycler) : ?>
                                	<option <?php if ($current_cycler->cycler_id == $cycler->cycler_id) echo 'selected="selected"'; ?> value="<?php echo $cycler->cycler_id; ?>">
										<?php echo $cycler->cycler_name; ?>
                                   	</option>
                                <?php endforeach; ?>
                            </select>
                            
                            <select name="pacycler_cycleraction">
                            	<option value="" selected="selected">Bulk Actions</option>
                            	<option value="delete">Delete Slides</option>
                            </select>
                            <input type="submit" value="Apply" name="pacycler_doaction" class="button-secondary action" />
                        </div>
            		<br class="clear" />
          			</div>
                    <div class="clear"></div>
                    <table class="widefat fixed" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col" id="cb" class="manage-column column-cb check-column" style="">&nbsp;</th>
                                <th scope="col" id="name" class="manage-column column-name" style=""><?php echo $current_cycler->image1; ?></th>
                                <th scope="col" id="description" class="manage-column column-description" style=""><?php echo $current_cycler->text1; ?></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th scope="col" id="cb" class="manage-column column-cb check-column" style="">&nbsp;</th>
                                <th scope="col" id="name" class="manage-column column-name" style=""><?php echo $current_cycler->image1; ?></th>
                                <th scope="col" id="description" class="manage-column column-description" style=""><?php echo $current_cycler->text1; ?></th>
                            </tr>
                        </tfoot>
                        <tbody id="slide-list">
                            <?php if (isset($slides) && (sizeof($slides) > 0)) : ?>
                                <?php foreach($slides as $slide) : ?>
                                    <tr id='cat-1' class='iedit alternate'>
                                        <th scope='row' class='check-column'><input name="pacycler_delete[]" value="<?php echo $slide->slide_id; ?>" type="checkbox" /></th>
                                        <td class="name column-name">
                                            <a href="<?php echo PACYCLER_OPTIONSPAGEURL; ?>&view=slide&pacycler_slideid=<?php echo $slide->slide_id; ?>"><img class="slide" src="<?php echo PACYCLER_IMAGEUPLOADURL . $slide->image1; ?>" alt="<?php echo $slide->image1; ?>" /></a>
                                            <br />
                                            <div class="row-actions">
                                                <span class='edit'>
                                                    <a href="<?php echo PACYCLER_OPTIONSPAGEURL; ?>&view=slide&pacycler_id=<?php echo $slide->cycler_id; ?>&pacycler_slideid=<?php echo $slide->slide_id; ?>">Edit</a> | 
                                                    <a href="<?php echo PACYCLER_OPTIONSPAGEURL; ?>&view=slide&pacycler_id=<?php echo $slide->cycler_id; ?>&pacycler_action=delete&pacycler_slideid=<?php echo $slide->slide_id; ?>">Delete</a>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="description column-description">
                                            <?php echo $slide->text1; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="3">No slides listed for this cycler.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </form>
              </div>
            </div>
            <!-- /col-right -->
            <div id="col-left">
                <div class="col-wrap">
                
                    <div id="pabc-edit-slide" class="form-wrap">
                        <h3><?php echo ($slide_id ? 'Edit' : 'Add'); ?> Slide</h3>
                
                        <?php if ($cycler_id || $slide_id) : ?>
                        
                            <form method="post" enctype="multipart/form-data" action="<?php $_SERVER['REQUEST_URI']; ?>">
    							<input type="hidden" name="pacycler_postback" value="1" />
                                <input type="hidden" name="action" value="pacycler_addslide" />
                                <?php for($i=0; $i<PACYCLER_NUMTEXTFIELDS; $i++) : ?>
									<?php if ($current_cycler->{'text' . ($i+1)}) : ?>
                                        <div class="form-field form-required">
                                            <label for="pacycler_text<?php echo ($i+1); ?>"><?php echo $current_cycler->{'text' . ($i+1)}; ?></label>
                                            <input name="pacycler_text<?php echo ($i+1); ?>" id="pacycler_text<?php echo $i; ?>" type="text" 
                                                value="<?php echo htmlspecialchars((isset($current_slide) ? $current_slide->{'text' . ($i+1)} : '')); ?>" size="40" />
                                        </div>
                                   	<?php endif; ?>
                                <?php endfor; ?>
                                
                                <?php for($i=0; $i<PACYCLER_NUMIMAGEFIELDS; $i++) : ?>
									<?php if ($current_cycler->{'image' . ($i+1)}) : ?>
                                        <div class="form-field form-required">
                                            <label for="pacycler_image<?php echo ($i+1); ?>"><?php echo $current_cycler->{'image' . ($i+1)}; ?></label>
                                            <input name="pacycler_image<?php echo ($i+1); ?>" id="pacycler_image<?php echo ($i+1); ?>" type="file" />
                                            <?php if ($current_slide->{'image' . ($i+1)}) : ?>
                                                <img class="slide" src="<?php echo PACYCLER_IMAGEUPLOADURL . $current_slide->{'image' . ($i+1)}; ?>" alt="" />
                                            <?php endif; ?>
                                        </div>
                                	<?php endif; ?>
                                <?php endfor; ?>
                                
                                <div class="form-field form-required">
                                	<label for="pacycler_order">Slide Order</label>
                                	<input style="width:30px;" name="pacycler_slide_order" type="text" value="<?php echo (isset($current_slide) ? $current_slide->display_order : 0); ?>"/>
                                	<span class="note">Lower slide numbers appear first.</span>
                                </div>
                                <p class="submit">
                                  <input type="submit" class="button" name="pacycler_saveslide" value="Save Slide" />
                                </p>
                            </form>
                        <?php else : ?>
                        	Select a cycler from the menu on the right to add a slide.
                        <?php endif; ?>
                    </div>
                </div>
            </div><!-- /col-left -->
       	</div><!-- /col-container -->
   	</div><!-- /wrap -->
</div>
