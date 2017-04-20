<div class="row">
    <div class="col-xs-4">
        <ul class="pager">
            <li class="previous prev_link">
                <a href="<?php echo $prev['link']; ?>" onclick="unsetexercise(event)">
                    <i class="fa fa-chevron-left"></i> <?php echo $prev['name']; ?>
                </a>
            </li>
        </ul><?php

        if (count($easier) > 0) { ?>

            <ul class="pager pager-success dropdown">
            <li class="previous prev_link">

                <a href="#" onclick="unsetexercise(event)" data-toggle="dropdown">
                    <i class="fa fa-chevron-left"></i> Könnyebb
                </a>

                <ul class="dropdown-menu dropdown-menu-left"><?php

                    foreach ($easier as $exercise) { ?>
                        <li class="dropdown-menu-li">
                        <a href="<?php echo $exercise['link']; ?>">
                            <img class="star"
                                 src="<?php echo base_url() . 'assets/images/star' . $exercise['progress']['stars'][0] . '.png'; ?>"
                                 alt="star" width="15px">
                            <img class="star"
                                 src="<?php echo base_url() . 'assets/images/star' . $exercise['progress']['stars'][1] . '.png'; ?>"
                                 alt="star" width="15px">
                            <img class="star star-last"
                                 src="<?php echo base_url() . 'assets/images/star' . $exercise['progress']['stars'][2] . '.png'; ?>"
                                 alt="star" width="15px">
                            <?php echo $exercise['name']; ?>
                        </a>
                        </li><?php

                    } ?>

                </ul>

            </li>
            </ul><?php

        } ?>


    </div>
    <div class="col-xs-4">
    </div>
    <div class="col-xs-4">
        <ul class="pager">
            <li class="next next_link">
                <a href="<?php echo $next['link']; ?>" onclick="unsetexercise(event)">
                    <?php echo $next['name']; ?> <i class="fa fa-chevron-right"></i>
                </a>
            </li>
        </ul><?php

        if (count($harder) > 0) { ?>

            <ul class="pager pager-danger dropdown">
            <li class="next next_link">

                <a href="#" onclick="unsetexercise(event)" data-toggle="dropdown">
                    Nehezebb <i class="fa fa-chevron-right"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-right"><?php

                    foreach ($harder as $exercise) { ?>
                        <li class="dropdown-menu-li">
                        <a href="<?php echo $exercise['link']; ?>">
                            <img class="star"
                                 src="<?php echo base_url() . 'assets/images/star' . $exercise['progress']['stars'][0] . '.png'; ?>"
                                 alt="star" width="15px">
                            <img class="star"
                                 src="<?php echo base_url() . 'assets/images/star' . $exercise['progress']['stars'][1] . '.png'; ?>"
                                 alt="star" width="15px">
                            <img class="star star-last"
                                 src="<?php echo base_url() . 'assets/images/star' . $exercise['progress']['stars'][2] . '.png'; ?>"
                                 alt="star" width="15px">
                            <?php echo $exercise['name']; ?>
                        </a>
                        </li><?php

                    } ?>
                </ul>
            </li>
            </ul><?php
        } ?>
    </div>
</div>
