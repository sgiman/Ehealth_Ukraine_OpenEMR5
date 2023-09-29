<?php
$roles = array (
"MIS USER"  => 
"legal_entity:read legal_entity:write legal_entity:mis_verify role:read user:request_factor user:approve_factor event:read client:read connection:read connection:write connection:refresh_secret connection:delete medical_program:read program_service:read",

"DOCTOR"  => 
"declaration:read declaration_request:approve declaration_request:read declaration_request:sign declaration_request:write division:read employee:read employee_request:approve employee_request:reject employee_request:read legal_entity:read otp:read otp:write person:read patient_summary:read encounter:write encounter:read episode:write episode:read job:read condition:read condition:write observation:read observation:write immunization:read immunization:write allergy_intolerance:read allergy_intolerance:write medication_statement:read medication_statement:write device:read device:write risk_assessment:read risk_assessment:write medication_dispense:read drugs:read medication_request:details medication_request:read medication_request:reject medication_request:resend medication_request_request:read medication_request_request:reject medication_request_request:sign medication_request_request:write diagnostic_report:read diagnostic_report:write diagnostic_report:cancel procedure:read procedure:write service_request:makeinprogress service_request:complete service_request:recall service_request:cancel service_request:write service_request:read service_request:use approval:create program_service:read medication_administration:read medication_administration:write healthcare_service:read employee_role:read person_request:write person_request:read authentication_method_request:write",

"HR" => 
"division:details division:write division:activate division:deactivate division:read employee:read employee:write employee:details employee:deactivate employee_request:approve employee_request:reject employee_request:write employee_request:read legal_entity:read healthcare_service:read healthcare_service:write employee_role:read employee_role:write equipment:write equipment:read",

"ASSISTANT"  => 
"division:read employee:read employee_request:approve employee_request:reject employee_request:read legal_entity:read otp:read otp:write person:read patient_summary:read diagnostic_report:read diagnostic_report:write diagnostic_report:cancel procedure:read procedure:write service_request:makeinprogress service_request:complete service_request:read service_request:use approval:create healthcare_service:read employee_role:read person_request:write person_request:read preperson:read preperson:write encounter:write episode:write job:read condition:write observation:write immunization:write allergy_intolerance:write medication_statement:write device:write risk_assessment:write medication_administration:write authentication_method_request:write merge_request:write merge_request:read",



"NHS ADMIN ADMIN" => 
"global_parameters:read global_parameters:write dictionary:write",

"NHS ADMIN SUPPORT" => 
"legal_entity:read employee:read user:request_factor user:approve_factor",
              
"NHS ADMIN MANAGER" => 
"legal_entity:read user:request_factor user:approve_factor medication_request:read medication_request:details medication_dispense:read",

"MITHRIL ADMIN"  => 
"app:read app:write app:delete token:read token:write token:delete user:read user:write user:delete role:read role:write role:delete client_type:read client_type:write client_type:delete client:read client:write client:delete user:block user:unblock user:request_factor user:approve_factor authentication_factor:write authentication_factor:read",

"NHS ADMIN ADMIN"  => 
"global_parameters:read global_parameters:write dictionary:write user:request_factor user:approve_factor",

"NHS LE TERMINATOR"  => 
"legal_entity:read legal_entity:deactivate",

"NHS ADMIN SIGNER"  => 
"capitation_report:read contract:read contract:terminate contract_request:create contract_request:read contract_request:sign contract_request:terminate contract_request:update division:details division:read employee:read legal_entity:nhs_verify legal_entity:read legal_entity:update private_contracts:read private_contracts:write",

"NHS ADMIN REIMBURSEMENT"  => 
"division:details division:read employee:read legal_entity:read medication_dispense:read medication_dispense:reject medication_request:details medication_request:read medication_request:reject medication_request:resend reimbursement_report:download",

"NHS LE VERIFIER"  => 
"division:details division:read employee:read legal_entity:merge legal_entity:nhs_verify legal_entity:read legal_entity_deactivation_job:read legal_entity_merge_job:read legal_entity:update related_legal_entities:read user:approve_factor user:request_factor",

"NHS REVIEWER"  => 
"person_merge:read merge_candidate:assign person_merge:write",

"UADDRESSES ADMIN"  => 
"address:read address:write user:request_factor user:approve_factor",

"ASSISTANT"  => 
"division:read employee:read employee_request:approve employee_request:reject employee_request:read legal_entity:read otp:read otp:write person:read patient_summary:read diagnostic_report:read diagnostic_report:write diagnostic_report:cancel procedure:read procedure:write service_request:makeinprogress service_request:complete service_request:read service_request:use approval:create healthcare_service:read employee_role:read person_request:write person_request:read preperson:read preperson:write encounter:write episode:write job:read condition:write observation:write immunization:write allergy_intolerance:write medication_statement:write device:write risk_assessment:write medication_administration:write authentication_method_request:write merge_request:write merge_request:read",

"ADMIN"  => 
"declaration:read declaration_request:approve declaration_request:reject declaration_request:read declaration_request:sign declaration_request:write capitation_report:read division:details division:read employee:read employee_request:approve employee_request:reject employee_request:read legal_entity:read otp:read otp:write person:read service_request:read service_request:use contract_request:sign contract_request:create contract_request:read contract_request:terminate contract_request:approve contract:read contract:write healthcare_service:read healthcare_service:write employee_role:read employee_role:write equipment:write equipment:read"

"NHS ADMIN PROGRAM MEDICATION"  => 
"division:read innm:read innm:write innm_dosage:deactivate innm_dosage:read innm_dosage:write legal_entity:read medical_program:deactivate medical_program:read medical_program:write medication:deactivate medication:read medication:write medication_dispense:reject program_medication:read program_medication:write reimbursement_report:download program_service:read program_service:write service_catalog:read service_catalog:write",

"NHS LE TERMINATOR"  => 
"legal_entity:read legal_entity:deactivate",

"OWNER"  => 
"employee_role:write employee_role:read healthcare_service:write healthcare_service:read declaration:read declaration_request:approve declaration_request:read declaration_request:reject declaration_request:write division:activate division:deactivate division:details division:read division:write employee:deactivate employee:details employee:read employee:write employee_request:approve employee_request:read employee_request:reject employee_request:write legal_entity:read otp:read otp:write person:read reimbursement_report:read secret:refresh capitation_report:read contract_request:create contract_request:read contract_request:terminate contract_request:approve contract_request:sign client:read connection:read connection:write connection:refresh_secret connection:delete related_legal_entities:read contract:read contract:write medication_request:details medication_dispense:read equipment:write equipment:read",

"RECEPTIONIST"  => 
"declaration:read declaration_request:approve declaration_request:reject declaration_request:read declaration_request:sign declaration_request:write capitation_report:read division:details division:read employee:read employee_request:approve employee_request:reject legal_entity:read otp:read otp:write person:read service_request:read service_request:use approval:create healthcare_service:read employee_role:read person_request:write person_request:read preperson:read preperson:write authentication_method_request:write merge_request:write merge_request:read encounter:write episode:write job:read condition:write observation:write immunization:write allergy_intolerance:write medication_statement:write device:write risk_assessment:write diagnostic_report:write procedure:write medication_administration:write",

"NHS ADMIN VERIFIER"  => 
"bl_user:read bl_user:write capitation_report:read declaration:approve declaration:read declaration:reject declaration:terminate declaration:write declaration_documents:read declarations_termination_job:read dictionary:read dictionary:write innm:read innm_dosage:read legal_entity:read medical_program:read medication:read medication_dispense:reject medication_request:reject medication_request:resend person:deactivate persons_deactivation_job:read person:read person:reset_authentication_method register:write register_entry:read related_legal_entities:read user:approve_factor user:request_factor persons_auth_reset_job:read",

"NHS ADMIN"  => 
"bl_user:deactivate bl_user:read bl_user:write capitation_report:read declaration:approve declaration:read declaration:reject declaration:terminate declarations_termination_job:read declaration:write declaration_documents:read dictionary:read dictionary:write division:read employee:read employee:deactivate employee_request:read employee_request:write global_parameters:read global_parameters:write innm:read innm:write innm_dosage:deactivate innm_dosage:read innm_dosage:write legal_entity:update legal_entity:merge legal_entity:nhs_verify legal_entity:read legal_entity_deactivation_job:read legal_entity_merge_job:read medical_program:deactivate medical_program:read medical_program:write medication:deactivate medication:read medication:write medication_dispense:read medication_dispense:reject medication_request:details medication_request:read party_user:read person:read person:deactivate persons_deactivation_job:read person:reset_authentication_method program_medication:read program_medication:write register:read register:write register_entry:read reimbursement_report:download related_legal_entities:read user:approve_factor user:request_factor program_service:write program_service:read service_catalog:read service_catalog:write persons_auth_reset_job:read private_contracts:read private_contracts:write",

"NHS ADMIN MONITORING"  => 
"bl_user:read capitation_report:read contract:read contract_request:read declaration:read declaration_documents:read dictionary:read division:read employee:read innm:read innm_dosage:read legal_entity:read medical_program:read medication:read medication_dispense:read medication_request:details medication_request:read party_user:read person:read program_medication:read register:read reimbursement_report:download related_legal_entities:read user:approve_factor user:request_factor program_service:read service_catalog:read private_contracts:read",

"PHARMACIST"  => 
"division:read employee:read employee_request:approve employee_request:reject employee_request:read legal_entity:read medication_dispense:process medication_dispense:read medication_dispense:write medication_dispense:reject medication_request:details healthcare_service:read employee_role:read",

"PHARMACY_OWNER"  => 
"employee_role:write employee_role:read healthcare_service:write healthcare_service:read division:activate division:deactivate division:details division:read division:write employee:deactivate employee:details employee:read employee:write employee_request:approve employee_request:read employee_request:reject employee_request:write legal_entity:read otp:read otp:write reimbursement_report:read secret:refresh medication_request:details client:read connection:read connection:write connection:refresh_secret connection:delete contract_request:create contract_request:read contract_request:terminate contract_request:approve contract_request:sign contract:read contract:write medication_dispense:read",

"CABINET"  => 
"medication_administration:read procedure:read diagnostic_report:read encounter:read service_request:read medication_statement:read device:read medication_dispense:read risk_assessment:read allergy_intolerance:read observation:read immunization:read condition:read episode:read medication_request:details medication_request:read cabinet:read employee_request:approve employee_request:reject user:request_factor user:approve_factor user:change_password app:authorize declaration_request:write declaration_request:read declaration_request:terminate declaration:read person:read person:write declaration:terminate app:read app:write app:delete authentication_factor:write authentication_factor:read profile:read",

"SPECIALIST"  => 
"division:read employee:read employee_request:approve employee_request:reject employee_request:read legal_entity:read otp:read otp:write person:read patient_summary:read encounter:write encounter:read episode:write episode:read job:read condition:read condition:write observation:read observation:write immunization:read immunization:write allergy_intolerance:read allergy_intolerance:write medication_statement:read medication_statement:write device:read device:write risk_assessment:read risk_assessment:write medication_dispense:read drugs:read medication_request:details medication_request:read medication_request:reject medication_request:resend medication_request_request:read medication_request_request:reject medication_request_request:sign medication_request_request:write diagnostic_report:read diagnostic_report:write diagnostic_report:cancel procedure:read procedure:write service_request:makeinprogress service_request:complete service_request:recall service_request:cancel service_request:write service_request:read service_request:use approval:create program_service:read medication_administration:read medication_administration:write healthcare_service:read employee_role:read person_request:write person_request:read preperson:read preperson:write authentication_method_request:write merge_request:write merge_request:sign merge_request:read composition:create composition:search composition:read composition:sign composition:cancel"

);

?>