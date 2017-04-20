<?php

$this->load->view('topics/BreadCrumb', $breadcrumb);
$this->load->view('topics/Title', $title);

if (is_array($exercises)) {

    $order = 1;
    ?>
    <div class="col-sm-8 col-sm-offset-2">
        <?php foreach ($exercises as $ex) { ?>

            <div class="panel panel-default topic-panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="col-sm-12">
                                <h1 class="exercise-label text-right">
                                    <?php echo(array_key_exists('ex_order', $ex) && $ex['ex_order'] != 0 ? $ex['ex_order'] : $order); ?>
                                </h1>
                            </div>

                            <div class="text-right col-sm-12">
                                <img id="star1"
                                     src="<?php echo base_url() . 'assets/images/star' . $ex['progress']['stars'][0] . '.png'; ?>"
                                     alt="star" width="15px">
                                <img id="star2"
                                     src="<?php echo base_url() . 'assets/images/star' . $ex['progress']['stars'][1] . '.png'; ?>"
                                     alt="star" width="15px">
                                <img id="star3"
                                     src="<?php echo base_url() . 'assets/images/star' . $ex['progress']['stars'][2] . '.png'; ?>"
                                     alt="star" width="15px">
                            </div>

                            <?php if (count($ex['tags']) > 0) { ?>
                                <div class="col-sm-12">

                                    <ul class="list-unstyled tag-list">
                                        <?php

                                        foreach ($ex['tags'] as $key => $tag) { ?>
                                            <li>
                                                <a class="label label-default"
                                                   title="<?php echo $tag['name']; ?>"
                                                   href="<?php echo base_url() . 'view/tag/' . $tag['label']; ?>">
                                                    <i class="fa fa-tags"></i> <?php echo $tag['name']; ?>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-9">
                            <?php

                            if (array_key_exists('classlabel', $ex) &&
                                array_key_exists('subtopiclabel', $ex) &&
                                array_key_exists('subtopicname', $ex)
                            ) { ?>
                                <p>
                                <a class="label label-warning"
                                   href="<?php echo base_url() . $ex['classlabel'] . '/' . $ex['subtopiclabel']; ?>">
                                    <?php echo $ex['classlabel'] . '/' . $ex['subtopicname']; ?>
                                </a>
                                </p><?php
                            }

                            echo $ex['question']; ?>

                        </div>
                    </div>

                </div>
                <div class="panel-footer text-center">
                    <a class="btn btn-primary" href="<?php print_r($ex['link']['link']); ?>">
                        Mehet <span class="fa fa-play"></span>
                    </a>
                </div>
            </div>
            <?php

            $order++;

        } ?>
    </div>

<?php } ?>
