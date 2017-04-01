<?php
/***********
SWEDISH
***********/
class Language {
public static function getLang() : array {
  /*** HEADINGS ***/
  $lang['headings']['score_top_heading'] = "Kontrollera betyg";
  $lang['headings']['score_sub_heading'] = "Kontrollera hur ditt dokument blev betygsatt av andra studenter och också hur dina granskningar blev betygsatta.";
  $lang['headings']['upload_top_heading'] = "Ladda upp dokument";
  $lang['headings']['upload_sub_heading'] = "Skriv ditt dokument i angivet format och ladda upp filen nedan.";
  $lang['headings']['review_top_heading'] = "Granska dokument";
  $lang['headings']['review_sub_heading'] = "Du ska granska och betygsätta andra elevers dokument. Ju fler desto bättre.";
  $lang['headings']['feedback_top_heading'] = "Se granskningar och ge återkoppling på dem.";
  $lang['headings']['feedback_sub_heading'] = "Under denna fas ska du ge återkoppling på de granskningar som ditt dokument har fått.";

  /*** NAVIGATION AND SECTION ***/
  $lang['navigation']['upload'] = "Ladda upp ditt dokument";
  $lang['navigation']['upload_uploaded_document'] = "Det uppladdade dokumentet";
  $lang['navigation']['review'] = "Granskning";
  $lang['navigation']['review_list_of_documents'] = "Lista över dokument att granska";
  $lang['navigation']['review_document_to_review'] = "Dokument att granska";
  $lang['navigation']['review_saved_review'] = "Din sparade granskning";
  $lang['navigation']['review_form'] = "Granskningsformulär";
  $lang['navigation']['review_feedback'] = "Återkoppling på din granskning";
  $lang['navigation']['feedback'] = "Ge återkoppling på granskningar";
  $lang['navigation']['feedback_introduction'] = "Introduktion";
  $lang['navigation']['feedback_list_of_reviews'] = "Lista med granskningar";
  $lang['navigation']['feedback_read_review'] = "Läs granskning";
  $lang['navigation']['feedback_give_feedback_on'] = "Ge återkoppling på";
  $lang['navigation']['feedback_your_feedback_on'] = "Din återkoppling av";
  $lang['navigation']['score'] = "Kontrollera betyg";
  $lang['navigation']['score_section_document'] = "Ditt dokuments betyg";
  $lang['navigation']['score_section_review'] = "Ditt granskningsbetyg";
  $lang['navigation']['teacher'] = "Lärarvy";

  /***  SESSION  ***/
  $lang['session']['no_user_found'] = "Hittade ingen användare";
  $lang['session']['error'] = "Detta är ett fel";
  $lang['session']['no_active_session_info'] = "<h2>Ingen aktiv session.</h2> <p>Detta är förmodligen för att du har återgått till sidan när sessionen har tagit slut. <a href=\"" . COURSE_PAGE_LINK . "\">Använd länken som angivits av din kursadministratör på din kurs webbplats</a> för att få tillgång till PeerReview. Om du har gjort det och fortfarande får upp detta meddelande. Var vänlig och kontakta din kursadministratör.</p>";


  /*** REVIEW ***/
  $lang['review']['review'] = "Granskning";
  $lang['review']['nothing_to_review'] = "Det finns för närvarande inga dokument för dig att granska just nu, du måste vänta till fler är uppladdade.";
  $lang['review']['need_to_upload_first'] = "Du måste ladda upp ett dokument innan du kan granska andra elevers dokument.";
  $lang['review']['not_time_for_reviews'] = "Du måste vänta till deadline för uppladdning av dokument. Granskningsfasen startar:";
  $lang['review']['no_reviews_yet'] = "Du har ännu inte fått några granskningar av ditt dokument. Du måste vänta till granskningar av ditt dokument är inlämnade innan du kan ge återkoppling på dessa.";
  $lang['review']['clarity'] = "Tydlighet";
  $lang['review']['completeness'] = "Helhet";
  $lang['review']['content'] = "Innehåll";
  $lang['review']['show_review_form_instructions'] = "Du kan göra så många granskningar du vill. De av dina granskningar som får högst återkoppling kommer att avgöra ditt betyg. Notera att granskningar som fått återkoppling inte längre går att ändra.";
  $lang['review']['review_document'] = "Dokument";
  $lang['review']['state_has_feedback'] = "har fått återkoppling";
  $lang['review']['state_complete'] = "komplett";
  $lang['review']['state_not_complete'] = "ej komplett";
  $lang['review']['start_first_review'] = "Påbörja din första granskning";
  $lang['review']['review_another'] = "Granska ett nytt dokument";
  $lang['review']['no_more_documents'] = "Inga fler dokument att granska";
  $lang['review']['complete_before_next'] = "Färdigställ den befintliga granskningen innan du börjar på en ny...";
  $lang['review']['your_saved_review'] = "Din sparade granskning";
  $lang['review']['on_document'] = "på dokument";
  $lang['review']['cannot_change_feedbacked_review'] = "Du kan inte ändra en granskning som har fått återkoppling";
  $lang['review']['complete_the_review'] = "Du måste färdigställa alla fält och betygsätta i alla kategorier.";
  $lang['review']['input_save_review'] = "Spara granskning";
  $lang['review']['your_review_from'] = "din granskning från författare";
  $lang['review']['comment_on'] = "Kommentera på";

  /*** FEEDBACK ***/
  $lang['feedback']['not_time_for_feedback'] = "Detta är inte tiden att ge återkoppling till granskningar, först måste du göra granskningar. Återkopplingsfasen startar:";
  $lang['feedback']['should_do_feedback_now'] = "Det är tid att se över din återkoppling";
  $lang['feedback']['warning_need_to_submit_feedback'] = "Varning: Du måste ge återkoppling på denna granskning.";
  $lang['feedback']['on_your_document'] = "på ditt dokument";
  $lang['feedback']['reviewer_has_not_completed'] = "Granskaren har inte färdigställt granskningen.";
  $lang['feedback']['warning_feedback_not_complete'] = "Varning: Denna återkoppling är inte komplett.";
  $lang['feedback']['heading_give_feedback'] = "Ge återkoppling på granskning:";
  $lang['feedback']['information_feedback'] = "Du ska återkoppla på den angivna granskningen.";
  $lang['feedback']['input_save_feedback'] = "Spara granskningsåterkoppling";
  $lang['feedback']['your_feedback_to_reviewer'] = "Din återkoppling till denna granskare";
  $lang['feedback']['information_introduction'] = "Under denna fas ska du läsa de granskningar som ditt dokument fått. Du ska också betygsätta dessa granskningar och lämna en kommentar om resonerandet bakom din betygsättning. Notera att du inte ska lämna någon personlig information i dessa kommentarer då du är anonym för eleven som granskade ditt dokument. Din lärare är du däremot inte anonym inför.";
  $lang['feedback']['your_reviews'] = "Dina granskningar";
  $lang['feedback']['complete'] = "Komplett";
  $lang['feedback']['not_complete'] = "Ej komplett";
  $lang['feedback']['not_given_feedback'] = "Du har inte gett återkoppling på denna granskning.";

  /*** GRADING ***/
  $lang['grading']['must_upload'] = "Du måste ladda upp ett dokument, göra granskningar och få återkoppling för att kontrollera betygsättningen.";
  $lang['grading']['no_document'] = "Du måste ladda upp ett dokument först";
  $lang['grading']['page_heading_1'] = "Ditt uppladdade dokuments betyg.";
  $lang['grading']['page_paragraph_1'] = "Denna tabell ger en överblick för hur ditt dokument har blivit betygsatt av andra elever. Det visar också återkoppling från dig (och din grupp) och om läraren har betygsatt granskningen.  All text som är <span class='strikethrough'>genomstruken</span> indikerar att läraren antingen har underkänt granskningen eller återkopplingen. Sådana granskningar eller återkopplingar räknas inte när du betygsätts.";
  $lang['grading']['table_heading_review_nr'] = "Granskning #";
  $lang['grading']['table_heading_clarity'] = "Tydlighet";
  $lang['grading']['table_heading_completeness'] = "Helhet";
  $lang['grading']['table_heading_content'] = "Innehåll";
  $lang['grading']['table_heading_feedback'] = "Återkoppling";
  $lang['grading']['table_heading_teacher_grading'] = "Lärarbedömning av granskning";
  $lang['grading']['should_provide_feedback'] = "Du ska ge återkoppling till denna granskning";
  $lang['grading']['no_received_reviews'] = "Du har ännu inte fått några granskningar av ditt dokument!";
  $lang['grading']['median_score'] = "Ditt 'medianbetyg', (notera att det slutgiltiga betyget kan förändras efter att läraren har granskat)";
  $lang['grading']['page_heading_2'] = "Ditt granskningsbetyg";
  $lang['grading']['page_paragraph_2'] = "Denna tabell ger en överblick av hur dina granskningar mottogs av författarna till dessa dokument och om läraren har betygsatt granskningen eller återkopplingen. All text som är <span class='strikethrough'>genomstruken</span> indikerar att läraren antingen har underkänt granskningen eller återkopplingen. Sådana granskningar eller återkopplingar räknas inte när du betygsätts.";
  $lang['grading']['review_table_heading_nr'] = "Din granskning #";
  $lang['grading']['review_table_heading_review_feedback'] = "Återkoppling på granskningen från författarna till dokumentet";
  $lang['grading']['review_table_heading_teacher_grading'] = "Lärarbedömning av granskning";
  $lang['grading']['review_table_your_review'] = "Din granskning";
  $lang['grading']['review_table_no_feedback'] = "Författarna till dokumentet har ännu inte get återkoppling på denna granskning.";
  $lang['grading']['review_table_review_not_complete'] = "Granskningen är inte komplett, du måste avsluta den innan den kan få återkoppling";
  $lang['grading']['review_table_reviewer_score'] = "Ditt granskningsbetyg (notera att det slutgiltiga betyget kan förändras efter att läraren har granskat)";

  /*** UPLOAD ***/
  $lang['upload']['upload_phase_done'] = "Du kan inte ändra det uppladdade dokumentet i detta läge. Deadline för uppladdning av dokument har passerat.";
  $lang['upload']['upload_empty'] = "TOMT";
  $lang['upload']['warning'] = "VARNING";
  $lang['upload']['one_more_upload_allowed'] = "Eftersom tiden för uppladdning har passerat kommer endast en enda uppladdning vara tillåten! Dubbelkolla att du laddar upp en korrekt fil..";
  $lang['upload']['upload_heading_instructions'] = "Uppladdningsinstruktioner";
  $lang['upload']['upload_heading_form'] = "Uppladdningsformulär";
  $lang['upload']['upload_deadline_instructions'] = "Efter deadline kan endast en engångsuppladdning ske (inga förändringar av uppladdad fil) och inga garantier lämnas för att ditt dokument ska bli granskat. Den uppladdade filen kan förändras till ";
  $lang['upload']['upload_form_instructions'] = "Välj dokument att ladda upp:";
  $lang['upload']['upload_form_input'] = "Ladda upp dokument";

  /*** TEACHER ***/
  // Fix this if it is necessary...
  $lang['teacher']['authors'] = "Författare";
  $lang['teacher']['reviews'] = "Granskningar";

  /*** DOCUMENT ***/
  $lang['document']['no_md_file'] = "Ingen .md fil har laddats upp";
  $lang['document']['no_pdf_file'] = "Ingen .pdf fil har laddats upp";
  $lang['document']['saved_file'] = "Filen sparad";

  /*** PDF EXTRA ***/
  $lang['pdf']['pdf_not_supported'] = "Denna webbläsaren stödjer inte PDF. Vänligen ladda hem PDF-filen för att öppna den:";
  $lang['pdf']['pdf_anchor_text'] = "Ladda ner PDF";

  /*** EXCEPTIONS ***/
  $lang['exceptions']['missing_param'] = "Saknad parameter";
  $lang['exceptions']['file_no_content'] = "Filen saknade innehåll";
  $lang['exceptions']['upload_failed'] = "Uppladdningen misslyckades";
  $lang['exceptions']['md_wrong_type_file'] = "Fel typ av fil. Endast filer som slutar med [\".md\"] är tillåtna";
  $lang['exceptions']['pdf_wrong_type_file'] = "Fel typ av fil. Endast filer som slutar med [\".pdf\"] är tillåtna";
  $lang['exceptions']['corrupt_settings'] = "Korrupt inställningsfil. Du borde inte vara här... Kontakta din lärare";
  $lang['exceptions']['unable_to_move_file'] = "Det går inte att flytta filen";
  $lang['exceptions']['uid_not_valid'] = "Ej giltigt unikt ID";
  $lang['exceptions']['find_document_fail'] = "Misslyckades att hitta dokument";
  $lang['exceptions']['find_document_fail_no_left'] = "Misslyckades att hitta ett slumpmässigt dokument, inga kvar.";
  $lang['exceptions']['user_has_not_reviewed'] = "Denna användare har inte granskat detta dokument";
  $lang['exceptions']['not_valid_grading'] = "Ej giltigt betyg";
  $lang['exceptions']['should_never_get_here'] = "Du borde aldrig komma hit... Kontakt din lärare! Genast!";
  $lang['exceptions']['exception_no_review_exists'] = "Ingen granskning existerar";
  $lang['exceptions']['exception_only_on_teacher_feedback'] = "Borde endast ske när vi har återkoppling från lärare";

  return $lang;
  }
}
?>
