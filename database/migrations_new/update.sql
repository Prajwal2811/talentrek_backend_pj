table name jobseekers => jobseekers_and_exapt (role:jobseeker/expat)
table name recruiter_jobseeker_shortlist => recruiter_jobseeker_and_expat_shortlist (column name jobseeker_id => jobseeker_or_expat_id, extra column as 'role')
table name jobseeker_assessment_data => jobseeker_and_expat_assessment_data (column name jobseeker_id => jobseeker_or_expat_id, extra column as 'role')
table name reviews => NO CHNAGE IN NAME OF TABLE  (column name jobseeker_id => jobseeker_or_expat_id, extra column as 'role')
table name jobseeker_saved_booking_session => jobseeker_or_expat_saved_booking_session (column name jobseeker_id => jobseeker_or_expat_id, extra column as 'role')
table name skills => NO CHNAGE IN NAME OF TABLE  (column name jobseeker_id => jobseeker_or_expat_id, extra column as 'role')
table name material_purchases_payment_record => NO CHNAGE IN NAME OF TABLE  (column name jobseeker_id => jobseeker_or_expat_id, extra column as 'role')
table name jobseeker_sessions_booking_payment_request => jobseeker_and_expat_sessions_booking_payment_request (column name jobseeker_id => jobseeker_or_expat_id, extra column as 'role')
table name material_purchases_payment_request => NO CHNAGE IN NAME OF TABLE  (column name jobseeker_id => jobseeker_or_expat_id, extra column as 'role')
table name team_course_members => NO CHNAGE IN NAME OF TABLE  (column name main_jobseeker_id => main_jobseeker_or_expat_id,  column name jobseeker_id => jobseeker_or_expat_id, extra column as 'role')
table name jobseeker_assessment_status => jobseeker_or_expat_assessment_status (column name jobseeker_id => jobseeker_or_expat_id, extra column as 'role')
table name jobseeker_cart_items => jobseeker_or_expat_cart_items (column name jobseeker_id => jobseeker_or_expat_id, extra column as 'role')
table name jobseeker_training_material_purchases => jobseeker_or_expat_training_material_purchases (column name jobseeker_id => jobseeker_or_expat_id, extra column as 'role')







$table->enum('role', ['jobseeker', 'expat'])->nullable();
