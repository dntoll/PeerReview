<?php

/***********
ENGLISH
***********/
class Language {
public static function getLang() : array {

/*** HEADINGS ***/
$lang['headings']['score_top_heading'] = "Check score";
$lang['headings']['score_sub_heading'] = "Check how your document was scored by other students and also how your reviews was scored.";
$lang['headings']['upload_top_heading'] = "Upload document";
$lang['headings']['upload_sub_heading'] = "Write your document in the provided format and upload the file below.";
$lang['headings']['review_top_heading'] = "Review documents";
$lang['headings']['review_sub_heading'] = "You should review and grade other students documents. The more the better.";
$lang['headings']['feedback_top_heading'] = "View Reviews and Give Feedback on Reviews";
$lang['headings']['feedback_sub_heading'] = "During this phase you are to view the reviews your document has generated.";

/*** NAVIGATION AND SECTION ***/
$lang['navigation']['upload'] = "Upload your document";
$lang['navigation']['upload_uploaded_document'] = "The uploaded document";
$lang['navigation']['review'] = "Review";
$lang['navigation']['review_list_of_documents'] = "List of documents to review";
$lang['navigation']['review_document_to_review'] = "Document to review";
$lang['navigation']['review_saved_review'] = "Your saved review";
$lang['navigation']['review_form'] = "Review form";
$lang['navigation']['review_feedback'] = "Feedback on your review";
$lang['navigation']['feedback'] = "Give Feedback on Reviews";
$lang['navigation']['feedback_introduction'] = "Introduction";
$lang['navigation']['feedback_list_of_reviews'] = "List of reviews";
$lang['navigation']['feedback_read_review'] = "Read Review";
$lang['navigation']['feedback_give_feedback_on'] = "Give Feedback on";
$lang['navigation']['feedback_your_feedback_on'] = "Your Feedback on";
$lang['navigation']['score'] = "Check scores";
$lang['navigation']['score_section_document'] = "Your uploaded document score";
$lang['navigation']['score_section_review'] = "Your reviewer score";
$lang['navigation']['teacher'] = "Teacher View";

/***  SESSION  ***/
$lang['session']['no_user_found'] = "No user found";
$lang['session']['error'] = "This is an error!";
$lang['session']['no_active_session_info'] = "<h2>No active session.</h2> <p>This is probably due to that you have returned to the site when the session has ended. <a href=\"" . COURSE_PAGE_LINK . "\">Use the link provided by your course administrator on your course web space</a> to get access to PeerReview. If you have and still get this message, please contact your course administrator.</p>";

/*** REVIEW ***/
$lang['review']['review'] = "Review";
$lang['review']['nothing_to_review'] = "There are no documents for you to review at this point, you have to wait until more are posted";
$lang['review']['need_to_upload_first'] = "You need to upload a document before you can review other students documents";
$lang['review']['not_time_for_reviews'] = "You need to wait until the deadline for uploading documents has expired. The review phase starts:";
$lang['review']['no_reviews_yet'] = "You have not yet recieved any reviews on your document. You need to wait until reviews on your document are submitted before you can give feedback on those.";
$lang['review']['clarity'] = "Clarity";
$lang['review']['completeness'] = "Completeness";
$lang['review']['content'] = "Content";
$lang['review']['show_review_form_instructions'] = "You can do as many reviews as you like. Your reviews that get the highest feedback will determine your grade. Note that reviews that got feedback can no longer be changed.";
$lang['review']['review_document'] = "Document";
$lang['review']['state_has_feedback'] = "has received feedback";
$lang['review']['state_complete'] = "complete";
$lang['review']['state_not_complete'] = "not complete";
$lang['review']['start_first_review'] = "Start your first review";
$lang['review']['review_another'] = "Review another document";
$lang['review']['no_more_documents'] = "No more documents to review";
$lang['review']['complete_before_next'] = "Complete existing review to start a new review...";
$lang['review']['your_saved_review'] = "Your saved review";
$lang['review']['on_document'] = "on document";
$lang['review']['cannot_change_feedbacked_review'] = "You cannot change a review that has got feedback";
$lang['review']['complete_the_review'] = "You need to complete all fields and give grades on all categories";
$lang['review']['input_save_review'] = "Save Review";
$lang['review']['your_review_from'] = "your review from author";
$lang['review']['comment_on'] = "Comment on";

/*** FEEDBACK ***/
$lang['feedback']['not_time_for_feedback'] = "This is not the time for giving feedback to reviews, first you must make reviews. The feedback phase starts:";
$lang['feedback']['should_do_feedback_now'] = "It is time to look at your feedback";
$lang['feedback']['warning_need_to_submit_feedback'] = "Warning: You need to submit feedback for this review.";
$lang['feedback']['on_your_document'] = "on your document";
$lang['feedback']['reviewer_has_not_completed'] = "The reviewer has not completed the review.";
$lang['feedback']['warning_feedback_not_complete'] = "Warning: This Feedback is not complete.";
$lang['feedback']['heading_give_feedback'] = "Give feedback on the review:";
$lang['feedback']['information_feedback'] = "You should respond to the review you are given.";
$lang['feedback']['input_save_feedback'] = "Save review feedback";
$lang['feedback']['your_feedback_to_reviewer'] = "Your feedback to this reviewer";
$lang['feedback']['information_introduction'] = "During this phase you are to view the reviews your document has generated. You should also grade these reviews and provide a comment on the reasoning behind your grading. Note that you should not provide personal information in these comments and you are anonymous for the student that reviewed your document. However, you are not anonymous to the teacher.";
$lang['feedback']['your_reviews'] = "Your Reviews";
$lang['feedback']['complete'] = "Complete";
$lang['feedback']['not_complete'] = "Not complete";
$lang['feedback']['not_given_feedback'] = "You have not given feedback on this review";

/*** GRADING ***/
$lang['grading']['must_upload'] = "You must upload a document, do reviews and receive feedback to check the grading";
$lang['grading']['no_document'] = "You need to upload a document first";
$lang['grading']['page_heading_1'] = "Your uploaded document's score";
$lang['grading']['page_paragraph_1'] = "This table gives an overview over how your document was scored by other students. It also shows feedback from you (and your group) and if the teacher has graded the review. Any text that is <span class='strikethrough'>strikethrough</span> indicates that the teacher has either failed the review or the feedback. Those reviews or feedbacks are not counted when you are scored.";
$lang['grading']['table_heading_review_nr'] = "Review #";
$lang['grading']['table_heading_clarity'] = "Clarity";
$lang['grading']['table_heading_completeness'] = "Completeness";
$lang['grading']['table_heading_content'] = "Content";
$lang['grading']['table_heading_feedback'] = "Feedback";
$lang['grading']['table_heading_teacher_grading'] = "Teacher grading of review";
$lang['grading']['should_provide_feedback'] = "You should provide feedback on this review";
$lang['grading']['no_received_reviews'] = "You have not yet got any reviews on your document!";
$lang['grading']['median_score'] = "Your Median Score, (note that the final score may change after teacher has reviewed)";
$lang['grading']['page_heading_2'] = "Your review score";
$lang['grading']['page_paragraph_2'] = "This table gives an overview over how your reviews were received by the authors of those documents and if the teacher has graded the review or feedback. Any text that is <span class='strikethrough'>strikethrough</span> indicates that the teacher has either failed the review or the feedback. Those reviews or feedbacks are not counted when you are scored.";
$lang['grading']['review_table_heading_nr'] = "Your Review #";
$lang['grading']['review_table_heading_review_feedback'] = "Feedback on the review from the authors of the document";
$lang['grading']['review_table_heading_teacher_grading'] = "Teacher grading of Review";
$lang['grading']['review_table_your_review'] = "Your review";
$lang['grading']['review_table_no_feedback'] = "The authors of the document have not yet given feedback on this review";
$lang['grading']['review_table_review_not_complete'] = "The review is not complete, you should finish it before it can get feedback";
$lang['grading']['review_table_reviewer_score'] = "Your reviewer score(note that the final score may change after teacher has reviewed)";

/*** UPLOAD ***/
$lang['upload']['upload_phase_done'] = "You cannot change the uploaded document at this point. The deadline for uploading documents has passed";
$lang['upload']['upload_empty'] = "EMPTY";
$lang['upload']['warning'] = "WARNING";
$lang['upload']['one_more_upload_allowed'] = "Since the time for uploading has ended, only a single upload will be allowed! Make sure you double check that you upload a correct file.";
$lang['upload']['upload_heading_instructions'] = "Upload instructions";
$lang['upload']['upload_heading_form'] = "Upload form";
$lang['upload']['upload_deadline_instructions'] = "After the deadline only a one time upload can happen (no changes of uploaded file) and no garanties are made that you are going to get reviews. The uploaded file can be changed up until";
$lang['upload']['upload_form_instructions'] = "Select document to upload:";
$lang['upload']['upload_form_input'] = "Upload Document";

/*** TEACHER ***/
// Fix this if it is necessary...
$lang['teacher']['authors'] = "Authors";
$lang['teacher']['reviews'] = "Reviews";

/*** DOCUMENT ***/
$lang['document']['no_md_file'] = "No .md file uploaded yet";
$lang['document']['no_pdf_file'] = "No .pdf file uploaded yet";
$lang['document']['saved_file'] = "Saved file";

/*** PDF EXTRA ***/
$lang['pdf']['pdf_not_supported'] = "This browser does not support PDFs. Please download the PDF to view it:";
$lang['pdf']['pdf_anchor_text'] = "Download PDF";

/*** EXCEPTIONS ***/
$lang['exceptions']['missing_param'] = "Missing param";
$lang['exceptions']['file_no_content'] = "The file had no content";
$lang['exceptions']['upload_failed'] = "The upload failed";
$lang['exceptions']['md_wrong_type_file'] = "The wrong type of file, only text files that ends with [\".md\"] allowed";
$lang['exceptions']['pdf_wrong_type_file'] = "The wrong type of file, only text files that ends with [\".pdf\"] allowed";
$lang['exceptions']['corrupt_settings'] = "Corrupt Settings file. You should not be here... Contact your teacher";
$lang['exceptions']['unable_to_move_file'] = "Unable to move file";
$lang['exceptions']['uid_not_valid'] = "Not a valid unique ID";
$lang['exceptions']['find_document_fail'] = "Failed to find document";
$lang['exceptions']['find_document_fail_no_left'] = "Failed to find a random document, none left";
$lang['exceptions']['user_has_not_reviewed'] = "This user has not reviewed this document";
$lang['exceptions']['not_valid_grading'] = "Not a valid Grading";
$lang['exceptions']['should_never_get_here'] = "You should never get here... Contact your teacher!";
$lang['exceptions']['exception_no_review_exists'] = "No review exists";
$lang['exceptions']['exception_only_on_teacher_feedback'] = "Should only happen when we have feedback from teacher";
return $lang;
}
}
?>
