<div>
    <div class="col-sm-8 col-sm-offset-2">
        <?php
        $this->load->view('exercise/BreadCrumb', $breadcrumb);
        ?>
    </div>
    <div class="col-sm-12 col-md-10 col-md-offset-1">
        <div class="panel exercise-panel panel-default">
            <div class="panel-body">
                <div class="col-xs-12">
                    <?php
                    $this->load->view('exercise/Progress', $progress);
                    ?>
                    <div class="row question">
                        <div class="col-sm-10 col-sm-offset-1"><?php

                            echo $question; ?>

                        </div>
                    </div>
                    <div class="row answer">
                        <div class="col-sm-12 exercise_input">
                            <form id="exercise_form" autocomplete="off"><?php
                                switch ($type) {
                                    case 'int':
                                    case 'single_list':
                                        if (isset($labels)) {
                                            $this->load->view('exercise/input/Default', array('labels' => $labels));
                                        } else {
                                            $this->load->view('exercise/input/Default');
                                        }
                                        break;
                                    case 'multi':
                                        $this->load->view('exercise/input/Multi', array('options' => $options));
                                        break;
                                    case 'array':
                                    case 'list':
                                        $this->load->view('exercise/input/Array', array('labels' => $labels));
                                        break;
                                    case 'coordinate':
                                    case 'coordinatelist':
                                        $this->load->view('exercise/input/Coordinate', array('labels' => $labels));
                                        break;
                                    case 'fraction':
                                        $this->load->view('exercise/input/Fraction');
                                        break;
                                    case 'range':
                                        $this->load->view('exercise/input/Range');
                                        break;
                                    case 'quiz':
                                        $this->load->view('exercise/input/Quiz',
                                            array('options' => $options,
                                                'width' => $width,
                                                'align' => $align));
                                        break;
                                } ?>

                                <input type="hidden" name="hash" value="<?php echo $hash; ?>">
                                <input type="hidden" name="hints_all" value="<?php echo $hints_all; ?>">
                                <div class="text-center"><?php

                                    if ($debugMode) {
                                        print_r($type . '<br />');
                                        print_r($correct);
                                        print_r('<br />');
                                    } ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <a class="btn btn-default pull-left"
                           href="<?php echo base_url() . $classlabel . '/' . $subtopiclabel; ?>"
                           onclick="unsetexercise(event)">
                            <span class="fa fa-chevron-left"></span> Vissza
                        </a>
                        <a id="next_button" class="btn btn-lg btn-primary pull-right"
                           onclick="checkSolution(event)">Tovább&nbsp;<span class="fa fa-arrow-right">
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <p><?php

                            if (isset($youtube)) { ?>
                                <a data-toggle="collapse" data-target="#videoCollapse"
                                   class="btn btn-warning pull-left collapse-button">
                                    <span class="fa fa-play"></span>&nbsp;Videó megoldás
                                </a>
                                <?php
                            } ?>

                        </p><?php

                        if ($hints_all > 0) { ?>

                        <p><a id="hint_button" class="btn btn-danger pull-right" onclick="gethint(event)">
                                Segítséget kérek!
                            </a></p><br/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <p id="hints_left" class="small pull-right">
                            (<?php echo $hints_all - $hints_used; ?> segítség maradt.)
                        </p><?php

                        }

                        ?>

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <div id="message"></div>
                    </div>
                </div><?php

                if (isset($youtube)) { ?>

                    <div class="row">
                    <div class="col-sm-12 text-center collapse" id="videoCollapse">
                        <div class="embed-responsive embed-responsive-16by9 videoplayer">
                            <iframe style="border: 3px solid #000;" class="embed-responsive-item"
                                    src="//www.youtube.com/embed/<?php echo $youtube; ?>?rel=0&amp;showinfo=0&amp;iv_load_policy=3"
                                    allowfullscreen=""></iframe>
                        </div>
                        <a data-toggle="collapse" data-target="#videoCollapse"
                           class="btn btn-default text-center collapse-button">
                            <span class="fa fa-remove"></span>&nbsp;Bezár
                        </a>
                    </div>
                    </div><?php

                } ?>

                <div class="row">
                    <div id="hints" class="col-sm-12"></div>
                </div>
            </div>
        </div>
    </div>

    <script>

      document.onkeydown = keyPress

      function keyPress(e) {
        var x = e || window.event
        var key = (x.keyCode || x.which)
        if (key == 13 || key == 3) {
          if ($('#next_button').attr('href')) {
            window.location.href = $('#next_button').attr('href')
          } else {
            checkSolution(x)
          }
        }
        else if (key == 37) {
          data = $('.prev_hint').children().attr('onclick')
          if (typeof data !== 'undefined') {
            arr = data.split(/[(,')]/)
            gethint(x, arr[2], arr[4])
          }
        }
        else if (key == 39) {
          data = $('.next_hint').children().attr('onclick')
          if (typeof data === 'undefined') {
            gethint(x)
          } else {
            arr = data.split(/[(,')]/)
            gethint(x, arr[2], arr[4])
          }

        }
      }

      function unsetexercise(event) {
        var hash = $('[name="hash"]').attr('value')
        $.ajax({
          type: 'GET',
          url: '<?php echo base_url();?>action/unsetexercise/' + hash
        })
        return true
      }

      function gethint(event, id, type) {

        var hash = $('[name="hash"]').attr('value')
        var hints_all = $('[name="hints_all"]').attr('value')

        if (typeof id === 'undefined') {
          id = ''
        } else {
          id = Number(id)
        }
        if (typeof type === 'undefined') {
          type = ''
        }
        event.preventDefault()
        if (id == '' || (id > 0 && id <= Number(hints_all))) {
          $('#loader').html('Kis türelmet...&nbsp;<img src="<?php echo base_url();?>assets/images/loader.gif" />')
          $.ajax({
            type: 'GET',
            url: '<?php echo base_url();?>action/gethint/' + hash + '/' + id.toString() + '/' + type.toString(),
            success: function (data) {
              if (data) {
                var $hints = $('#hints');
                $('#message').html('')
                data = jQuery.parseJSON(data)
                var hint_current = Number(data['hint_current'])
                var hints_all = Number(data['hints_all'])
                var hints_used = Number(data['hints_used'])
                var hints_left = hints_all - hints_used
                if (hints_all > 1) {
                  $hints.html('<ul class="pager pager-bottom"></ul>')
                  $hints.children().append('<li class="prev_hint small"><a onclick="gethint(event,' + hint_current + ',\'prev\')"><span class="fa fa-chevron-left"></span></a></li>')
                  if (hint_current === 1) {
                    $('.prev_hint').attr('class', 'small disabled')
                  }
                  $hints.children().append('<li class="small"><b>' + hint_current + '/' + hints_all + '</b></li>')
                  $hints.children().append('<li class="next_hint small"><a onclick="gethint(event,' + hint_current + ',\'next\')"><span class="fa fa-chevron-right"></span></a></li>')
                  if (hint_current >= hints_all) {
                    $('.next_hint').attr('class', 'small disabled')
                  }
                }

                if (data['hints']) {
                  var newHint = $('<div class="fade"><p>' + data['hints'] + '</p></div>');
                  $hints
                    .append(newHint)

                  //new Svg_MathJax().typeset(newHint.get(0))
                  MathJax.Hub.Queue(['Typeset', MathJax.Hub, newHint.get(0)], function(){
                    newHint.addClass('in')
                  })

                  $('#hints_left').html('(' + hints_left.toString() + ' segítség maradt.)')
                  if (hints_left === 0) {
                    $('#hint_button').attr('class', 'btn btn-danger pull-right disabled')
                  }
                }
              }
            }
          })
          $('#loader').html('<br />')
        }
      }

      // Check solution
      function checkSolution(event) {

        $('#loader').html('Kis türelmet...&nbsp;<img src="<?php echo base_url();?>assets/images/loader.gif" />')

        var queryString = $('#exercise_form').serializeArray()
        event.preventDefault()
        $.ajax({
          type: 'GET',
          url: '<?php echo base_url();?>action/checkanswer',
          data: {
            answer: JSON.stringify(queryString)
          },
          dataType: 'json',
          success: function (data) {

            // Exercise not finished
            if (data['status'] === 'NOT_DONE') {

              $('#message').html('<div class="alert alert-warning"><strong><span class=\"fa fa-remove\"></span></strong>&nbsp;&nbsp;' + data['message'] + '</div>')
              MathJax.Hub.Queue(['Typeset', MathJax.Hub, 'message'])
              $('#loader').html('<br />')
              return

            }

            // Disable buttons
            var radios = document.forms['exercise_form']['answer']

            if (radios.length > 0) {
              for (var i = 0, iLen = radios.length; i < iLen; i++) {
                radios[i].disabled = true
              }
            } else {
              radios.disabled = true
            }

            // Disable hint button
            if (data['status'] === 'CORRECT') {
              $('#hint_button').attr('class', 'btn btn-danger pull-right disabled')
            }

            // Exercise finished
            switch (data['status']) {
              case 'CORRECT':
                $('#message').replaceWith('<div class="alert alert-success"><strong><span class=\"fa fa-ok\"></span></strong>&nbsp;&nbsp;' + data['message'] + '</div>')
                $('#progress_bar').css('width', data['progress']['value'] + '%').attr('aria-valuenow', data['progress']['value'])
                progress_bar_class = $('#progress_bar').attr('class').replace(/(progress-bar-)\w*/, '$1' + data['progress']['style'])
                $('#progress_bar').attr('class', progress_bar_class)
                $('#next_button').replaceWith('<a id="next_button" class="btn btn-primary btn-lg pull-right" href="' + data['link_next'] + '">Tovább&nbsp;<span class="fa fa-chevron-right"></span></button>')
                // Update results
                $('.trophies').text(data['results']['trophies'])
                $('.shields').text(data['results']['shields'])
                $('.points').text(data['results']['points'])
                var seconds = parseInt($('#time').text())
                jQuery(function ($) {
                  var time = seconds, display = $('#time')
                  startTimer(time, display)
                })
                setTimeout(function () {
                  window.location = data['link_next']
                }, (seconds + 1) * 1000)
                break
              case 'WRONG':
                if (data['hints']) {
                  $('#exercise_hints').replaceWith('<div class="alert alert-warning text-left">' + data['hints'] + '</div>')
                  MathJax.Hub.Queue(['Typeset', MathJax.Hub, 'hint'])
                }
                $('#message').replaceWith('<div class="alert alert-danger"><strong><span class=\"fa fa-remove\"></span></strong>&nbsp;&nbsp;' + data['message'] + '</div>')
                $('#next_button').replaceWith('<a id="next_button" class="btn btn-lg btn-primary pull-right" href="<?php echo base_url() . $classlabel . '/' . $subtopiclabel . '/' . $exerciselabel;?>">Újra&nbsp;<span class="fa fa-refresh"></span></a>')
                var keys = []
                for (var k in data.submessages) keys.push(k);
                if (keys.length > 0) {

                  for (var i = keys.length - 1; i >= 0; i--) {
                    var submessage = data.submessages[i]
                    if (submessage == 'FILLED_CORRECT') {
                      $('#input' + i).before('<span class=\"fa fa-ok alert-success2\"></span>&nbsp;')
                    } else if (submessage == 'FILLED_WRONG') {
                      $('#input' + i).before('<span class=\"fa fa-remove alert-danger2\"></span>&nbsp;')
                    } else if (submessage == 'EMPTY_WRONG') {
                      $('#input' + i).before('<span class=\"fa fa-ok alert-warning2\"></span>&nbsp;')
                    } else {
                      $('#input' + i).before('<span class=\"fa fa-ok alert-info2\"></span>&nbsp;')
                    }
                  }
                }
                MathJax.Hub.Queue(['Typeset', MathJax.Hub, 'message'])
                break
            }
          }
        })
        $('#loader').html('<br />')
      }

      function startTimer(seconds, display) {
        var timer = seconds
        setInterval(function () {

          display.text(timer)

          if (--timer < 0) {
            timer = 0
          }
        }, 1000)
      }
    </script>
</div>
