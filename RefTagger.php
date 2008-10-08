<?php
/* 
Plugin Name: RefTagger
Plugin URI: http://www.logos.com/reftagger
Description: Transform Bible references into links to the full text of the verse.
Author: Logos Bible Software
Version: 1.3
Author URI: http://www.logos.com
*/

function lbsFooter($unused)
{
	$bible_version = get_option('lbs_bible_version');
	$libronix = get_option('lbs_libronix');
	$existing_libronix = get_option('lbs_existing_libronix');
	$link_color = get_option('lbs_libronix_color');
	$tooltips = get_option('lbs_tooltips');
	$search_comments = get_option('lbs_search_comments');
	$nosearch = get_option('lbs_nosearch');
	$new_window = get_option('lbs_new_window');
	$first = true;
	$libronix_bible_version = get_option('lbs_libronix_bible_version');

	// Generate the script code to be printed on the page
	?><script src="http://bible.logos.com/jsapi/Referencetagging.js" type="text/javascript"></script>
	
		<script type="text/javascript">
			Logos.ReferenceTagging.lbsBibleVersion = "<?php echo $bible_version;?>";
			Logos.ReferenceTagging.lbsLibronixBibleVersion = "<?php echo $libronix_bible_version;?>";
			<?php if($libronix == 1) echo 'Logos.ReferenceTagging.lbsAddLibronixDLSLink = true;';?>
			<?php if($existing_libronix == 1) echo 'Logos.ReferenceTagging.lbsAppendIconToLibLinks = true;';?>
			Logos.ReferenceTagging.lbsLibronixLinkIcon = "<?php echo $link_color;?>";
			<?php if($search_comments != 1) echo 'Logos.ReferenceTagging.lbsNoSearchClassNames = [ "commentlist" ];';?>
			<?php if($tooltips != 1) echo 'Logos.ReferenceTagging.lbsUseTooltip = false;';?>
			Logos.ReferenceTagging.lbsNoSearchTagNames = [ <?php foreach($nosearch as $tagname => $value)
			{
				if($value == '1')
				{
					if($first)
						$first = false;
					else
						echo ', ';
						
					echo '"'.$tagname.'"';
				}
			}?> ];
			<?php if($new_window == 1) echo 'Logos.ReferenceTagging.lbsLinksOpenNewWindow = true;';?>
			Logos.ReferenceTagging.tag();
		</script><?php
}

// Register the user preferences when the plugin is enabled
function lbs_set_options()
{
	add_option('lbs_bible_version', 'ESV', 'Which Bible version to use');
	add_option('lbs_libronix', 'false', 'Insert Libronix links');
	add_option('lbs_existing_libronix', 'false', 'Insert Libronix icon after existing Libronix links');
	add_option('lbs_libronix_color', 'dark', 'Color of Libronix link icons');
	add_option('lbs_tooltips', '1', 'Show a tooltip containing verse text when the mouse hovers over a reference');
	add_option('lbs_search_comments', '1', 'Whether or not to search user comments');
	$default_nosearch = array('h1' => "1",
							  'h2' => "1",
							  'h3' => "1");
	add_option('lbs_nosearch', $default_nosearch, 'List of HTML tags that will not be searched');
	add_option('lbs_new_window', '0', 'Whether or not to open links in a new window');
	add_option('lbs_libronix_bible_version', 'ESV', 'Which Bible version to use with Libronix links');
}

// Remove the user preferences when the plugin is disabled
function lbs_unset_options()
{
	delete_option('lbs_bible_version');
	delete_option('lbs_libronix');
	delete_option('lbs_existing_libronix');
	delete_option('lbs_libronix_color');
	delete_option('lbs_tooltips');
	delete_option('lbs_search_comments');
	delete_option('lbs_nosearch');
	delete_option('lbs_new_window');
	delete_option('lbs_libronix_bible_version');
}

// The options page
function lbs_admin_options()
{
	?><div class="wrap"><h2>RefTagger Options</h2><?php
	
	// If the user clicked submit, update the preferences
	if($_REQUEST['submit'])
	{
		lbs_update_options();
	}
	
	// Print the options page
	lbs_options_page();
	
	?></div><?php
}

// Update any preferences the user has changed
function lbs_update_options()
{
	$changed = false;
	$old_libronix = get_option('lbs_libronix');
	$existing_libronix = get_option('lbs_existing_libronix');
	$old_comments = get_option('lbs_search_comments');
	$nosearch = get_option('lbs_nosearch');
	$window = get_option('lbs_new_window');
	$old_tooltips = get_option('lbs_tooltips');
	
	if($_REQUEST['lbs_bible_version'])
	{
		update_option('lbs_bible_version', $_REQUEST['lbs_bible_version']);
		$changed = true;
	}
	
		if($_REQUEST['lbs_libronix_bible_version'])
	{
		update_option('lbs_libronix_bible_version', $_REQUEST['lbs_libronix_bible_version']);
		$changed = true;
	}
	
	
	if($_REQUEST['lbs_libronix'] != $old_libronix)
	{
		update_option('lbs_libronix', $_REQUEST['lbs_libronix']);
		$changed = true;
	}
	
	if($_REQUEST['lbs_existing_libronix'] != $existing_libronix)
	{
		update_option('lbs_existing_libronix', $_REQUEST['lbs_existing_libronix']);
		$changed = true;
	}
	
	if($_REQUEST['lbs_libronix_color'])
	{
		update_option('lbs_libronix_color', $_REQUEST['lbs_libronix_color']);
		$changed = true;
	}
	
	if($_REQUEST['lbs_tooltips'] != $old_tooltips)
	{
		update_option('lbs_tooltips', $_REQUEST['lbs_tooltips']);
		$changed = true;
	}
	
	if($_REQUEST['lbs_search_comments'] != $old_comments)
	{
		update_option('lbs_search_comments', $_REQUEST['lbs_search_comments']);
		$changed = true;
	}
	
	if($_REQUEST['lbs_new_window'])
	{
		update_option('lbs_new_window', $_REQUEST['lbs_new_window']);
		$changed = true;
	}
	
	if($_REQUEST['lbs_nosearch_h1'] != $nosearch['h1'])
	{
		$nosearch['h1'] = $_REQUEST['lbs_nosearch_h1'];
		update_option('lbs_nosearch', $nosearch);
		$changed = true;
	}
	
	if($_REQUEST['lbs_nosearch_h2'] != $nosearch['h2'])
	{
		$nosearch['h2'] = $_REQUEST['lbs_nosearch_h2'];
		update_option('lbs_nosearch', $nosearch);
		$changed = true;
	}
	if($_REQUEST['lbs_nosearch_h3'] != $nosearch['h3'])
	{
		$nosearch['h3'] = $_REQUEST['lbs_nosearch_h3'];
		update_option('lbs_nosearch', $nosearch);
		$changed = true;
	}
	
	if($_REQUEST['lbs_nosearch_h4'] != $nosearch['h4'])
	{
		$nosearch['h4'] = $_REQUEST['lbs_nosearch_h4'];
		update_option('lbs_nosearch', $nosearch);
		$changed = true;
	}
	if($_REQUEST['lbs_nosearch_h5'] != $nosearch['h5'])
	{
		$nosearch['h5'] = $_REQUEST['lbs_nosearch_h5'];
		update_option('lbs_nosearch', $nosearch);
		$changed = true;
	}
	
	if($_REQUEST['lbs_nosearch_h6'] != $nosearch['h6'])
	{
		$nosearch['h6'] = $_REQUEST['lbs_nosearch_h6'];
		update_option('lbs_nosearch', $nosearch);
		$changed = true;
	}
	
	if($_REQUEST['lbs_nosearch_b'] != $nosearch['b'])
	{
		$nosearch['b'] = $_REQUEST['lbs_nosearch_b'];
		$nosearch['strong'] = $_REQUEST['lbs_nosearch_b'];
		update_option('lbs_nosearch', $nosearch);
		$changed = true;
	}
	
	if($_REQUEST['lbs_nosearch_i'] != $nosearch['i'])
	{
		$nosearch['i'] = $_REQUEST['lbs_nosearch_i'];
		$nosearch['em'] = $_REQUEST['lbs_nosearch_i'];
		update_option('lbs_nosearch', $nosearch);
		$changed = true;
	}
	if($_REQUEST['lbs_nosearch_u'] != $nosearch['u'])
	{
		$nosearch['u'] = $_REQUEST['lbs_nosearch_u'];
		update_option('lbs_nosearch', $nosearch);
		$changed = true;
	}
	
	if($_REQUEST['lbs_nosearch_ol'] != $nosearch['ol'])
	{
		$nosearch['ol'] = $_REQUEST['lbs_nosearch_ol'];
		update_option('lbs_nosearch', $nosearch);
		$changed = true;
	}
	if($_REQUEST['lbs_nosearch_ul'] != $nosearch['ul'])
	{
		$nosearch['ul'] = $_REQUEST['lbs_nosearch_ul'];
		update_option('lbs_nosearch', $nosearch);
		$changed = true;
	}
	
	if($_REQUEST['lbs_nosearch_span'] != $nosearch['span'])
	{
		$nosearch['span'] = $_REQUEST['lbs_nosearch_span'];
		update_option('lbs_nosearch', $nosearch);
		$changed = true;
	}	
	if($changed)
	{
		?><div id="message" class="updated fade">
			<p>Settings Saved.</p>
		</div><?php
	}
}

// Print the options page
function lbs_options_page()
{
	$selected_version = get_option('lbs_bible_version');
	$selected_libronix = get_option('lbs_libronix');
	$selected_existing_libronix = get_option('lbs_existing_libronix');
	$selected_color = get_option('lbs_libronix_color');
	$selected_tooltips = get_option('lbs_tooltips');
	$selected_nosearch = get_option('lbs_nosearch');
	$selected_comments = get_option('lbs_search_comments');
	$selected_window = get_option('lbs_new_window');
	$selected_lib_version = get_option('lbs_libronix_bible_version');
	
	?>
	<form method="post">
	
		<table class="form-table">
		<tr valign="top">
		<th scope="row">Bible version:</th>
		<td>
			<select name="lbs_bible_version">
				<option value="NIV" <?php if ($selected_version == 'NIV') { print 'selected="SELECTED"'; } ?>>NIV</option>
				<option value="NASB" <?php if ($selected_version == 'NASB') { print 'selected="SELECTED"'; } ?>>NASB</option>
				<option value="KJV" <?php if ($selected_version == 'KJV') { print 'selected="SELECTED"'; } ?>>KJV</option>
				<option value="ESV" <?php if ($selected_version == 'ESV') { print 'selected="SELECTED"'; } ?>>ESV</option>
				<option value="ASV" <?php if ($selected_version == 'ASV') { print 'selected="SELECTED"'; } ?>>ASV</option>
				<option value="NLT" <?php if ($selected_version == 'NLT') { print 'selected="SELECTED"'; } ?>>NLT</option>
				<option value="NKJV" <?php if ($selected_version == 'NKJV') { print 'selected="SELECTED"'; } ?>>NKJV</option>
				<option value="YLT" <?php if ($selected_version == 'YLT') { print 'selected="SELECTED"'; } ?>>YLT</option>
				<option value="DAR" <?php if ($selected_version == 'DAR') { print 'selected="SELECTED"'; } ?>>DARBY</option>
				<option value="NIRV" <?php if ($selected_version == 'NIRV') { print 'selected="SELECTED"'; } ?>>NIRV</option>
				<option value="TNIV" <?php if ($selected_version == 'TNIV') { print 'selected="SELECTED"'; } ?>>TNIV</option>
			</select>
		</td>
		</tr>
		<tr valign="middle">
		<th scope="row">Links open in:</th>
		<td>
		<input name="lbs_new_window" value="0" id="lbs_new_window0" style="vertical-align: middle" type="radio" <?php if ($selected_window == '0') { print 'checked="CHECKED"'; } ?>><label for="lbs_new_window0">&nbsp;Existing window</label>
		<br/>
		<input name="lbs_new_window" value="1" id="lbs_new_window1" style="vertical-align: middle" type="radio" <?php if ($selected_window == '1') { print 'checked="CHECKED"'; } ?>>
		<label for="lbs_new_window1">&nbsp;New window</label>
		</td>
		</tr>
		<tr valign="middle">
		<th scope="row">Insert Libronix links:</th>
		<td>
		<input name="lbs_libronix" value="1" id="lbs_libronix" type="checkbox" <?php if ($selected_libronix == '1') { print 'checked="CHECKED"'; } ?>>
		<label for="lbs_libronix">&nbsp;Insert a small icon linking to the verse in <a href="http://www.logos.com/products/ldls">Libronix DLS</a></label>
		<br/>
		<input name="lbs_existing_libronix" value="1" id="lbs_existing_libronix" type="checkbox" <?php if ($selected_existing_libronix == '1') { print 'checked="CHECKED"'; } ?>>
		<label for="lbs_existing_libronix">&nbsp;Add a Libronix icon to previously existing Libronix links</label>
		</td>
		</tr>
		<tr valign="top">
		<th scope="row">Libronix Bible version:</th>
		<td>
			<select name="lbs_libronix_bible_version">
				<option value="DEFAULT" <?php if ($selected_lib_version == 'DEFAULT') { print 'selected="SELECTED"'; } ?>>User's Default</option>
				<option value="NIV" <?php if ($selected_lib_version == 'NIV') { print 'selected="SELECTED"'; } ?>>NIV</option>
				<option value="NASB95" <?php if ($selected_lib_version == 'NASB95') { print 'selected="SELECTED"'; } ?>>NASB95</option>
				<option value="NASB" <?php if ($selected_lib_version == 'NASB') { print 'selected="SELECTED"'; } ?>>NASB77</option>
				<option value="KJV" <?php if ($selected_lib_version == 'KJV') { print 'selected="SELECTED"'; } ?>>KJV</option>
				<option value="ESV" <?php if ($selected_lib_version == 'ESV') { print 'selected="SELECTED"'; } ?>>ESV</option>
				<option value="ASV" <?php if ($selected_lib_version == 'ASV') { print 'selected="SELECTED"'; } ?>>ASV</option>
				<option value="MESSAGE" <?php if ($selected_lib_version == 'MESSAGE') { print 'selected="SELECTED"'; } ?>>MSG</option>
				<option value="NRSV" <?php if ($selected_lib_version == 'NRSV') { print 'selected="SELECTED"'; } ?>>NRSV</option>
				<option value="AMP" <?php if ($selected_lib_version == 'AMP') { print 'selected="SELECTED"'; } ?>>AMP</option>
				<option value="NLT" <?php if ($selected_lib_version == 'NLT') { print 'selected="SELECTED"'; } ?>>NLT</option>
				<option value="CEV" <?php if ($selected_lib_version == 'CEV') { print 'selected="SELECTED"'; } ?>>CEV</option>
				<option value="NKJV" <?php if ($selected_lib_version == 'NKJV') { print 'selected="SELECTED"'; } ?>>NKJV</option>
				<option value="NCV" <?php if ($selected_lib_version == 'NCV') { print 'selected="SELECTED"'; } ?>>NCV</option>
				<option value="KJ21" <?php if ($selected_lib_version == 'KJ21') { print 'selected="SELECTED"'; } ?>>KJ21</option>
				<option value="YLT" <?php if ($selected_lib_version == 'YLT') { print 'selected="SELECTED"'; } ?>>YLT</option>
				<option value="DARBY" <?php if ($selected_lib_version == 'DARBY') { print 'selected="SELECTED"'; } ?>>DARBY</option>
				<option value="ANIV" <?php if ($selected_lib_version == 'ANIV') { print 'selected="SELECTED"'; } ?>>ANIV</option>
				<option value="HCSB" <?php if ($selected_lib_version == 'HCSB') { print 'selected="SELECTED"'; } ?>>HCSB</option>
				<option value="NIRV" <?php if ($selected_lib_version == 'NIRV') { print 'selected="SELECTED"'; } ?>>NIRV</option>
				<option value="TNIV" <?php if ($selected_lib_version == 'TNIV') { print 'selected="SELECTED"'; } ?>>TNIV</option>
			</select>
		</td>
		</tr>
		
		<tr valign="top">
		<th scope="row">Libronix link icon:</th>
		<td>
		<input name="lbs_libronix_color" id="lbs_libronix_color0" value="dark" style="vertical-align: middle" type="radio" <?php if ($selected_color == 'dark') { print 'checked="CHECKED"'; } ?>>
		<label for="lbs_libronix_color0">&nbsp;<img src="http://www.logos.com/images/Corporate/LibronixLink_dark.png"/>&nbsp;Dark (for sites with light backgrounds)</label>
		<br/>
		<input name="lbs_libronix_color" value="light" id="lbs_libronix_color1" style="vertical-align: middle" type="radio" <?php if ($selected_color == 'light') { print 'checked="CHECKED"'; } ?>>
		<label for="lbs_libronix_color1">&nbsp;<img src="http://www.logos.com/images/Corporate/LibronixLink_light.png"/>&nbsp;Light (for sites with dark backgrounds)</label>
		</td>
		</tr>
		<tr style="vertical-align:top">
		<th scope="row">Show ToolTips:</th>
		<td>
		<input name="lbs_tooltips" value="1" id="lbs_tooltips" type="checkbox" <?php if ($selected_tooltips == '1') { print 'checked="CHECKED"'; } ?>>
		<label for="lbs_tooltips">&nbsp;Show a tooltip containing verse text when the mouse hovers over a reference.
		</label>
		</td>
		</tr>
		<tr style="vertical-align:top">
		<th scope="row">Search Options:</th>
		<td>
		
		<input name="lbs_search_comments" value="1" id="lbs_search_comments" type="checkbox" <?php if ($selected_comments == '1') { print 'checked="CHECKED"'; } ?>>
		<label for="lbs_search_comments">&nbsp;Search for Bible references in user comments</label>
		<br/><br/>
		<table>
		<tr>Do not search the following HTML tags</tr>
		<tr>
		<td>
		<input name="lbs_nosearch_b" value="1" id="lbs_nosearch_b" type="checkbox" <?php if ($selected_nosearch['b'] == '1') { print 'checked="CHECKED"'; } ?>>
		<label for="lbs_nosearch_b">&nbsp;Bold</label>
		<br/>
		<input name="lbs_nosearch_i" value="1" id="lbs_nosearch_i" type="checkbox" <?php if ($selected_nosearch['i'] == '1') { print 'checked="CHECKED"'; } ?>>
		<label for="lbs_nosearch_i">&nbsp;Italic</label>
		<br/>
		<input name="lbs_nosearch_u" value="1" id="lbs_nosearch_u" type="checkbox" <?php if ($selected_nosearch['u'] == '1') { print 'checked="CHECKED"'; } ?>>
		<label for="lbs_nosearch_u">&nbsp;Underline</label>
		<br/>
		<input name="lbs_nosearch_ol" value="1" id="lbs_nosearch_ol" type="checkbox" <?php if ($selected_nosearch['ol'] == '1') { print 'checked="CHECKED"'; } ?>>
		<label for="lbs_nosearch_ol">&nbsp;Ordered list</label>
		<br/>
		<input name="lbs_nosearch_ul" value="1" id="lbs_nosearch_ul" type="checkbox" <?php if ($selected_nosearch['ul'] == '1') { print 'checked="CHECKED"'; } ?>>
		<label for="lbs_nosearch_ul">&nbsp;Unordered list</label>
		<br/>
		<input name="lbs_nosearch_span" value="1" id="lbs_nosearch_span" type="checkbox" <?php if ($selected_nosearch['span'] == '1') { print 'checked="CHECKED"'; } ?>>
		<label for="lbs_nosearch_span">&nbsp;Span</label>
		</td>
		<td>
		<input name="lbs_nosearch_h1" value="1" id="lbs_nosearch_h1" type="checkbox" <?php if ($selected_nosearch['h1'] == '1') { print 'checked="CHECKED"'; } ?>>
		<label for="lbs_nosearch_h1">&nbsp;Header 1</label>
		<br/>
		<input name="lbs_nosearch_h2" value="1" id="lbs_nosearch_h2" type="checkbox" <?php if ($selected_nosearch['h2'] == '1') { print 'checked="CHECKED"'; } ?>>
		<label for="lbs_nosearch_h2">&nbsp;Header 2</label>
		<br/>
		<input name="lbs_nosearch_h3" value="1" id="lbs_nosearch_h3" type="checkbox" <?php if ($selected_nosearch['h3'] == '1') { print 'checked="CHECKED"'; } ?>>
		<label for="lbs_nosearch_h3">&nbsp;Header 3</label>
		<br/>
		<input name="lbs_nosearch_h4" value="1" id="lbs_nosearch_h4" type="checkbox" <?php if ($selected_nosearch['h4'] == '1') { print 'checked="CHECKED"'; } ?>>
		<label for="lbs_nosearch_h4">&nbsp;Header 4</label>
		<br/>
		<input name="lbs_nosearch_h5" value="1" id="lbs_nosearch_h5" type="checkbox" <?php if ($selected_nosearch['h5'] == '1') { print 'checked="CHECKED"'; } ?>>
		<label for="lbs_nosearch_h5">&nbsp;Header 5</label>
		<br/>
		<input name="lbs_nosearch_h6" value="1" id="lbs_nosearch_h6" type="checkbox" <?php if ($selected_nosearch['h6'] == '1') { print 'checked="CHECKED"'; } ?>>
		<label for="lbs_nosearch_h6">&nbsp;Header 6</label>
		</td>
		</tr>
		</td>
		</table>
		</table>
		<p class="submit">
			<input type="submit" name="submit" value="Save Changes" />
		</p>
	</form>
	<?php
}

// Add the options page to the menu
function lbs_add_menu()
{
	add_options_page('RefTagger', 'RefTagger', 'manage_options', __FILE__, 'lbs_admin_options');
}

add_action('admin_menu', 'lbs_add_menu');

register_activation_hook(__FILE__, 'lbs_set_options');
register_deactivation_hook(__FILE__, 'lbs_unset_options');

// Run when the footer is generated
add_action('wp_footer', 'lbsFooter');






?>