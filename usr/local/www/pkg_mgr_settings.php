<?php
/* $Id$ */
/*
	pkg_mgr_settings.php
	part of pfSense
	Copyright (C) 2009 Jim Pingle <jimp@pfsense.org>
    Copyright (C) 2004-2010 Scott Ullrich <sullrich@gmail.com>
        Copyright (C) 2005 Colin Smith

	Redistribution and use in source and binary forms, with or without
	modification, are permitted provided that the following conditions are met:

	1. Redistributions of source code must retain the above copyright notice,
	   this list of conditions and the following disclaimer.

	2. Redistributions in binary form must reproduce the above copyright
	   notice, this list of conditions and the following disclaimer in the
	   documentation and/or other materials provided with the distribution.

	THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES,
	INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
	AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
	AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
	OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
	SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
	INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
	CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
	ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
	POSSIBILITY OF SUCH DAMAGE.
*/
/*
	pfSense_MODULE:	pkgs
*/

##|+PRIV
##|*IDENT=page-pkg-mgr-settings
##|*NAME=Packages: Settings page
##|*DESCR=Allow access to the 'Packages: Settings' page.
##|*MATCH=pkg_mgr_settings.php*
##|-PRIV

ini_set('max_execution_time', '0');

require_once("globals.inc");
require_once("guiconfig.inc");
require_once("pkg-utils.inc");

if ($_POST) {
	if (!$input_errors) {
		if($_POST['alturlenable'] == "yes") {
			$config['system']['altpkgrepo']['enable'] = true;
			$config['system']['altpkgrepo']['xmlrpcbaseurl'] = $_POST['pkgrepourl'];
		} else {
			unset($config['system']['altpkgrepo']['enable']);
		}
		write_config();
	}
}

$curcfg = $config['system']['altpkgrepo'];

$pgtitle = array(gettext("System"),gettext("Package Settings"));
include("head.inc");
?>
<script language="JavaScript">
<!--


function enable_altpkgrepourl(enable_over) {
	if (document.iform.alturlenable.checked || enable_over) {
		document.iform.pkgrepourl.disabled = 0;
	} else {
		document.iform.pkgrepourl.disabled = 1;
	}
}

// -->
</script>

<body link="#0000CC" vlink="#0000CC" alink="#0000CC">
<?php include("fbegin.inc");?>
<?php if ($input_errors) print_input_errors($input_errors); ?>

<form action="pkg_mgr_settings.php" method="post" name="iform" id="iform">
            <?php if ($savemsg) print_info_box($savemsg); ?>
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
<?php
	$version = file_get_contents("/etc/version");
	$tab_array = array();
	$tab_array[] = array(sprintf(gettext("%s packages"), $version), false, "pkg_mgr.php");
	$tab_array[] = array(gettext("Installed Packages"), false, "pkg_mgr_installed.php");
	$tab_array[] = array(gettext("Package Settings"), true, "pkg_mgr_settings.php");
	display_top_tabs($tab_array);
?>
		</td>
	</tr>
	<tr><td><div id=mainarea>
	      <table class="tabcont" width="100%" border="0" cellpadding="6" cellspacing="0">
	<tr>
		<td colspan="2" valign="top" class="listtopic"><?=gettext("Package Repository URL");?></td>
	</tr>
	<tr>
		<td valign="top" class="vncell"><?=gettext("Package Repository URL");?></td>
		<td class="vtable">
			<input name="alturlenable" type="checkbox" id="alturlenable" value="yes" onClick="enable_altpkgrepourl()" <?php if(isset($curcfg['enable'])) echo "checked"; ?>> <?=gettext("Use a different URL server for packages other than");?> <?php echo $g['product_website']; ?><br>
			<table>
			<tr><td><?=gettext("Base URL:");?></td><td><input name="pkgrepourl" type="input" class="formfld url" id="pkgrepourl" size="64" value="<?php if($curcfg['xmlrpcbaseurl']) echo $curcfg['xmlrpcbaseurl']; else echo $g['']; ?>"></td></tr>
			</table>
			<span class="vexpl">
				<?php printf(gettext("This is where %s will check for packages when the"),$g['product_name']);?>, <a href="pkg_mgr.php"><?=gettext("System: Packages");?></a> <?=gettext("page is viewed.");?>
				</span>
				</td>
	</tr>
	<script>enable_altpkgrepourl();</script>
                <tr>
                  <td width="22%" valign="top">&nbsp;</td>
                  <td width="78%">
                    <input name="Submit" type="submit" class="formbtn" value="<?=gettext("Save");?>">
                  </td>
                </tr>
              </table></div></td></tr></table>
</form>
<?php include("fend.inc"); ?>
</body>
</html>