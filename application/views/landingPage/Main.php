<div class="teacher container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 text-center">
            <p class="small">
                A <b>Zsebtanár</b> egy interaktív matematikai feladatgyűjtemény videókkal és megoldásokkal, elsősorban
                az
                érettségire készülőknek. Észrevételeket, javaslatokat, új feladatokat a <b>zsebtanar@gmail.com</b>-ra
                lehet
                küldeni. Ha tetszik az oldal, kövess minket <b><a href="https://www.facebook.com/zsebtanar"
                                                                  target="_blank">Facebook</a></b>-on
                vagy <b><a href="https://www.youtube.com/channel/UCqtj_u2Otbf-9D0sJcb1zMw"
                           target="_blank">Youtube</a></b>-on!
            </p>

            <input type="text" id="exercise_tags" class="form-control input-lg"
                   placeholder="Mit szeretnél gyakorolni?">

            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-random" aria-hidden="true"></i>&nbsp;Véletlen feladat <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu"><?php
                    foreach ($classes as $class) { ?>
                        <li>
                            <a href="action/getrandomexercise/<?php echo $class['label']; ?>">
                                <?php echo $class['name']; ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="dropdown">
                <a href="#" class="dropdown-toggle navbar-primary" data-toggle="dropdown" role="button"
                   aria-haspopup="true" aria-expanded="false">
                    <b><?php echo $final_exercises['name']; ?><span class="caret"></span></b>
                    <ul class="dropdown-menu"><?php
                        foreach ($final_exercises['subtopics'] as $subtopic) { ?>

                            <li>
                            <a href="<?php echo base_url() . $final_exercises['classlabel'] . '/' . $subtopic['label']; ?>">
                                <?php echo $subtopic['name']; ?>
                            </a>
                            </li><?php

                        } ?>
                    </ul>
                </a>
            </div>

            <div class="dropdown">
                <a href="#"
                   class="dropdown-toggle"
                   data-toggle="dropdown"
                   role="button"
                   aria-haspopup="true"
                   aria-expanded="false">
                    Osztályok <span class="caret"></span>
                </a>

                <ul class="dropdown-menu multi-level">
                <?php foreach ($classes as $class) { ?>
                    <li class="dropdown-submenu">
                        <a href="#"
                           class="dropdown-toggle"
                           data-toggle="dropdown"
                           role="button"
                           aria-haspopup="true"
                           aria-expanded="false">
                            <?php echo $class['name']; ?>
                        </a>
                        <ul class="dropdown-menu"><?php
                            if (count($class['topics']) > 0) {
                                foreach ($class['topics'] as $topic) { ?>
                                    <li class="dropdown-header"><?php echo $topic['name']; ?></li><?php
                                    foreach ($topic['subtopics'] as $subtopic) { ?>
                                        <li>
                                        <a href="<?php echo base_url() . $class['label'] . '/' . $subtopic['label']; ?>">
                                            <?php echo $subtopic['name']; ?>
                                        </a>
                                        </li><?php
                                    }
                                }
                            } ?>
                        </ul>
                    </li>
                <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>