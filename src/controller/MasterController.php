<?php

namespace controller;

require_once("src/controller/StudentController.php");
require_once("src/controller/TeacherController.php");

require_once("src/view/TeacherView.php");
require_once("src/view/LayoutView.php");
require_once("src/model/AFGrader.php");
require_once 'externals/php-markdown-lib/Michelf/MarkdownExtra.inc.php';
require_once "lang/".LANGUAGE.".php";

class MasterController {


  public static function doControll() : string {
    $s = new \Settings();

    $m = new \model\StudentModel($s);
    $sv = new \view\StudentView($s);

    $lv = new \view\LayoutView();


    try {
      $user = $sv->getUID();
      if ($m->isStudent($user) && \view\TeacherView::isTryingToGetTeacherAccess() == FALSE) {
      	$u = new StudentController($sv, $m, $s);
        $lv =  $u->doControl($user, $lv);
      } else if ($s->isTeacher($user) && \view\TeacherView::isTryingToGetTeacherAccess()) {
      	$u = new TeacherController($m, $sv, $s);
        $lv =  $u->doControl($user, $lv);
      } else {
        $lv =  $sv->getWrongUserIDNote($lv);
      }
      $lv->setMenu($sv->showMenu($user, $lv->getSubMenu(), $s->isTeacher($user)));
    } catch (\view\NoUIDException $e) {
      $lv= $sv->getWrongUserIDNote($lv);
    }
    return $lv->getHTMLBody();
  }
}
