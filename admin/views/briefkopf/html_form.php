<?php

function ticketmaster_render_general_settings($ticketmaster_general_settings){

?>
<section id="intro">
    <img src="<?php echo plugin_dir_url(__FILE__). 'loader.gif'; ?>" alt="">
</section>

<h1>Einstellung PDF Vorlage</h1>
<h2>Adresse:</h2>

<form action="" method="post" >
<div class="divTable" style="width: 50%;" >
  <div class="divTableBody">
        <div class="divTableRow">
          <div class="divTableCell">Logo:</div>
          <img src="<?php echo $ticketmaster_general_settings['logo']; ?>" width="200" class="uploaded-logo">
          <div class="divTableCell"><label>
            <button class="js-image-upload button button-primary">Logo hochladen</button>
            <input name="logo" type="hidden" size="50" class="image-link-input" value="<?php echo $ticketmaster_general_settings['logo']; ?>">Bild als .png ! </label>
          </div>
        </div>

      <div class="divTableRow">
        <div class="divTableCell"><b>Kopfzeile:</b></div>
        <div class="divTableCell">
          <input type="text" name="heading"  value="<?php echo $ticketmaster_general_settings['heading']; ?>">
        </div>
      </div>

      <div class="divTableRow">
          <div class="divTableCell"><b>Info Text:</b></div>
          <div class="divTableCell"><textarea rows="5" name="info" cols="50"><?php echo $ticketmaster_general_settings['info']; ?></textarea>
          </div>
      </div>

      <div class="divTableRow" id="hid">
         <div class="divTableCell"><b>Zusatzinfos:</b></div>
          <div class="divTableCell" >
              <textarea rows="5" name="additional_info" cols="50"><?php echo $ticketmaster_general_settings['additional_info']; ?></textarea>
          </div>
      </div>   



      <h2>Fu&#7838;zeile:</h2>
      <h4><u>Text links in der Fu&#7838;zeile:</u></h4>

      <div class="divTableRow" id="hid">
          <div class="divTableCell" >
              <textarea rows="6" name="footer[1]" cols="50"><?php echo $ticketmaster_general_settings['footer'][1]; ?></textarea>
          </div>
      </div>


      <h4><u>Text Center  in der Fu&#7838;zeile:</u> </h4>
      <div class="divTableRow" id="hid">
          <div class="divTableCell" >
              <textarea rows="6" name="footer[2]" cols="50"><?php echo $ticketmaster_general_settings['footer'][2]; ?></textarea>
          </div>
      </div>


      <h4><u>Text rechts in der Fu&#7838;zeile: </u></h4>


      <div class="divTableRow" id="hid">
          <div class="divTableCell" >
              <textarea rows="6" name="footer[3]" cols="50"><?php echo $ticketmaster_general_settings['footer'][3]; ?></textarea>
          </div>
      </div>


    <div class="divTableRow">
    <div class="divTableCell"></div>
    <div class="divTableCell"><input type="submit" value="Absenden" name="save_general_settings" class="button button-primary"></div>
    </div>

  <!--  <div class="divTableRow">
      <div class="divTableCell"></div>
      <div class="divTableCell">
          <input type="submit" value="Show demo PDF" name="print_pdf_template" formtarget="_blank" class="button button-secondary"></div>
    </div>
-->

  </div>
</div>
</form>

<?php

}