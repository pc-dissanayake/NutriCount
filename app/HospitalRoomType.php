<?php

namespace App\Enums;

enum HospitalRoomType: string
{
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
