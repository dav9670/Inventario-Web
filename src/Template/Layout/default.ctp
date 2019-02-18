<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'Inventar.io';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('style.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <?= $this->Html->script('jquery-3.3.1.js', array('inline' => false)); ?>
</head>
<body>
    <nav class="top-bar expanded" data-topbar role="navigation">
        <ul class="title-area large-2 medium-3 columns">
            <li class="name">
                <h1><a href=""><?= $this->fetch('title') ?></a></h1>
            </li>
        </ul>
        <div class="top-bar-section">
            <ul>
                <?php if($this->request->getSession()->read('Auth.User')){ ?>
                    <?php if($this->request->getSession()->read('Auth.User.admin_status') == 'admin') { ?>
                        <li><?= $this->Html->link( 'Loans', ['controller' => 'Loans', 'action' => 'index']); ?></li>
                        <li><?= $this->Html->link( 'Mentors', ['controller' => 'Mentors', 'action' => 'index']); ?></li>
                        <li><?= $this->Html->link( 'Rooms', ['controller' => 'Rooms', 'action' => 'index']); ?></li>
                        <li><?= $this->Html->link( 'Licences', ['controller' => 'Licences', 'action' => 'index']); ?></li>
                        <li><?= $this->Html->link( 'Equipments', ['controller' => 'Equipments', 'action' => 'index']); ?></li>
                        <li><?= $this->Html->link( 'Reports', ['controller' => 'Reports', 'action' => 'index']); ?></li>
                        <li><?= $this->Html->link( 'Users', ['controller' => 'Users', 'action' => 'index']); ?></li>
                    <?php } else { ?>
                        <li><?= $this->Html->link( 'Profile', ['controller' => 'Users', 'action' => 'profile']); ?></li>
                    <?php } ?>
                <?php } ?>
            </ul>
            <ul class="title-area right">
                <li><a href="/fr_CA">Francais</a></li>
                <li><a href="/en_US">English</a></li>
            </ul>
            <ul class="right">
                <?php if($this->request->getSession()->read('Auth.User')) { ?>
                    <li><?= $this->Html->link( $this->request->getSession()->read('Auth.User.email'), ['controller' => 'Users', 'action' => 'profile']); ?></li>
                    <li><?= $this->Html->link( 'Logout', ['controller' => 'Users', 'action' => 'logout']); ?></li>
                <?php } ?>
            </ul>
        </div>
    </nav>
    <?= $this->Flash->render() ?>
    <div class="container clearfix">
        <?= $this->fetch('content') ?>
    </div>
    <footer>
    </footer>
</body>
</html>
