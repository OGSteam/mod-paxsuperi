<?php

if (! defined('IN_SPYOGAME')) {
    exit('Hacking attempt');
}

$pub_subaction = (isset($pub_subaction)) ? $pub_subaction : 'paxsuperi';

$activeIndex = ($pub_subaction === 'paxsuperi') ? 'active' : '';
$activeState = ($pub_subaction === 'state') ? 'active' : '';
$activeAdmin = ($pub_subaction === 'admin') ? 'active' : '';

?>

<div class="nav-page-menu">
    <div class="nav-page-menu-item  <?php echo $activeIndex; ?> ">
        <a class="nav-page-menu-link" href="index.php?action=paxsuperi&amp;subaction=paxsuperi">
            Pax Superi
        </a>
    </div>
    <div class="nav-page-menu-item  <?php echo $activeState; ?> ">
        <a class="nav-page-menu-link" href="index.php?action=paxsuperi&amp;subaction=state">
            Etat
        </a>
    </div>
    <div class="nav-page-menu-item  <?php echo $activeAdmin; ?> ">
        <a class="nav-page-menu-link" href="index.php?action=paxsuperi&amp;subaction=admin">
            Administration
        </a>
    </div>

</div>