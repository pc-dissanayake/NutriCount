<?php

namespace App\Enums;

enum HospitalRoomType: string
{
        // Wards
        case GENERAL_WARD = 'General Ward';
        case FEMALE_WARD = 'Female Ward';
        case MALE_WARD = 'Male Ward';
        case PRIVATE_WARD = 'Private Ward';
        case ISOLATION_WARD = 'Isolation Ward';
        case POSTNATAL_WARD = 'Postnatal Ward';
        case PRENATAL_WARD = 'Prenatal Ward';
        case SURGICAL_WARD = 'Surgical Ward';
        case MEDICAL_WARD = 'Medical Ward';
        case CHILDREN_WARD = 'Children\'s Ward';
        case GERIATRIC_WARD = 'Geriatric Ward';
        case MATERNAL_WARD = 'Maternal Ward';
        case INFANT_WARD = 'Infant Ward';
        case HIGH_RISK_WARD = 'High Risk Ward';

        // Operating Theatres
        case MAIN_OPERATING_THEATRE = 'Main Operating Theatre';
        case EMERGENCY_OPERATING_THEATRE = 'Emergency Operating Theatre';
        case DAY_SURGERY_THEATRE = 'Day Surgery Theatre';
        case OBSTETRIC_THEATRE = 'Obstetric Theatre';
        case ORTHOPEDIC_THEATRE = 'Orthopedic Theatre';
        case CARDIAC_THEATRE = 'Cardiac Operating Theatre';
        case NEUROSURGERY_THEATRE = 'Neurosurgery Theatre';
        case PLASTIC_SURGERY_THEATRE = 'Plastic Surgery Theatre';

        // Additional Units
        case DIALYSIS_UNIT = 'Dialysis Unit';
        case CHEMOTHERAPY_UNIT = 'Chemotherapy Unit';
        case RADIOTHERAPY_UNIT = 'Radiotherapy Unit';
        case ENDOSCOPY_UNIT = 'Endoscopy Unit';
        case PHYSIOTHERAPY_UNIT = 'Physiotherapy Unit';
        case OCCUPATIONAL_THERAPY_UNIT = 'Occupational Therapy Unit';
        case SPEECH_THERAPY_UNIT = 'Speech Therapy Unit';
        case REHABILITATION_UNIT = 'Rehabilitation Unit';
        case PALLIATIVE_CARE_UNIT = 'Palliative Care Unit';
        case PAIN_MANAGEMENT_UNIT = 'Pain Management Unit';
        case TRANSFUSION_UNIT = 'Transfusion Unit';
        case ISOLATION_UNIT = 'Isolation Unit';
        case OBSERVATION_UNIT = 'Observation Unit';
        case TRIAGE_UNIT = 'Triage Unit';

        // Specialized Units
    case CANCER_UNIT = 'Oncology Unit (Cancer Treatment)';
    case CARDIOLOGY_UNIT = 'Cardiology Unit';
    case NEUROLOGY_UNIT = 'Neurology Unit';
    case NEPHROLOGY_UNIT = 'Nephrology (Dialysis) Unit';
    case MENTAL_HEALTH_UNIT = 'Psychiatric/Mental Health Unit';
    case MATERNITY_HOSPITAL = 'Maternity Hospital';
    case PEDIATRIC_UNIT = 'Pediatric Unit';
    case BURN_UNIT = 'Burn Unit';
    case INFECTIOUS_DISEASE_UNIT = 'Infectious Disease Unit (e.g. IDH)';
    case RESPIRATORY_UNIT = 'Respiratory Diseases Unit (e.g. TB Clinic)';

    // Emergency and Surgery
    case EMERGENCY_UNIT = 'Emergency Treatment Unit (ETU)';
    case OPERATION_THEATRE = 'Operation Theatre';
    case MINOR_THEATRE = 'Minor Surgery Theatre';
    case ICU = 'Intensive Care Unit (ICU)';
    case NICU = 'Neonatal ICU (NICU)';
    case SICU = 'Surgical ICU';
    case HDU = 'High Dependency Unit';

    // OPD and Clinics
    case OPD = 'Outpatient Department (OPD)';
    case SPECIALIST_CLINIC = 'Specialist Clinic (ENT, Eye, Skin,)';
    case MEDICAL_CLINIC = 'Medical Clinic';
    case SURGICAL_CLINIC = 'Surgical Clinic';
    case MOH_OFFICE = 'MOH Office';
    case PHM_CLINIC = 'Public Health Midwife Clinic';
    case ANC_CLINIC = 'Antenatal Clinic';
    case IMMUNIZATION_CLINIC = 'Immunization Clinic';

    // Diagnostics
    case LABORATORY = 'Medical Laboratory';
    case RADIOLOGY_UNIT = 'Radiology Unit (X-Ray, CT, MRI)';
    case ULTRASOUND_UNIT = 'Ultrasound Unit';
    case BLOOD_BANK = 'Blood Bank';
    case ECG_ECHO_ROOM = 'ECG / Echo Room';
    case PATHOLOGY_LAB = 'Pathology / Histopathology Lab';

    // Administrative and Support Units
    case PHARMACY = 'Pharmacy';
    case MEDICAL_RECORDS = 'Medical Records Department';
    case CSSD = 'Central Sterile Supply Department (CSSD)';
    case LAUNDRY_UNIT = 'Laundry Unit';
    case KITCHEN_UNIT = 'Hospital Kitchen';
    case ENGINEERING_DEPARTMENT = 'Engineering / Maintenance Department';
    case ADMIN_OFFICE = 'Hospital Administration Office';
    case HR_UNIT = 'Human Resource Unit';

    public function label(): string
    {
        return $this->value;
    }
}
