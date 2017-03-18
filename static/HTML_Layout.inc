
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">

    <title>Peer-Review</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <!--[if lte IE 8]><script src="static/stellar/assets/js/ie/html5shiv.js"></script><![endif]-->
    <?php
    //if (student\TeacherView::isTryingToGetTeacherAccess() == FALSE) {
      ?><link rel="stylesheet" href="./static/stellar/assets/css/main.css" /><?php
    //}
     ?>
    <!--[if lte IE 9]><link rel="stylesheet" href="./static/stellar/assets/css/ie9.css" /><![endif]-->
    <!--[if lte IE 8]><link rel="stylesheet" href="./static/stellar/assets/css/ie8.css" /><![endif]-->

    <link rel="stylesheet" href="./static/index.css" />
  </head>
  <body >
  <div id="wrapper">
<?php
  echo $content;
    ?>



  <footer id="footer">
            <section>
              <h2>Peer review system</h2>
              <p>Developed in 2016 by Daniel Toll in order to allow students to share and review documents anonymously.</p>

            </section>
            <section>
              <h2>Contact</h2>
              <dl class="alt">
                <dt>Email</dt>
                <dd><a href="mailto:daniel.toll@lnu.se">daniel.toll@lnu.se</a></dd>
                <dt>Slack</dt>
                <dd><a href="https://coursepress.slack.com/messages/2dv610/">https://coursepress.slack.com/messages/2dv610/</a></dd>
                
              </dl>

            </section>
            <p class="copyright">&copy; Untitled. Design: <a href="https://html5up.net">HTML5 UP</a>.</p>
          </footer>
</div>
   </body>

   <!-- Scripts -->
      <script src="./static/stellar/assets/js/jquery.min.js"></script>
      <script src="./static/stellar/assets/js/jquery.scrollex.min.js"></script>
      <script src="./static/stellar/assets/js/jquery.scrolly.min.js"></script>
      <script src="./static/stellar/assets/js/skel.min.js"></script>
      <script src="./static/stellar/assets/js/util.js"></script>
      <!--[if lte IE 8]><script src="./static/stellar/assets/js/ie/respond.min.js"></script><![endif]-->
      <script src="./static/stellar/assets/js/main.js"></script>
</html>
