 <?php $setting = Setting::getInstance(); ?>
 <table class="og-table og-little-table">
     <form method="post" action="index.php?action=paxsuperi&amp;subaction=admin&amp;admin=1">
         <thead>
             <tr>
                 <th colspan="2">Configuration</th>
             </tr>
         </thead>
         <tbody>
             <tr>
                 <td class="tdstat">
                     Numero D'univers <?php echo help("pax_uni", "Préciser le numero de votre univers de jeu <br>(ex: 67 => cf : https://s<b>67</b>-fr.ogame.gameforge.com)"); ?>
                 </td>
                 <td class="tdvalue">
                     <input type="text" id="uni" name="uni" value="<?php echo (int)  $setting->uni; ?>" placeholder="67" required="required" />
                 </td>
             </tr>


             <tr>
                 <td class="tdstat">
                     Pays <?php echo help("pax_pays", "Préciser le pays de votre univers de jeu <br>(ex: fr => cf : https://s67-<b>fr</b>.ogame.gameforge.com)"); ?>
                 </td>
                 <td class="tdvalue">
                     <input type="text" id="pays" name="pays" value="<?php echo  $setting->pays; ?>" placeholder="fr" required="required" />
                 </td>
             </tr>

             <tr>
                 <td class="tdstat">
                     Temporisation API <?php echo help("pax_tempo", "Préciser le temps en seconde entre chaque appel"); ?>
                 </td>
                 <td class="tdvalue">
                     <input type="text" id="temporisation" name="temporisation" maxlength="1" value="<?php echo (int)  $setting->temporisation; ?>" placeholder="1" required="required" />

                 </td>
             </tr>
             <tr>
                 <td colspan="2">
                     <input class="btn og-button " type="submit" value="Envoyer!" />
                 </td>
             </tr>


         </tbody>

 </table>