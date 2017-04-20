<nav class="navbar navbar-default navbar-fixed-top" role="banner">

    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="<?php echo base_url(); ?>" onclick="unsetexercise(event)">
                <img src="<?php echo base_url(); ?>assets/images/logo.svg" alt="logo" width="20">
            </a>

            <a class="navbar-brand navbar-logo" href="<?php echo base_url(); ?>" onclick="unsetexercise(event)">
                <b>Zsebtanár</b>
            </a>
        </div>

        <div class="collapse navbar-collapse" id="bs-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right small"><?php

                if ($type != 'main') { ?>
                    <li>
                        <a href="#" data-toggle="modal" data-target="#info" class="trophies btn btn-link">
                            <img src="<?php echo base_url(); ?>assets/images/trophy.png" alt="shield" width="15"/>
                            <?php echo $results['trophies']; ?>
                        </a>
                    </li>

                    <li>
                        <a href="#" data-toggle="modal" data-target="#info" class="shields btn btn-link">
                            <img src="<?php echo base_url(); ?>assets/images/shield.png" alt="shield" width="15"/>
                            <?php echo $results['shields']; ?>
                        </a>
                    </li>


                    <li>
                        <a href="#" data-toggle="modal" data-target="#info" class="points btn btn-link">
                            <img src="<?php echo base_url(); ?>assets/images/coin.png" alt="coin" width="15"/>
                            <?php echo $results['points']; ?>
                        </a>
                    </li>
                <?php } ?>

                    <li>
                        <a href="#" data-toggle="modal" data-target="#info" class="btn btn-default">
                            <span class="fa fa-lg fa-user-circle"></span>
                        </a>
                    </li>

                    <li>
                        <a href="#" data-toggle="modal" data-target="#info" class="btn btn-default">
                            <span class="fa fa-lg fa-question-circle"></span>
                        </a>
                    </li>
            </ul>
        </div>
    </div>
</nav>