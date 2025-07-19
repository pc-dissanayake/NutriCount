<?php

namespace App\Enums;

enum MedicalSpecialty: string
{
    case Medical = ' Medical';
    case GeneralMedicine = 'General Medicine';
    case Cardiology = 'Cardiology';
    case Dermatology = 'Dermatology';
    case Endocrinology = 'Endocrinology';
    case Gastroenterology = 'Gastroenterology';
    case Hematology = 'Hematology';
    case InfectiousDisease = 'Infectious Disease';
    case Nephrology = 'Nephrology';
    case Neurology = 'Neurology';
    case Oncology = 'Oncology';
    case Ophthalmology = 'Ophthalmology';
    case Orthopedics = 'Orthopedics';
    case Otolaryngology = 'Otolaryngology';
    case Pediatrics = 'Pediatrics';
    case Psychiatry = 'Psychiatry';
    case Pulmonology = 'Pulmonology';
    case Radiology = 'Radiology';
    case Rheumatology = 'Rheumatology';
    case Surgery = 'Surgery';
    case Urology = 'Urology';
    case ObstetricsGynecology = 'Obstetrics & Gynecology';
    case EmergencyMedicine = 'Emergency Medicine';
    case Anesthesiology = 'Anesthesiology';
    case HealthInformatics = 'Health Informatics';
    case Other = 'Other';
    case AllergyImmunology = 'Allergy & Immunology';
    case Pathology = 'Pathology';
    case PlasticSurgery = 'Plastic Surgery';
    case FamilyMedicine = 'Family Medicine';
    case Geriatrics = 'Geriatrics';
    case NuclearMedicine = 'Nuclear Medicine';
    case PhysicalMedicineRehabilitation = 'Physical Medicine & Rehabilitation';
    case ThoracicSurgery = 'Thoracic Surgery';
    case VascularSurgery = 'Vascular Surgery';
    case Dentistry = 'Dentistry';
    case ForensicMedicine = 'Forensic Medicine';
    case OccupationalMedicine = 'Occupational Medicine';
    case PalliativeCare = 'Palliative Care';
    case SportsMedicine = 'Sports Medicine';
    case TransfusionMedicine = 'Transfusion Medicine';
    case TropicalMedicine = 'Tropical Medicine';

    public static function options(): array
    {
        return array_column(self::cases(), 'value', 'value');
    }
}
