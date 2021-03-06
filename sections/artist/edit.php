<?
/************************************************************************
||------------|| Edit artist wiki page ||------------------------------||

This page is the page that is displayed when someone feels like editing 
an artist's wiki page.

It is called when $_GET['action'] == 'edit'. $_GET['artistid'] is the 
ID of the artist, and must be set.

************************************************************************/

$ArtistID = $_GET['artistid'];
if(!is_number($ArtistID)) { error(0); }

// Get the artist name and the body of the last revision
$DB->query("SELECT
	Name,
	Image,
	Body,
	VanityHouse
	FROM artists_group AS a
	LEFT JOIN wiki_artists ON wiki_artists.RevisionID=a.RevisionID
	WHERE a.ArtistID='$ArtistID'");

if($DB->record_count() < 1) {
	error("Cannot find the artist with the ID ".$ArtistID.': See the <a href="log.php?search=Artist+'.$ArtistID.'">log</a>.');
}

list($Name, $Image, $Body, $VanityHouse) = $DB->next_record(MYSQLI_NUM, true);

// Start printing form
show_header('Edit artist');
?>
<div class="thin">
	<div class="header">
		<h2>Edit <a href="artist.php?id=<?=$ArtistID?>"><?=$Name?></a></h2>
	</div>
	<div class="box pad">
		<form class="edit_form" name="artist" action="artist.php" method="post">
			<input type="hidden" name="action" value="edit" />
			<input type="hidden" name="auth" value="<?=$LoggedUser['AuthKey']?>" />
			<input type="hidden" name="artistid" value="<?=$ArtistID?>" />
			<div>
				<h3>Image</h3>
				<input type="text" name="image" size="92" value="<?=$Image?>" /><br />
				<h3>Artist info</h3>
				<textarea name="body" cols="91" rows="20"><?=$Body?></textarea> <br />
				<h3>Vanity House <input type="checkbox" name="vanity_house" value="1"  <?=( check_perms('artist_edit_vanityhouse') ? '' : 'disabled="disabled"' )?> <?=($VanityHouse ? 'checked="checked"' : '')?> /></h3>
				<h3>Edit summary</h3>
				<input type="text" name="summary" size="92" /><br />
				<div style="text-align: center;">
					<input type="submit" value="Submit" />
				</div>
			</div>
		</form>
	</div>
<? if(check_perms('torrents_edit')) { ?> 
	<h2>Rename</h2>
	<div class="box pad">
		<form class="rename_form" name="artist" action="artist.php" method="post">
			<input type="hidden" name="action" value="rename" />
			<input type="hidden" name="auth" value="<?=$LoggedUser['AuthKey']?>" />
			<input type="hidden" name="artistid" value="<?=$ArtistID?>" />
			<div>
				<input type="text" name="name" size="92" value="<?=$Name?>" />
				<div style="text-align: center;">
					<input type="submit" value="Rename" />
				</div>
				
			</div>
		</form>
	</div>
	
	<h2>Make into non-redirecting alias</h2>
	<div class="box pad">
		<form class="merge_form" name="artist" action="artist.php" method="post">
			<input type="hidden" name="action" value="change_artistid" />
			<input type="hidden" name="auth" value="<?=$LoggedUser['AuthKey']?>" />
			<input type="hidden" name="artistid" value="<?=$ArtistID?>" />
			<div>
				<em>Merges this artist (<?=$Name?>) into the artist specified below (without redirection), so that "<?=$Name?>" (and its aliases) will appear as a non-redirecting alias of the artist entered in the text box below.</em><br /><br />
				<div style="text-align: center;">
					<label for="newartistid">ArtistID:</label>&nbsp;<input type="text" id="newartistid" name="newartistid" size="40" value="" /><br />
					<strong>OR</strong><br />
					<label for="newartistid">Artist Name:</label>&nbsp;<input type="text" id="newartistname" name="newartistname" size="40" value="" />
					<br /><br />
					<input type="submit" value="Change ArtistID" />
				</div>
			</div>
		</form>
	</div>
	
	<h2>Aliases</h2>
	<div class="box pad">
		<ul>
		
<?
	$DB->query("SELECT AliasID, Name, UserID, Redirect FROM artists_alias WHERE ArtistID='$ArtistID'");
	while(list($AliasID, $AliasName, $User, $Redirect) = $DB->next_record(MYSQLI_NUM, true)) { 
		if($AliasName == $Name) { $DefaultRedirectID = $AliasID; }
?>
			<li><?=$AliasID?>. <?=$AliasName?>
<?		if($User) { ?> <a href="user.php?id=<?=$User?>">User</a>. <?}
		if($Redirect) { ?> (writes redirect to <?=$Redirect?>)<? } ?>
			<a href="artist.php?action=delete_alias&amp;aliasid=<?=$AliasID?>&amp;auth=<?=$LoggedUser['AuthKey']?>">[X]</a>
			</li>
<?	}
?>
		</ul>
		<form class="add_form" name="aliases" action="artist.php" method="post">
			<input type="hidden" name="action" value="add_alias" />
			<input type="hidden" name="auth" value="<?=$LoggedUser['AuthKey']?>" />
			<input type="hidden" name="artistid" value="<?=$ArtistID?>" />
			<h3>Name:</h3>
			<input type="text" name="name" size="40" value="<?=$Name?>" /><br />
			<h3>Writes redirect to (Alias ID, blank for no redirect):</h3>
			<input type="text" name="redirect" size="40" value="<?=$DefaultRedirectID?>" /><br />
			<em>This redirects artist names as they are written, eg. new torrents and added artists. All uses of this new alias will be redirected to the alias ID you enter here. Use for common misspellings, etc.</em><br />
			<input type="submit" value="Add alias" />
		</form>
	</div>
<? } ?> 
</div>
<? show_footer() ?>