<div class="wrap">
    <h1>Veranstaltungen Einstellungen</h1>
    <hr><br>
   
    <?php if( !empty( $this->notice )){ ?>
        <div class="notice is-dismissible notice-success">
            <p><?php echo $this->notice; ?> </p>
        </div>
    <?php } ?>

    <a href="admin.php?page=bixxs-events-tickeseinstellungen&action=bixxs_events_new_template" class="page-title-action">+ Neue Veranstaltung </a> <br>
    
    <h1 style="margin-top:40px;">Alle Veranstaltungen:</h1>

    <?php
        /**
		 * Get All Tickets data from DataBase Table
		 */
        global $wpdb;
        $table_name = $wpdb->prefix . 'bixxs_events';
        $result = $wpdb->get_results ( "SELECT * FROM $table_name" );
    ?>

    <table class="ticket-table">
        <tr class="table-title">
            <th>ID</th>
            <th>Veranstaltung</th>
            <th>Bearbeiten</th>
            <th>löschen</th>
        </tr>
      
        <?php 
            if (empty($result)) {
     
                echo '<tr><td>Keine Veranstaltungen gefunden!</td></tr>';
       
            } else {

                foreach ($result as $key => $ticket) { 
         ?>
            <tr>
                <td><?php echo $ticket->ID; ?></td>
                <td><?php echo $ticket->ticketnname; ?></td>
           
                <td><a href="admin.php?page=bixxs-events-tickeseinstellungen&action=edit-event&id=<?php echo $ticket->ID; ?>" class="info-eidt">Bearbeiten</a></td>
         
                <td> <a href="admin.php?page=bixxs-events-tickeseinstellungen&action=delete-event&id=<?php echo $ticket->ID; ?>" class="info-delete">löschen</a></td>
            </tr>
        <?php } }  ?>

        <tr class="table-title">
            <th>ID</th>
            <th>Veranstaltungen</th>
            <th>Bearbeiten</th>
            <th>löschen</th>
        </tr>
   </table>

</div>