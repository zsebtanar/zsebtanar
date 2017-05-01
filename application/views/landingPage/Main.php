<div class="teacher container">
    <div class="top"></div>
    <div class="content ">
        <div class="table-content row">
            <div class="col-sm-10 col-sm-offset-1 col-md-9">
                    <p class="site-description">
                        A <b>Zsebtanár</b> egy interaktív matematikai feladatgyűjtemény videókkal és
                        megoldásokkal, elsősorban az érettségire készülőknek. Észrevételeket,
                        javaslatokat, új feladatokat a <b>zsebtanar@gmail.com</b>-ra lehet küldeni.
                        Ha tetszik az oldal, kövess minket
                        <b><a href="https://www.facebook.com/zsebtanar" target="_blank">Facebook</a></b>-on vagy
                        <b><a href="https://www.youtube.com/channel/UCqtj_u2Otbf-9D0sJcb1zMw"
                              target="_blank">Youtube</a></b>-on!
                    </p>

                    <div class="exercises text-center col-sm-offset-2 col-md-11 col-md-offset-2 col-lg-9 col-lg-offset-3">
                        <div>
                            <input type="text"
                                   id="exercise_tags"
                                   class="form-control input-lg exercise-search"
                                   placeholder="Keress a gyakorlatok között?">
                        </div>

                        <div><p>&dash; vagy &dash;</p></div>

                        <div class="btn-group exercise-selector"
                             role="group"
                             aria-label="Feladat választó gomb csoport">

                            <div class="btn-group" role="group">
                                <a href="#"
                                   class="dropdown-toggle btn btn-default"
                                   data-toggle="dropdown"
                                   role="button"
                                   aria-haspopup="true"
                                   aria-expanded="false">
                                    <?php echo $final_exercises['name']; ?><span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu"><?php
                                foreach ($final_exercises['subtopics'] as $subtopic) { ?>
                                    <li>
                                        <a href="<?php echo base_url() . $final_exercises['classlabel'] . '/' . $subtopic['label']; ?>">
                                            <?php echo $subtopic['name']; ?>
                                        </a>
                                    </li>
                                    <?php

                                } ?>
                                </ul>
                            </div>
                            <div class="btn-group" role="group">
                                <a href="#"
                                   class="dropdown-toggle btn btn-default"
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
                                            <li class="dropdown-header"><?php echo $topic['name']; ?></li>
                                            <?php
                                                    foreach ($topic['subtopics'] as $subtopic) { ?>
                                            <li>
                                                <a href="<?php echo base_url() . $class['label'] . '/' . $subtopic['label']; ?>">
                                                    <?php echo $subtopic['name']; ?>
                                                </a>
                                            </li>
                                            <?php
                                                    }
                                                }
                                            } ?>
                                        </ul>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div class="btn-group" role="group">
                                <button type="button"
                                        class="btn btn-default dropdown-toggle"
                                        data-toggle="dropdown"
                                        role="button"
                                        aria-haspopup="true"
                                        aria-expanded="false">
                                    </i> Véletlen feladat <i class="caret"></i>
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
                        </div>

                    </div>
                </div>
        </div>
        <div class="bg"></div>
    </div>
    <div class="bottom"></div>

</div>