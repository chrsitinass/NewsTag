$(function() {
  $('#login-table input').keydown(function(e) {
    if (e.keyCode == 13 || e.keyCode == 10) {
      $('#login').click();
    }
  });
  $('#modify-password-table input').keydown(function(e) {
    if (e.keyCode == 13 || e.keyCode == 10) {
      $('#modify-password').click();
    }
  });
  $('#register-table input').keydown(function(e) {
    if (e.keyCode == 13 || e.keyCode == 10) {
      $('#register').click();
    }
  });
  $('#login').click(function() {
    var username = $('#username').val();
    var password = $('#password').val();
    $.post(
        '/user/check_login.php',
        {username: username, password: password},
        function(data) {
          if (data != 'Success') {
            alert(data);
            return false;
          }
          // TODO: If the current position is login.php, return the root
          // page; otherwise reload the page.

          //document.location.reload();
          document.location.href = '/';
        });
  });

  $('#logout').click(function() {
    $.post(
        '/user/logout.php',
        {},
        function(data) {
          document.location.href = '/';
        });
  });

  $('#register').click(function() {
    var username = $('#username').val();
    var pass1 = $('#password1').val();
    var pass2 = $('#password2').val();
    if (pass1 != pass2) {
      alert('Inconsistent passwords.');
      return false;
    }
    $.post(
        '/user/check_register.php',
        {username: username, password: pass1},
        function(data) {
          if (data != 'Success') {
            alert(data);
          } else {
            document.location.href = '/';
          }
        });
  });

  $('#modify-password').click(function() {
    var pass0 = $('#password0').val();
    var pass1 = $('#password1').val();
    var pass2 = $('#password2').val();
    if (pass1 != pass2) {
      alert('Inconsistent passwords.');
      return false;
    }
    $.post(
        '/user/modify_password_post_handler.php',
        {old_password: pass0, new_password: pass1},
        function(data) {
          alert(data);
          if (data == 'Success') {
            document.location.href = '/';
          }
        });
  });

  $('#add_course').click(function() {
    var course_name = $('#course').val();
    $.post(
        '/course/check_add_courses.php',
        {course_name: course_name},
        function(data) {
          alert(data);
          if (data == 'Success') {
            document.location.href = '/course/show_courses.php';
          }
        });
  });

  $('[name="choose_course"]').click(function() {
    var course_name = this.getAttribute('value');
    $.post(
        '/course/choose_courses_post_handler.php',
        {course_name: course_name},
        function(data) {
          alert(data);
          if (data == 'Success') {
            document.location.href = '/course/select_courses.php';
          }
        });
  });

  $('#query_grades').click(function() {
    var username = $('#username').val();
    var course = $('#course').val();
    if (course == '0') {
      alert('Please choose a course.');
      return false;
    }
    $.post(
        '/grade/query_grades_post_handler.php',
        {username: username, course_name: course},
        function(data) {
          var list = JSON.parse(data);
          if (list['result'] != 'Success') {
            alert(list['result']);
            return false;
          }
          DisplayGradeResults(list);
          return true;
        });
  });

  $('#modify-grades-btn').click(function() {
    Hide($('#modify-grades-btn'));
    Show($('#save-grades-btn'));
    Show($('#cancel-grades-btn'));

    Hide($('.show-score'));
    Show($('.input-score'));
    Disabled($('#query_grades'));
  });

  $('#cancel-grades-btn').click(function() {
    Show($('#modify-grades-btn'));
    Hide($('#save-grades-btn'));
    Hide($('#cancel-grades-btn'));

    Show($('.show-score'));
    Enabled($('.show-score'));
    Hide($('.input-score'));
    Enabled($('#query_grades'));
  });

  $('#save-grades-btn').click(function() {
    $('#save-grades-btn').html('Submitting');
    Disabled($('#save-grades-btn'));
    Hide($('#cancel-grades-btn'));

    var request = [];
    var valid = true;
    $('.input-score').each(function(index, grade) {
      if (grade.value < 0 || grade.value > 100) {
        valid = false;
      }
      request.push({
          'student': grade.name,
          'course': grade.placeholder,
          'score': grade.value});
    });
    if (!valid) {
      alert('Invalid scores.');
      return;
    }
    $.post(
        '/grade/modify_grades_post_handler.php',
        {request: request},
        function(data) {
          if (data == 'Success') {
            RefreshModifiedGrades();
          } else {
            alert(data);
          }
        });
  });

  $('#add-timetable-btn').click(function() {
    Show($('#add-timetable-selector'));
    Show($('#save-timetable-btn'));
    Show($('#cancel-timetable-btn'));
    Hide($('#add-timetable-btn'));
  });

  $('#cancel-timetable-btn').click(function() {
    Hide($('#add-timetable-selector'))
    Hide($('#cancel-timetable-btn'));
    Hide($('#save-timetable-btn'));
    Show($('#add-timetable-btn'));
  });

  $('#save-timetable-btn').click(function() {
    $('#save-timetable-btn').html('Submitting');
    Hide($('#cancel-timetable-btn'));
    Disabled($('#save-timetable-btn'));
    Disabled($('#add-timetable-selector select'));

    var weekday = $('#weekday').val();
    var lecture = $('#lecture').val();
    var course_name = $('#add-timetable-selector').attr('value');
    $.post(
        '/timetable/add_timetable_post_handler.php',
        {weekday: weekday, lecture: lecture, course: course_name},
        function(response) {
          if (response != 'Success') {
            alert(response);
            return false;
          }
          document.location.reload();
        });
  });

  $('#choose-homework-btn').click(function() {
    $('#homework-file-input')[0].click();
  });

  $('#homework-file-input').on('change', function() {
    $('#filename').html(this.value);
    Show($('#filename'));
  });

  $('#submit-homework-btn').click(function() {
    if ($('#course').val() == '0') {
      alert('Please choose a course.');
      return false;
    }
    if ($('#homework-file-input').val() == '') {
      alert('Please choose a file.')
      return false;
    }
    var course_name = $('#course').val();
    $.get(
        '/homework/submit_homework_validation.php',
        {course_name: course_name},
        function(response) {
          if (response != 'Success') {
            alert(response);
            return false;
          }
          SubmitHomework();
        });
  });

  $('#author-image').click(function() {
    if (!is_admin) {
      return;
    }
    Show($('#save-author-image'));
    Show($('#cancel-author-image'));
    $('#select-author-image')[0].click();
  });

  $('#cancel-author-image').click(function() {
    Hide($('#save-author-image'));
    Hide($('#cancel-author-image'));
  });

  $('#save-author-image').click(function() {
    Hide($('#cancel-author-image'));

    $('#save-author-image').html('submitting');
    Disabled($('#save-author-image'));
    $('#submit-author-image')[0].click();
  });

  function SubmitHomework() {
    $('#submit-homework-btn').html('Submitting');
    Disabled($('#submit-homework-btn'));
    $('#submit-homework-file')[0].click();
  }

  function RefreshModifiedGrades() {
    Hide($('#save-grades-btn'));
    Enabled($('#save-grades-btn'));
    $('#save-grades-btn').html('保存修改');

    Show($('#modify-grades-btn'));
    Enabled($('#query_grades'));
    $('#query_grades')[0].click();
    Show($('.show-score'));
    Hide($('.input-score'));
  }

  function DisplayGradeResults(data) {
    var result_div = document.getElementById('show_score');
    result_div.innerHTML =
        '<thead> \
           <th>课程名</th> \
           <th>学号</th> \
           <th>成绩</th> \
           <th>作业</th> \
         </thead>';
    for (var grade in data.list) {
      var input_name = data.list[grade].student + '@' + data.list[grade].course;
      result_div.innerHTML +=
          '<tr>' +
            '<td><a href="/course/description.php?course_name=' +
                data.list[grade].course + '" class="white-link">' +
                data.list[grade].course + '</a></td>' +
            '<td>' + data.list[grade].student + '</td>' +
            '<td>' +
              '<div class="show-score">' + data.list[grade].score + '</div>' +
              '<input name="' + data.list[grade].student +
                  '" class="input-score form-control" value="' +
                  data.list[grade].score + '" placeholder="' +
                  data.list[grade].course + '" required>' +
            '</td>' +
            '<td><a href="/homework/download_homework.php?' +
                'course_name=' + data.list[grade].course +
                '&student=' + data.list[grade].student +
                '" class="btn btn-primary download-homework"' +
                (data.list[grade].has_homework ? '>下载作业' :
                    'disabled>作业未提交') + '</a>' +
            '</td>' +
          '</tr>';
    }
    $('#modify-grades-btn').css({'display': 'block'});
  }

  function Show(element) {
    element.css({'display': 'block'});
  }
  function Hide(element) {
    element.css({'display': 'none'});
  }
  function Disabled(element) {
    element.attr('disabled', true);
  }
  function Enabled(element) {
    element.attr('disabled', false);
  }
  function SetVisible(element) {
    element.css({'visibility': 'visible'});
  }
  function SetHidden(element) {
    element.css({'visibility': 'hidden'});
  }
});
