<?php

if (! defined('IN_SPYOGAME')) {
    exit('Hacking attempt');
}

$pub_subaction    = (isset($pub_subaction)) ? $pub_subaction : 'paxsuperi';
$activelink       = ($pub_subaction === 'link') ? 'active' : '';
$activeadmin      = ($pub_subaction === 'admin') ? 'active' : '';
$activesuperadmin = ($pub_subaction === 'paxsuperi') ? 'active' : '';
$activexml        = ($pub_subaction === 'xml') ? 'active' : '';

?>

<div class="nav-page-menu">
<div class="nav-page-menu-item  <?php echo $activesuperadmin; ?> ">
        <a class="nav-page-menu-link" href="index.php?action=paxsuperi&amp;subaction=paxsuperi">
            Pax Superi
        </a>
    </div>
    <!--
    <div class="nav-page-menu-item  <?php echo $activelink; ?> ">
        <a class="nav-page-menu-link" href="index.php?action=superapix&amp;subaction=link">
            Liens
        </a>
    </div>
    <div class="nav-page-menu-item  <?php echo $activexml; ?> ">
        <a class="nav-page-menu-link" href="index.php?action=superapix&amp;subaction=xml">
            Info Serveur
        </a>
    </div>    <div class="nav-page-menu-item  <?php echo $activeadmin; ?> ">
        <a class="nav-page-menu-link" href="index.php?action=superapix&amp;subaction=admin">
            Administration
        </a>
    </div>
-->
</div>