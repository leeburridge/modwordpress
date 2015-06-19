<?php
/*
* WordPress Bridge v1.1
*
* (C)2011 Lee Burridge
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author Lee Burridge <lee@leeandgrace.co.uk>
*  @copyright  2011 Lee Burridge
*
* Changelog
* v1.1 06/10/2011
*	Added blog page support to extend on the current system. This allows a CMS page to be included above the WordPress entries
* v1.0 06/10/2011
* 	First Release
*/

if ( !defined( '_PS_VERSION_' ) )
  exit;

class modwordpress extends Module
  {
	public function __construct()
    {
		$this->name = 'modwordpress';
		$this->tab = 'WPBridge';
		$this->version = 1.0;
		$this->author = 'Lee Burridge';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l( 'WordPress Bridge' );
		$this->description = $this->l( 'Display latest WordPress entries. Perfect for news etc.' );
    }

	public function install()
	{
		// Create a config variable with the default values to store the setting in
		Configuration::updateValue('PS_WPBTITLE', 'News Blog');
		Configuration::updateValue('PS_WPBTABLE', 'wp_posts'); 
		Configuration::updateValue('PS_WPBQTY', '3');
		Configuration::updateValue('PS_WPBCMSID', '1');
		Configuration::updateValue('PS_WPBCMSCLASS', 'block products_block');
		Configuration::updateValue('PS_WPBWPCLASS', 'block products_block');
		Configuration::updateValue('PS_WPBCMSFOOTCLASS', 'block products_block');
		Configuration::updateValue('PS_WPBCMSFOOTID', '1');
		return ( parent::install() );
	}

	public function uninstall()
	{
		// Uninstall just does a basic install right now
		parent::uninstall();
	}

	public function hookhome( $params )
	  {
	  global $cookie, $smarty;
	  
	  // Check what is configured as the WordPress posts table (Look it up in the PS_CONFIGURATION table)
	  $wpbtable = Configuration::get('PS_WPBTABLE', $id_lang = NULL);
	  $wpbqty = Configuration::get('PS_WPBQTY', $id_lang = NULL);
	  $wpbtitle = Configuration::get('PS_WPBTITLE', $id_lang = NULL);
	  $wpbcmsid = Configuration::get('PS_WPBCMSID', $id_lang = NULL);
	  $wpbcmsclass = Configuration::get('PS_WPBCMSCLASS', $id_lang = NULL);
	  $wpbwpclass = Configuration::get('PS_WPBWPCLASS', $id_lang = NULL);
	  $wpbcmsfootclass = Configuration::get('PS_WPBCMSFOOTCLASS', $id_lang = NULL);
	  $wpbcmsfootid = Configuration::get('PS_WPBCMSFOOTID', $id_lang = NULL);
	  
	  $sql = "SELECT * FROM ".$wpbtable." where `post_status`='publish' ORDER BY ID DESC";
	  
	  $_cms = Db::getInstance()->ExecuteS($sql);
		$i=0;
		foreach($_cms as $cms)
		{
			$news[$i] = $cms['post_title'];
			$ndata[$i] = $cms['post_content'];
			$pdate[$i] = date("d/m/Y", strtotime($cms['post_date']));
			$i++;
			}
		
		$i=0;
		do {
			$count[$i] = $i;
			$i++;
		} while ($i < $wpbqty);
		
		// CMS Header Page
		if ($wpbcmsid <> "") {
			// Load the CMS page data
			$icms = new CMS((int)$wpbcmsid, intval($cookie->id_lang)); 
			if (Validate::isLoadedObject($icms)) 
				$smarty->assign('content', $icms->content); 
		}
		
		// CMS Footer Page
		if ($wpbcmsfootid <> "") {
			// Load the CMS page data
			$cms = new CMS((int)$wpbcmsfootid, intval($cookie->id_lang)); 
			if (Validate::isLoadedObject($cms)) 
				//$smarty->assign('content2', $cms->content2); 
				$smarty->assign('content2', $cms->content);
		}
		
		// I know this is messy but it works and the overheads are minimal.
		$smarty->assign('news', $news);
		$smarty->assign('ndata', $ndata);
		$smarty->assign('pdate', $pdate);
		$smarty->assign('wpbqty', $wpbqty);
		$smarty->assign('count', $count);
		$smarty->assign('wpbtitle', $wpbtitle);
		$smarty->assign('wpbcmsid', $wpbcmsid);
		$smarty->assign('wpbcmsclass', $wpbcmsclass);
		$smarty->assign('wpbwpclass', $wpbwpclass);
		$smarty->assign('wpbcmsfootclass', $wpbcmsfootclass);
		$smarty->assign('wpbcmsfootid', $wpbcmsfootid);
		
		// Send the output
		return $this->display( __FILE__, 'modwordpress.tpl' );
		
	}
	
	public function getContent()
	{
		$output = '<h2>'.$this->displayName.'</h2>';

		return $output.$this->displayForm();
	}	
	
	public function displayform()
	{
		$output = '';
		
		// If it's been submitted we need to update the variables.
		if ( $_POST['frmsave'] == 'Save Changes' )
			{
			// Get the variables that were submitted.
			$newtable = $_POST['txttable'];
			$newqty = $_POST['txtnum'];
			$newtitle = $_POST['txttitle'];
			$newcmsid = $_POST['txtcmsid'];
			$newcmsclass = $_POST['txtcmsclass'];
			$newwpclass = $_POST['txtwpclass'];
			$newcmsfootclass = $_POST['txtcmsfootclass'];
			$newcmsfootid = $_POST['txtcmsfootid'];
		
			// Save them
			Configuration::updateValue('PS_WPBTABLE', $newtable); 
			Configuration::updateValue('PS_WPBQTY', $newqty);
			Configuration::updateValue('PS_WPBTITLE', $newtitle);
			Configuration::updateValue('PS_WPBCMSID', $newcmsid);
			Configuration::updateValue('PS_WPBCMSCLASS', $newcmsclass);
			Configuration::updateValue('PS_WPBWPCLASS', $newwpclass);
			Configuration::updateValue('PS_WPBCMSFOOTCLASS', $newcmsfootclass);
			Configuration::updateValue('PS_WPBCMSFOOTID', $newcmsfootid);
			
			$output .= '<table border=1 bordercolor=red width=100%><tr><td>';
			$output .= '<center><font color=red><B>The settings have been updated.</b></font></center>';
			$output .= '</td></tr></table>';
			$output .= '<br><br>';
			}
		
		// Get the config and display it for editing.
		$poststable = Configuration::get('PS_WPBTABLE', $id_lang = NULL);
		$postsqty = Configuration::get('PS_WPBQTY', $id_lang=NULL);
		$posttitle = Configuration::get('PS_WPBTITLE', $id_lang=NULL);
		$postcmsid = Configuration::get('PS_WPBCMSID', $id_lang=NULL);
		$postcmsclass = Configuration::get('PS_WPBCMSCLASS', $id_lang=NULL);
		$postwpclass = Configuration::get('PS_WPBWPCLASS', $id_lang=NULL);
		$postcmsfootclass = Configuration::get('PS_WPBCMSFOOTCLASS', $id_lang=NULL);
		$postcmsfootid = Configuration::get('PS_WPBCMSFOOTID', $id_lang=NULL);
		
		$output .= 'This module allows you to integrate your Wordpress posts into PrestaShop.<BR>';
		$output .= '<B>NOTE : You need to have your WordPress tables installed in your PrestaShop table</B>';
		$output .= '<BR><BR>';
		$output .= '<form action="'.$_SERVER['REQUEST_URI'].'" method="post">';
		$output .= '<table width=100%>';
		$output .= '<tr><td colspan=2><h3>Main Settings</h3></td></tr>';
		$output .= '<tr><td>';
		$output .= 'Module Display Title ';
		$output .= "</td><td><input type=text name=txttitle size=30 value = '".$posttitle."'></td><tr>";
		$output .= '<td>';
		$output .= 'WordPress Posts Table ';
		$output .= '</td><td><input type=text name=txttable value = '.$poststable.'></td><tr>';
		$output .= '<tr><td>';
		$output .= 'Qty';
		$output .= '</td><td><input type=text name=txtnum size=2 value = '.$postsqty.'>';
		$output .= '</td></tr>';
		$output .= '<tr><td colspan=2><hr></td></tr>';
		$output .= '<tr><td colspan=2><h3>CMS Header Page Settings</h3></td></tr>';
		$output .= '<tr><td>';
		$output .= 'CMS ID (Leave blank for none)';
		$output .= '</td><td><input type=text name=txtcmsid size=2 value = '.$postcmsid.'>';
		$output .= '</td></tr>';
		$output .= '<tr><td>';
		$output .= 'CMS Class (Leave blank for none)';
		$output .= "</td><td><input type=text name=txtcmsclass size=20 value = '".$postcmsclass."'>";
		$output .= '</td></tr>';
		$output .= '<tr><td colspan=2><hr></td></tr>';
		$output .= '<tr><td colspan=2><h3>Wordpress Settings</h3></td></tr>';
		$output .= '<tr><td>';
		$output .= 'WordPress Class (Leave blank for none)';
		$output .= "</td><td><input type=text name=txtwpclass size=20 value = '".$postwpclass."'>&nbsp&nbsp<font color=red>*</font> Recommanded setting 'block products_block'";
		$output .= '</td></tr>';
		$output .= '<tr><td colspan=2><hr></td></tr>';
		$output .= '<tr><td colspan=2><h3>CMS Footer Page</h3></td></tr>';
		$output .= '<tr><td>';
		$output .= 'CMS Footer ID (Leave blank for none)';
		$output .= '</td><td><input type=text name=txtcmsfootid size=2 value = '.$postcmsfootid.'>';
		$output .= '</td></tr>';
		$output .= '<tr><td>';
		$output .= 'CMS Footer Class (Leave blank for none)';
		$output .= "</td><td><input type=text name=txtcmsfootclass size=20 value = '".$postcmsfootclass."'>";
		$output .= '</td></tr>';
		$output .= '</table>';
		$output .= "<input type=submit name=frmsave value='Save Changes'>";
		$output .= '</form>';
		
		return $output;
	}
}
?>
