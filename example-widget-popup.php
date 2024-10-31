<?php
/**
 * Plugin Name: OnlineAfspraken Plugin
 * Plugin URI: http://onlineafspraken.nl
 * Description: De OnlineAfspraken plugin waarmee je het reserveringsframe makkelijk weergeeft.
 * Version: 0.9
 * Author: Martijn van der Molen
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY.
 */
require_once 'oa_widget.php';
require_once 'oa_widget_popup.php';
require_once 'oa_shortcode.php';

add_action( 'widgets_init', 'widgets_init');


function widgets_init() {
        register_widget( 'oa_widget' );
	register_widget( 'oa_widget_popup' );
}

// plugin menu
add_action('admin_menu', 'admin_oa_plugin_menu');


// NAAM AANPASSEN (icon url, en bestand)!!!
// zet de plugin menu link
function admin_oa_plugin_menu(){
    add_menu_page('OnlineAfspraken Plugin', 'OnlineAfspraken', 'administrator', 'oa_plugin_menu', 'admin_oa_plugin', plugins_url('OnlineAfspraken-Wordpres-Plugin/images/icon.jpg'));
    add_submenu_page('oa_plugin_menu', 'OnlineAfspraken upload button', 'OnlineAfspraken uploaden button', 'administrator', 'OnlineAfspraken-Uploaden', 'admin_oa_plugin_upload');
    add_submenu_page('oa_plugin_menu', 'OnlineAfspraken verwijder button', 'OnlineAfspraken verwijderen button', 'administrator', 'OnlineAfspraken-Verwijderen', 'admin_oa_plugin_delete');
}

// admin plugin beneer functie
function admin_oa_plugin(){
    global $wpdb; // $table_name
    
    $path = plugin_dir_url( __FILE__ );
                $dir = $path."images";
    
    //must check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }
    
    // het admin form
   echo'<p>
        <h1>Instellingen OnlineAfspraken Plugin / Widget</h1>
        </p>

        <p>
        <hr />
        <form name="form_install_reset" method="POST" action="'.ploa_install_reset().'">
        <table border="0">
        <tr><td><input type="submit" name="reset" value="Reset" /></td><td><input type="submit" name="reset_all" value="Reset All" /></td></tr>
        </table>
        </form>
        <hr />

       <form name="form_admin" method="POST" action="'.admin_oa_plugin_proces().'">
           <table border="0">
        <tr><td>OnlineAfspraken API key:</td><td><input type="text" name="apikey" value="'.get_option('apikey').'" size="30" /></td><td><img src="'.$dir.'/help.png" title="Vul hier uw apikey van onlineafspraken in."</td></tr>
        ';
        get_logo_oa();
        get_facebook_login_oa();
        get_language_frame_oa();
        get_lettertype_oa();
        get_letter_grote_tekst_oa();
        get_letter_grote_header_oa();
        get_letter_grote_calendar_oa();
    echo'
        <tr><td>Breedte frame:</td><td><input type="text" name="oaframewidth" value="'.get_option('oaframewidth').'" size="8" />px <img src="'.$dir.'/help.png" title="Vul hier de breedte van het frame in, dit werkt niet bij de popup widget."</td></tr>
        <tr><td>Hoogte frame:</td><td><input type="text" name="oaframeheight" value="'.get_option('oaframeheight').'" size="8" />px <img src="'.$dir.'/help.png" title="Vul hier de hoogte van het frame in, dit werkt niet bij de popup widget."</td></tr>
        <tr><td>Selecteer button:</td><td>';
        get_file_oa();
    echo'<img src="'.$dir.'/help.png" title="Kies hier de button die weergegeven moet worden bij de popup widget."</td></tr>';
        get_align_oa();
        get_align_oa_button();
        echo'
        <tr><td></td><td><input type="submit" name="submit" value="Opslaan" /></td></tr>
        </table>
       </form><hr />
       <p>
        Als u het onlineafspraken frame in een pagina op uw website wilt weergeven kunt<br />
        u deze shortcode gebruiken: [onlineafspraken]
        </p>
       ';
}
 
function admin_oa_plugin_upload(){
    echo'<p>
        <h1>Uploaden Button OnlineAfspraken Plugin / Widget</h1>
        </p>
        
        <p>
        <hr />
        <form action="'.uploaden_button_oa().'" method="POST" name="uploaden_button" enctype="multipart/form-data">
        <table border="0">
	<tr><td><label for="file">Upload uw button:</label></td><td><input type="file" name="file" id="file" /></td></tr>
        <tr><td></td><td><input type="submit" name="submit_upload_oa" value="Uploaden" />
	</td></tr>
        </table>
        </form>
        </p><hr />';
}

// delete button
function admin_oa_plugin_delete(){
    
    $path = plugin_dir_path( __FILE__ );
    //List files in images directory
    $dir = $path."oaimg";
    
    $handle = opendir($dir); 
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != ".." && $entry != get_option('img_oa')) {
            //echo "$entry\n";
			$file_array[] = $entry;
        }
    }
    closedir($handle);
    
    if($file_array == NULL){
        echo'
            <p>
            <h1>Verwijder Button OnlineAfspraken Plugin / Widget</h1>
            </p><hr />
            <p>
            <b>Er zijn geen buttons die u kunt verwijderen!</b>
            </p>';
    } else {
    echo'
        <p>
        <h1>Verwijder Button OnlineAfspraken Plugin / Widget</h1>
        </p>
        
        <p>
        <hr />
        <form name="delete_button" action="'.delete_oa_button().'" method="POST">
        ';
        get_file_oa_delete();
        echo'
        </form>
        </p><hr />';
    }
}

// delete button
function delete_oa_button(){
    $path = plugin_dir_path( __FILE__ );
    
    if(isset($_POST['delete_button_oa'])){
        
        if($_POST['chkboxarray'] == NULL){
            echo ('<b>U heeft geen button geselecteerd!</b>');
            die();
        } else {
            foreach($_POST['chkboxarray'] as $checkbox)
            {
                if (!unlink($path.'oaimg/'.$checkbox))
                {
                echo ("<b>Error deleting $checkbox</b><br />");
                }
                else
                {
                echo ("<b>Deleted $checkbox</b><br />");
                }
            }
        }
    }
}

// uploaden button
function uploaden_button_oa(){
    $path = plugin_dir_path( __FILE__ );
    
    if(isset($_POST['submit_upload_oa'])){
    $bestand = $_FILES["file"]["name"];
        
        
        if (($_FILES["file"]["size"] < 5242880));
    else
        {   echo '<b>U kunt alleen bestanden uploaden die kleiner zijn dan 5 mb.</b>';
            die(); }
            
          $allowedExtensions = array("jpg","jpeg","gif","png");
  foreach ($_FILES as $file) {
    if ($file['tmp_name'] > '') {
      if (!in_array(end(explode(".",
            strtolower($file['name']))),
            $allowedExtensions)) {
       die('<b><u>'.$file['name'].'</u> is an invalid file type!<br/>'.'Alleen jpg, gif en png bestanden zijn toegestaan.</b>');
      }
    }
  } 

  if ($_FILES["file"]["error"] > 0)
    {
    echo '<b>Return Code: ' . $_FILES['file']['error'] . '</b>';
    die();
    }

   if (file_exists($path.'oaimg/' . $_FILES['file']['name']))
      {
      echo $_FILES['file']['name'] . ' <b>bestaat al. Geef de <u>button</u> een andere naam.</b>';
      die();
      }
    else
      {
      move_uploaded_file($_FILES["file"]["tmp_name"],
      $path.'oaimg/' . $_FILES["file"]["name"]);
      echo '<b>Het bestand is geupload.</b>';
      }
  }
}

// haalt buttons op voor delete
function get_file_oa_delete(){
        $path = plugin_dir_path( __FILE__ );
        $path_url = plugin_dir_url( __FILE__ );
    //List files in images directory
    $dir = $path."oaimg";
    $dir_url = $path_url."oaimg";
    
    $handle = opendir($dir);
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != ".." && $entry != get_option('img_oa')) {
            //echo "$entry\n";
			$file_array[] = $entry;
        }
    }
    closedir($handle);
    
    if($file_array == NULL){
        echo'
            <p>
            <b>Er zijn geen buttons die u kunt verwijderen!</b>
            </p>';
    } else {
        echo'<table border="0">';
foreach($file_array as $browser) 
	{
		echo '<tr><td><input type="checkbox" name="chkboxarray[]" value="'.$browser.'" />'.$browser.'</td>
                    <td>'; ?><img src="<?php echo $dir_url.'/'.$browser; ?>"/><?php echo'</td></tr>';
	}
        echo'
            <tr><td><input type="submit" name="delete_button_oa" value="Delete" /></td></tr>
            </table>';
    }
}

// haalt buttons op!
function get_file_oa(){
    $path = plugin_dir_path( __FILE__ );
    //List files in images directory
    $dir = $path."oaimg";
    
if ($handle = opendir($dir)) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != ".." && $entry != get_option('img_oa')) {
            //echo "$entry\n";
			$file_array[] = $entry;
        }
    }
    closedir($handle);
	
        echo '<select name="img_oa">
            <option valua="'.get_option('img_oa').'" select="selected">'.get_option('img_oa').'</option>';	
foreach($file_array as $browser) 
	{
		echo '<option value="'.$browser.'">'.$browser.'</option>';
		echo '<br />';
	}
	echo '</select>';
}
}

// haal waardes op (selected)
function get_logo_oa(){
    if(get_option('logo') == '0'){
        echo'<tr><td>OnlineAfspraken logo:</td><td><select name="logo">
        <option value="0" select="selected">Nee</option>
        <option value="1">Ja</option>
        </select></td></tr>';
    } else {
       echo'<tr><td>OnlineAfspraken logo:</td><td><select name="logo">
        <option value="1" select="selected">Ja</option>        
        <option value="0">Nee</option>
        </select></td></tr>';
    }
}

function get_facebook_login_oa(){
    if(get_option('fblogin') == '0'){
        echo'<tr><td>Facebook login:</td><td><select name="fblogin">
        <option value="0" select="selected">Nee</option>
        <option value="1">Ja</option>
        </select</td></tr>';
    } else {
       echo'<tr><td>Facebook login:</td><td><select name="fblogin">
        <option value="1" select="selected">Ja</option>
        <option value="0"">Nee</option>
        </select</td></tr>';
    }
}

function get_language_frame_oa(){
    if(get_option('oalang') == '3'){
        echo'<tr><td>OnlineAfspraken taal:</td><td><select name="oalang">
        <option value="3" select="selected">Nederlands</option>
        <option value="1">Engels</option>
        <option value="4">Frans</option>
        <option value="5">Duits</option>    
        </select></td></tr>';
    } elseif(get_option('oalang') == '1'){
        echo'<tr><td>OnlineAfspraken taal:</td><td><select name="oalang">
        <option value="1" select="selected">Engels</option>        
        <option value="3">Nederlands</option>
        <option value="4">Frans</option>
        <option value="5">Duits</option>    
        </select></td></tr>';
    } elseif(get_option('oalang') == '4'){
        echo'<tr><td>OnlineAfspraken taal:</td><td><select name="oalang">
        <option value="4" select="selected">Frans</option>        
        <option value="1">Engels</option>        
        <option value="3">Nederlands</option>
        <option value="5">Duits</option>    
        </select></td></tr>';
    } else {
       echo'<tr><td>OnlineAfspraken taal:</td><td><select name="oalang">
        <option value="5" select="selected">Duits</option>        
        <option value="3">Nederlands</option>
        <option value="1">Engels</option>
        <option value="4">Frans</option>
        </select></td></tr>';
    }
}

function get_lettertype_oa(){
    if(get_option('oaltype') == '0'){
        echo'<tr><td>Lettertype:</td><td><select name="oaltype">
        <option value="0" select="selected">Verdana</option>
        <option value="1">Arial</option>
        <option value="2">Tahoma</option>    
        </select></td></tr>';
    } elseif(get_option('oaltype') == '1'){
        echo'<tr><td>Lettertype:</td><td><select name="oaltype">
        <option value="1" select="selected">Arial</option>        
        <option value="0">Verdana</option>
        <option value="2">Tahoma</option>    
        </select></td></tr>';
    } else {
       echo'<tr><td>Lettertype:</td><td><select name="oaltype">
        <option value="2" select="selected">Tahoma</option>         
        <option value="1">Arial</option>        
        <option value="0">Verdana</option>
        </select></td></tr>';
    }
}

function get_letter_grote_tekst_oa(){
    if(get_option('oafstext') == '8'){
        echo'<tr><td>Letter grote tekst:</td><td><select name="oafstext">
        <option value="8" select="selected">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafstext') == '9'){
        echo'<tr><td>Letter grote tekst:</td><td><select name="oafstext">
        <option value="9" select="selected>9px</option>        
        <option value="8"">8px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafstext') == '10'){
        echo'<tr><td>Letter grote tekst:</td><td><select name="oafstext">
        <option value="10" select="selected">10px</option>        
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafstext') == '11'){
        echo'<tr><td>Letter grote tekst:</td><td><select name="oafstext">
        <option value="11" select="selected">11px</option>        
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafstext') == '12'){
        echo'<tr><td>Letter grote tekst:</td><td><select name="oafstext">
        <option value="12" select="selected">12px</option>        
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafstext') == '13'){
        echo'<tr><td>Letter grote tekst:</td><td><select name="oafstext">
        <option value="13" select="selected">13px</option>  
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafstext') == '14'){
        echo'<tr><td>Letter grote tekst:</td><td><select name="oafstext">
        <option value="14" select="selected">14px</option>        
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafstext') == '15'){
        echo'<tr><td>Letter grote tekst:</td><td><select name="oafstext">
        <option value="15" select="selected">15px</option> 
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafstext') == '16'){
        echo'<tr><td>Letter grote tekst:</td><td><select name="oafstext">
        <option value="16" select="selected">16px</option>        
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafstext') == '17'){
        echo'<tr><td>Letter grote tekst:</td><td><select name="oafstext">
        <option value="17" select="selected">17px</option> 
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafstext') == '18'){
        echo'<tr><td>Letter grote tekst:</td><td><select name="oafstext">
        <option value="18" select="selected">18px</option> 
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafstext') == '19'){
        echo'<tr><td>Letter grote tekst:</td><td><select name="oafstext">
        <option value="19" select="selected">19px</option>       
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafstext') == '20'){
        echo'<tr><td>Letter grote tekst:</td><td><select name="oafstext">
        <option value="20" select="selected">20px</option>
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafstext') == '22'){
        echo'<tr><td>Letter grote tekst:</td><td><select name="oafstext">
        <option value="22" select="selected">22px</option> 
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafstext') == '24'){
        echo'<tr><td>Letter grote tekst:</td><td><select name="oafstext">
        <option value="24" select="selected">24px</option>     
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    
    } else {
        echo'<tr><td>Letter grote tekst:</td><td><select name="oafstext">
        <option value="26" select="selected">26px</option>   
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>    
        </select></td></tr>';
    }
}

function get_letter_grote_header_oa(){
    if(get_option('oafshtext') == '8'){
        echo'<tr><td>Letter grote header:</td><td><select name="oafshtext">
        <option value="8" select="selected">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafshtext') == '9'){
        echo'<tr><td>Letter grote header:</td><td><select name="oafshtext">
        <option value="9" select="selected>9px</option>        
        <option value="8"">8px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafshtext') == '10'){
        echo'<tr><td>Letter grote header:</td><td><select name="oafshtext">
        <option value="10" select="selected">10px</option>        
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafshtext') == '11'){
        echo'<tr><td>Letter grote header:</td><td><select name="oafshtext">
        <option value="11" select="selected">11px</option>        
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafshtext') == '12'){
        echo'<tr><td>Letter grote header:</td><td><select name="oafshtext">
        <option value="12" select="selected">12px</option>        
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafshtext') == '13'){
        echo'<tr><td>Letter grote header:</td><td><select name="oafshtext">
        <option value="13" select="selected">13px</option>  
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafshtext') == '14'){
        echo'<tr><td>Letter grote header:</td><td><select name="oafshtext">
        <option value="14" select="selected">14px</option>        
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafshtext') == '15'){
        echo'<tr><td>Letter grote header:</td><td><select name="oafshtext">
        <option value="15" select="selected">15px</option> 
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafshtext') == '16'){
        echo'<tr><td>Letter grote header:</td><td><select name="oafshtext">
        <option value="16" select="selected">16px</option>        
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafshtext') == '17'){
        echo'<tr><td>Letter grote header:</td><td><select name="oafshtext">
        <option value="17" select="selected">17px</option> 
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafshtext') == '18'){
        echo'<tr><td>Letter grote header:</td><td><select name="oafshtext">
        <option value="18" select="selected">18px</option> 
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafshtext') == '19'){
        echo'<tr><td>Letter grote header:</td><td><select name="oafshtext">
        <option value="19" select="selected">19px</option>       
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafshtext') == '20'){
        echo'<tr><td>Letter grote header:</td><td><select name="oafshtext">
        <option value="20" select="selected">20px</option>
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafshtext') == '22'){
        echo'<tr><td>Letter grote header:</td><td><select name="oafshtext">
        <option value="22" select="selected">22px</option> 
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafshtext') == '24'){
        echo'<tr><td>Letter grote header:</td><td><select name="oafshtext">
        <option value="24" select="selected">24px</option>     
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    
    } else {
        echo'<tr><td>Letter grote header:</td><td><select name="oafshtext">
        <option value="26" select="selected">26px</option>   
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>    
        </select></td></tr>';
    }
}

function get_letter_grote_calendar_oa(){
    if(get_option('oafsctext') == '8'){
        echo'<tr><td>Letter grote kalender:</td><td><select name="oafsctext">
        <option value="8" select="selected">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafsctext') == '9'){
        echo'<tr><td>Letter grote kalender:</td><td><select name="oafsctext">
        <option value="9" select="selected>9px</option>        
        <option value="8"">8px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafsctext') == '10'){
        echo'<tr><td>Letter grote kalender:</td><td><select name="oafsctext">
        <option value="10" select="selected">10px</option>        
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafsctext') == '11'){
        echo'<tr><td>Letter grote kalender:</td><td><select name="oafsctext">
        <option value="11" select="selected">11px</option>        
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafsctext') == '12'){
        echo'<tr><td>Letter grote kalender:</td><td><select name="oafsctext">
        <option value="12" select="selected">12px</option>        
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafsctext') == '13'){
        echo'<tr><td>Letter grote kalender:</td><td><select name="oafsctext">
        <option value="13" select="selected">13px</option>  
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafsctext') == '14'){
        echo'<tr><td>Letter grote kalender:</td><td><select name="oafsctext">
        <option value="14" select="selected">14px</option>        
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafsctext') == '15'){
        echo'<tr><td>Letter grote kalender:</td><td><select name="oafsctext">
        <option value="15" select="selected">15px</option> 
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafsctext') == '16'){
        echo'<tr><td>Letter grote kalender:</td><td><select name="oafsctext">
        <option value="16" select="selected">16px</option>        
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafsctext') == '17'){
        echo'<tr><td>Letter grote kalender:</td><td><select name="oafsctext">
        <option value="17" select="selected">17px</option> 
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafsctext') == '18'){
        echo'<tr><td>Letter grote kalender:</td><td><select name="oafsctext">
        <option value="18" select="selected">18px</option> 
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafsctext') == '19'){
        echo'<tr><td>Letter grote kalender:</td><td><select name="oafsctext">
        <option value="19" select="selected">19px</option>       
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafsctext') == '20'){
        echo'<tr><td>Letter grote kalender:</td><td><select name="oafsctext">
        <option value="20" select="selected">20px</option>
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafsctext') == '22'){
        echo'<tr><td>Letter grote kalender:</td><td><select name="oafsctext">
        <option value="22" select="selected">22px</option> 
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="24">24px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    } elseif(get_option('oafsctext') == '24'){
        echo'<tr><td>Letter grote kalender:</td><td><select name="oafsctext">
        <option value="24" select="selected">24px</option>     
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="26">26px</option>    
        </select></td></tr>';
    
    } else {
        echo'<tr><td>Letter grote kalender:</td><td><select name="oafsctext">
        <option value="26" select="selected">26px</option>   
        <option value="8">8px</option>
        <option value="9">9px</option>
        <option value="10">10px</option>
        <option value="11">11px</option>
        <option value="12">12px</option>
        <option value="13">13px</option>
        <option value="14">14px</option>
        <option value="15">15px</option>
        <option value="16">16px</option>
        <option value="17">17px</option>
        <option value="18">18px</option>
        <option value="19">19px</option>
        <option value="20">20px</option>
        <option value="22">22px</option>
        <option value="24">24px</option>    
        </select></td></tr>';
    }
}

function get_align_oa(){
    if(get_option('oaalign') == 'left'){
        echo'<tr><td>Weergave widget:</td><td><select name="oaalign">
        <option value="left" select="selected">Links</option>
        <option value="right">Rechts</option>
        </select></td></tr>';
    } else {
        echo'<tr><td>Weergave widget:</td><td><select name="oaalign">
        <option value="right" select="selected">Rechts</option> 
        <option value="left">Links</option>
        </select></td></tr>';
    }
}

function get_align_oa_button(){
    if(get_option('oaalignb') == 'left'){
        echo'<tr><td>Weergave button:</td><td><select name="oaalignb">
        <option value="left" select="selected">Links</option>
        <option value="right">Rechts</option>
        </select></td></tr>';
    } else {
        echo'<tr><td>Weergave button:</td><td><select name="oaalignb">
        <option value="right" select="selected">Rechts</option> 
        <option value="left">Links</option>
        </select></td></tr>';
    }
}


   // verwerkt de ingevulde gegevens nadat er op submit is gedrukt
function admin_oa_plugin_proces(){   

    if(isset($_POST['submit'])){
       $apikey = $_POST['apikey'];
       $logo = $_POST['logo'];
       $fblogin = $_POST['fblogin'];
       $oalang = $_POST['oalang'];
       $oaltype = $_POST['oaltype'];
       $oafstext = $_POST['oafstext'];
       $oafshtext = $_POST['oafshtext'];
       $oafsctext = $_POST['oafsctext'];
       $oaframewidth = $_POST['oaframewidth'];
       $oaframeheight = $_POST['oaframeheight'];
       $img_button_oa = $_POST['img_oa'];
       $oaalign = $_POST['oaalign'];
       $oaalignb = $_POST['oaalignb'];
       
       update_option('apikey', $apikey);
       update_option('logo', $logo);
       update_option('fblogin', $fblogin);
       update_option('oalang', $oalang);
       update_option('oaltype', $oaltype);
       update_option('oafstext', $oafstext);
       update_option('oafshtext', $oafshtext);
       update_option('oafsctext', $oafsctext);
       update_option('oaframewidth', $oaframewidth);
       update_option('oaframeheight', $oaframeheight);
       update_option('img_oa', $img_button_oa);
       update_option('oaalign', $oaalign);
       update_option('oaalignb', $oaalignb);
   }
}


// install function
function ploa_install() {
       update_option('apikey', "");
       update_option('logo', "1");
       update_option('fblogin', "0");
       update_option('oalang', "3");
       update_option('oaltype', "0");
       update_option('oafstext', "14");
       update_option('oafshtext', "17");
       update_option('oafsctext', "17");
       update_option('oaframewidth', "400");
       update_option('oaframeheight', "600");
       update_option('oaalign', "left");
       update_option('oaalignb', "left");
       update_option('img_oa', "boeknu.png");
}

// zet standaard settings / is ook reset
function ploa_install_reset(){       
       
    if(isset($_POST['reset'])){
       update_option('logo', "1");
       update_option('fblogin', "0");
       update_option('oalang', "3");
       update_option('oaltype', "0");
       update_option('oafstext', "14");
       update_option('oafshtext', "17");
       update_option('oafsctext', "17");
       update_option('oaframewidth', "400");
       update_option('oaframeheight', "600");
       update_option('oaalign', "left");
       update_option('oaalignb', "left");
       echo sprintf(__('<br /><strong>Data reset!</strong><br />'));
    }
    if(isset($_POST['reset_all'])){
       update_option('apikey', "");
       update_option('logo', "1");
       update_option('fblogin', "0");
       update_option('oalang', "3");
       update_option('oaltype', "0");
       update_option('oafstext', "14");
       update_option('oafshtext', "17");
       update_option('oafsctext', "17");
       update_option('oaframewidth', "400");
       update_option('oaframeheight', "600");
       update_option('oaalign', "left");
       update_option('oaalignb', "left");
       update_option('img_oa', "boeknu.png");
       echo sprintf(__('<br /><strong>All data reset!</strong><br />'));
    }
}

// roept install functie aan
register_activation_hook(__FILE__,'ploa_install');



// uinstall function
function ploa_uinstall() {
       delete_option('apikey');
       delete_option('logo');
       delete_option('fblogin');
       delete_option('oalang');
       delete_option('oaltype');
       delete_option('oafstext');
       delete_option('oafshtext');
       delete_option('oafsctext');
       delete_option('oaframewidth');
       delete_option('oaframeheight');
       delete_option('oaalign');
       delete_option('oaalignb');
       delete_option('img_oa');
}

// roept uinstall functie aan
register_deactivation_hook(__FILE__,'ploa_uinstall');

?>