<?php

namespace App\Livewire;

use App\Mail\ApplicationSubmitted;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\ApplicationHead;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ApplicationForm extends Component
{
    use WithFileUploads;

    public $applicationReasons = [
        ['id' => 'change-surname', 'label' => 'Change of Surname'],
        ['id' => 'change-address', 'label' => 'Change of Address'],
        ['id' => 'duplicate-cert', 'label' => 'Request for Duplicate Certificate'],
        ['id' => 'retention-reg', 'label' => 'Retention of Registration'],
        ['id' => 'additional-qual', 'label' => 'Entry of Additional Qualification'],
        ['id' => 'cancel-reg', 'label' => 'Cancellation of Registration'],
        ['id' => 'doctor-id', 'label' => 'Request for Doctor\'s Identity Card'],
        ['id' => 'provisional-reg', 'label' => 'Application for Provisional Registration'],
    ];

    public $collegeDistricts = [
        'Asansol, Paschim Bardhaman',
        'Kolkata',
        'Howrah',
        'Paschim Medinipur',
        'Birbhum',
        'Purba Bardhaman',
    ];

    // Form fields
    public $selectedReasons = [];
    public $name = '';
    public $fatherName = '';
    public $address = '';
    public $district = '';
    public $pincode = '';
    public $policeStation = '';
    public $aadhaar = '';
    public $dob = '';
    public $bloodGroup = '';
    public $mobile = '';
    public $email = '';
    public $regNumber = '';
    public $regDate = '';
    public $qualification = '';
    public $examination = '';
    public $heldIn = '';
    public $university = '';
    public $college = '';
    public $collegeDistrict = '';
    public $finalRollNo = '';
    public $term = '';
    public $universityRegNo = '';

    // File uploads
    public $uploadedFiles = [];

    // UI state
    public $isSubmitting = false;
    public $showSuccess = false;
    public $referenceId = '';

    public function mount()
    {
        $this->uploadedFiles = [];
    }

    public function updatedSelectedReasons()
    {
        $this->dispatch('reasonsUpdated');
    }

    public function toggleReason($reasonId)
    {
        if (in_array($reasonId, $this->selectedReasons)) {
            $this->selectedReasons = array_values(array_diff($this->selectedReasons, [$reasonId]));
        } else {
            $group1 = ['change-surname', 'change-address', 'duplicate-cert', 'retention-reg', 'additional-qual'];
            $group2 = ['cancel-reg', 'doctor-id', 'provisional-reg'];

            if (in_array($reasonId, $group1)) {
                $this->selectedReasons = array_values(array_diff($this->selectedReasons, $group2));
                $this->selectedReasons[] = $reasonId;
            } elseif (in_array($reasonId, $group2)) {
                $this->selectedReasons = array_values(array_diff($this->selectedReasons, array_merge($group1, $group2)));
                $this->selectedReasons[] = $reasonId;
            }
        }

        $this->updatedSelectedReasons();
    }

    public function getCheckboxStatesProperty()
    {
        $group1 = ['change-surname', 'change-address', 'duplicate-cert', 'retention-reg', 'additional-qual'];
        $group2 = ['cancel-reg', 'doctor-id', 'provisional-reg'];
        $states = [];
        $group1Selected = array_intersect($group1, $this->selectedReasons);
        $group2Selected = array_intersect($group2, $this->selectedReasons);

        foreach ($this->applicationReasons as $reason) {
            $reasonId = $reason['id'];
            $states[$reasonId] = [
                'checked' => in_array($reasonId, $this->selectedReasons),
                'disabled' => false
            ];
            if (in_array($reasonId, $group2) && !empty($group1Selected)) {
                $states[$reasonId]['disabled'] = true;
            }
            if (in_array($reasonId, $group1) && !empty($group2Selected)) {
                $states[$reasonId]['disabled'] = true;
            }
            if (in_array($reasonId, $group2) && !empty($group2Selected) && !in_array($reasonId, $group2Selected)) {
                $states[$reasonId]['disabled'] = true;
            }
        }
        return $states;
    }

    public function getVisibleFieldsProperty()
    {
        $fields = [
            'regNumber' => true, 'regDate' => true, 'qualification' => true, 'examination' => true,
            'heldIn' => true, 'finalRollNo' => false, 'term' => false, 'universityRegNo' => false, 'dob' => true, 'collegeDistrict'=>false,
        ];

        if (in_array('provisional-reg', $this->selectedReasons)) {
            $fields['regNumber'] = false;
            $fields['regDate'] = false;
            $fields['qualification'] = false;
            $fields['heldIn'] = false;
            $fields['examination'] = false;
            $fields['finalRollNo'] = true;
            $fields['term'] = true;
            $fields['universityRegNo'] = true;
            $fields['collegeDistrict'] = true;
        }

        if (in_array('doctor-id', $this->selectedReasons)) {
            $fields['examination'] = false;
            $fields['dob'] = false;
        }

        return $fields;
    }

    public function getUploadDefinitionsProperty()
    {
        return [
            'photo' => [
                'label' => 'Recent colour photo (JPEG, up to 200kb)',
                'accept' => '.jpg,.jpeg',
                'fileType' => 'JPEG',
                'maxSize' => 'Max 200KB',
                'validation' => 'image|max:200',
            ],
            'dobProof' => [
                'label' => 'Date of Birth Proof - Class X Admit Card/Pan Card/Class X pass certificate',
                'accept' => '.pdf',
                'fileType' => 'PDF',
                'maxSize' => 'Max 500KB',
                'validation' => 'file|mimes:pdf,jpg,jpeg,png|max:512',
            ],
            'regCert' => [
                'label' => 'Registration Certificate (PDF, up to 500kb)',
                'accept' => '.pdf',
                'fileType' => 'PDF',
                'maxSize' => 'Max 500KB',
                'validation' => 'file|mimes:pdf|max:512',
            ],
            'aadhaar' => [
                'label' => 'Aadhaar Card (PDF, up to 500kb)',
                'accept' => '.pdf',
                'fileType' => 'PDF',
                'maxSize' => 'Max 500KB',
                'validation' => 'file|mimes:pdf|max:512',
            ],
            'marksheet' => [
                'label' => 'Final DMS, DHMS, BHMS (Graded) BHMS Mark-sheet (PDF, up to 500kb)',
                'accept' => '.pdf',
                'fileType' => 'PDF',
                'maxSize' => 'Max 500KB',
                'validation' => 'file|mimes:pdf|max:512',
            ],
            'internshipCert' => [
                'label' => 'Internship Completion Certificate for DHMS, BHMS (Graded) & BHMS. (PDF, up to 500kb)',
                'accept' => '.pdf',
                'fileType' => 'PDF',
                'maxSize' => 'Max 500KB',
                'validation' => 'file|mimes:pdf|max:512',
            ],
            'mdCertificate' => [
                'label' => 'M.D. (Part-I, Part-II & Pass Certificate) / BHMS (Graded) (Part-I, Part-II & Pass Certificate) / Dip NIH (Part-I, Part-II & Pass Certificate). (PDF, up to 500kb)',
                'accept' => '.pdf',
                'fileType' => 'PDF',
                'maxSize' => 'Max 500KB',
                'validation' => 'file|mimes:pdf|max:512',
            ],
            // 'additionalQualCert' => [
            //     'label' => 'For entry of Additional Qualification: M.D. (Part-I, Part-II & Pass Certificate) / BHMS (Graded) (Part-I, Part-II & Pass Certificate) / Dip NIH (Part-I, Part-II & Pass Certificate). (PDF, up to 500kb)',
            //     'accept' => '.pdf',
            //     'fileType' => 'PDF',
            //     'maxSize' => 'Max 500KB',
            //     'validation' => 'required|file|mimes:pdf|max:512',
            // ],
            'marriageCert' => [
                'label' => 'Marriage Registration Certificate (for surname change) / Affidavit of 1st Class Judicial Magistrate (for Name Surname correction) / Police Missing Diary (for Duplicate). (PDF, up to 500kb)',
                'accept' => '.pdf',
                'fileType' => 'PDF',
                'maxSize' => 'Max 500KB',
                'validation' => 'file|mimes:pdf|max:512',
            ],
            'signature' => [
                'label' => 'Full signature. (PDF, up to 500kb)',
                'accept' => '.pdf',
                'fileType' => 'PDF',
                'maxSize' => 'Max 500KB',
                'validation' => 'file|mimes:pdf|max:512',
            ],
            'studentRegCert' => [
                'label' => 'Student Registration Certificate of Health University (PDF, up to 500kb)',
                'accept' => '.pdf',
                'fileType' => 'PDF',
                'maxSize' => 'Max 500KB',
                'validation' => 'file|mimes:pdf|max:512',
            ],
            'classXIIMarksheet' => [
                'label' => 'Class XII Marksheet both side(PDF, up to 500kb)',
                'accept' => '.pdf',
                'fileType' => 'PDF',
                'maxSize' => 'Max 500KB',
                'validation' => 'file|mimes:pdf|max:512',
            ],
            // NEW FIELDS FOR PROVISIONAL REGISTRATION
            'bhmsMarksheets' => [
                'label' => 'All BHMS Marksheets (1st BHMS to 4th BHMS in proper order) (PDF, up to 500kb)',
                'accept' => '.pdf',
                'fileType' => 'PDF',
                'maxSize' => 'Max 500KB',
                'validation' => 'file|mimes:pdf|max:512',
            ],
            'principalDeclaration' => [
                'label' => 'Declaration of Principal cum Hospital Superintendent (PDF, up to 500 KB)',
                'accept' => '.pdf',
                'fileType' => 'PDF',
                'maxSize' => 'Max 500KB',
                'validation' => 'file|mimes:pdf|max:512',
            ],
            'paymentProof' => [
                'label' => 'QR Code / UPI Payment transaction document. (PDF, up to 500kb)',
                'accept' => '.pdf',
                'fileType' => 'PDF',
                'maxSize' => 'Max 500KB',
                'validation' => 'required|file|mimes:pdf|max:512',
            ],
        ];
    }

    public function getRequiredUploadsProperty()
    {
        if (empty($this->selectedReasons)) {
            return [];
        }

        // FOR PROVISIONAL REGISTRATION - ADD THE TWO NEW FIELDS
        if (in_array('provisional-reg', $this->selectedReasons)) {
            return [
                'photo',
                'dobProof',
                'aadhaar',
                'classXIIMarksheet',
                'studentRegCert',
                'bhmsMarksheets',        // NEW FIELD
                'principalDeclaration',   // NEW FIELD
                'signature',
                'paymentProof'
            ];
        }

        if (in_array('cancel-reg', $this->selectedReasons)) {
            return ['photo', 'dobProof', 'aadhaar', 'regCert', 'marksheet', 'internshipCert', 'signature', 'paymentProof'];
        }

        if (in_array('doctor-id', $this->selectedReasons)) {
            return ['photo', 'aadhaar', 'regCert', 'signature', 'paymentProof'];
        }

        $uploads = [
            'photo',
            'dobProof',
            'aadhaar',
            'regCert',
            'marksheet',
            'internshipCert',
            'mdCertificate',
            'signature',
            'paymentProof'
        ];

        // Group of 5 reasons
        $group1 = ['change-surname', 'change-address', 'duplicate-cert', 'retention-reg', 'additional-qual'];

        if (count(array_intersect($group1, $this->selectedReasons)) > 0) {
            //$uploads[] = 'additionalQualCert';
            $uploads[] = 'marriageCert';
        }

        return array_unique($uploads);
    }

    public function submit()
    {
        Log::info('Form submission started', ['selectedReasons' => $this->selectedReasons]);

        $this->isSubmitting = true;
        $this->dispatch('submissionStarted');

        try {
            $visibleFields = $this->getVisibleFieldsProperty();

            $rules = [
                // 'selectedReasons' => 'required|array|min:1',
                // 'name' => 'required|string|max:255',
                // 'fatherName' => 'required|string|max:255',
                // 'address' => 'required|string',
                // 'district' => 'required|string|max:255',
                // 'pincode' => 'required|string|size:6',
                // 'policeStation' => 'required|string|max:255',
                // 'aadhaar' => 'required|string|size:12',
                // 'mobile' => 'required|string|size:10',
                // 'email' => 'required|email',
                // 'university' => 'required|string|max:255',
                // 'college' => 'required|string|max:255',
                // 'bloodGroup' => 'nullable|string|max:10',
            ];

            $messages = [
                'selectedReasons.required' => 'Please select at least one application reason.',
                'name.required' => 'Name is required.',
                'fatherName.required' => 'Father\'s name is required.',
                'address.required' => 'Address is required.',
                'district.required' => 'District is required.',
                'pincode.required' => 'Pincode is required.',
                'pincode.size' => 'Pincode must be exactly 6 digits.',
                'policeStation.required' => 'Police station is required.',
                'aadhaar.required' => 'Aadhaar number is required.',
                'aadhaar.size' => 'Aadhaar number must be exactly 12 digits.',
                'mobile.required' => 'Mobile number is required.',
                'mobile.size' => 'Mobile number must be exactly 10 digits.',
                'email.required' => 'Email is required.',
                'email.email' => 'Please enter a valid email address.',
                'university.required' => 'University name is required.',
                'college.required' => 'College name is required.',
                'collegeDistrict.required' => 'College district is required.',
                'dob.required' => 'Date of birth is required.',
                'dob.date_format' => 'Date of birth must be in DD/MM/YYYY format.',
                'regNumber.required' => 'Registration number is required.',
                'regDate.required' => 'Registration date is required.',
                'regDate.date_format' => 'Registration date must be in DD/MM/YYYY format (e.g., 15/08/2020).',
                'qualification.required' => 'Qualification is required.',
                'examination.required' => 'Examination is required.',
                'heldIn.required' => 'Held in year is required.',
                'finalRollNo.required' => 'Final roll number is required.',
                'term.required' => 'Term is required.',
                'universityRegNo.required' => 'University registration number is required.',
            ];

            // if ($visibleFields['dob']) {
            //     $rules['dob'] = 'required|date_format:d/m/Y';
            // }

            // if ($visibleFields['regNumber']) {
            //     $rules['regNumber'] = 'required|string|max:255';
            // }

            // if ($visibleFields['regDate']) {
            //     $rules['regDate'] = 'required|date_format:d/m/Y';
            // }

            // if ($visibleFields['qualification']) {
            //     $rules['qualification'] = 'required|string|max:255';
            // }

            // if ($visibleFields['examination']) {
            //     $rules['examination'] = 'required|string|max:255';
            // }

            // if ($visibleFields['heldIn']) {
            //     $rules['heldIn'] = 'required|string|max:255';
            // }

            // if ($visibleFields['finalRollNo']) {
            //     $rules['finalRollNo'] = 'required|string|max:255';
            // }

            // if ($visibleFields['term']) {
            //     $rules['term'] = 'required|string|max:255';
            // }

            // if ($visibleFields['universityRegNo']) {
            //     $rules['universityRegNo'] = 'required|string|max:255';
            // }

            // Validate required uploads
            foreach ($this->requiredUploads as $uploadId) {
                $uploadDef = $this->uploadDefinitions[$uploadId];
                if ($uploadId === 'photo') {
                    $rules['uploadedFiles.' . $uploadId] = 'image|max:200';
                    $messages['uploadedFiles.' . $uploadId . '.required'] = 'Photo is required.';
                    $messages['uploadedFiles.' . $uploadId . '.image'] = 'Photo must be an image file.';
                    $messages['uploadedFiles.' . $uploadId . '.max'] = 'Photo must not exceed 200KB.';
                } else {
                    $rules['uploadedFiles.' . $uploadId] = 'file|mimes:pdf,jpg,jpeg,png|max:512';
                    $messages['uploadedFiles.' . $uploadId . '.required'] = $uploadDef['label'] . ' is required.';
                    $messages['uploadedFiles.' . $uploadId . '.file'] = 'This must be a valid file.';
                    $messages['uploadedFiles.' . $uploadId . '.mimes'] = 'File must be PDF, JPG, JPEG, or PNG format.';
                    $messages['uploadedFiles.' . $uploadId . '.max'] = 'File must not exceed 500KB.';
                }
            }

            $this->validate($rules, $messages);

            DB::beginTransaction();

            $referenceId = 'CHM' . time() . rand(100, 999);
            $applicationHead = ApplicationHead::create(['reference_id' => $referenceId]);

            $applicationHead->details()->create([
                'name' => $this->name,
                'father_name' => $this->fatherName,
                'address' => $this->address,
                'district' => $this->district,
                'pincode' => $this->pincode,
                'police_station' => $this->policeStation,
                'aadhaar' => $this->aadhaar,
                'dob' => ($visibleFields['dob'] && !empty($this->dob))? Carbon::createFromFormat('d/m/Y', $this->dob)->format('Y-m-d'): null,
                // 'dob' => $visibleFields['dob'] ? Carbon::createFromFormat('d/m/Y', $this->dob)->format('Y-m-d') : null,
                'blood_group' => $this->bloodGroup,
                'mobile' => $this->mobile,
                'email' => $this->email,
                'reg_number' => $visibleFields['regNumber'] ? $this->regNumber : null,
                'reg_date' => $visibleFields['regDate'] && !empty($this->regDate) ? Carbon::createFromFormat('d/m/Y', $this->regDate)->format('Y-m-d') : null,
                'qualification' => $visibleFields['qualification'] ? $this->qualification : null,
                'examination' => $visibleFields['examination'] ? $this->examination : null,
                'held_in' => $visibleFields['heldIn'] ? $this->heldIn : null,
                'university' => $this->university,
                'college' => $this->college,
                'college_district' => $this->collegeDistrict,
                'final_roll_no' => $visibleFields['finalRollNo'] ? $this->finalRollNo : null,
                'term' => $visibleFields['term'] ? $this->term : null,
                'university_reg_no' => $visibleFields['universityRegNo'] ? $this->universityRegNo : null,
            ]);

            foreach ($this->selectedReasons as $reasonId) {
                $applicationHead->reasons()->create(['reason_id' => $reasonId]);
            }

            foreach ($this->requiredUploads as $uploadId) {
                if (isset($this->uploadedFiles[$uploadId])) {
                    $file = $this->uploadedFiles[$uploadId];
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '_' . $uploadId . '.' . $extension;

                    $path = $file->storeAs(
                        "applications/{$applicationHead->id}",
                        $filename,
                        'public'
                    );

                    $applicationHead->media()->create([
                        'document_type' => $uploadId,
                        'url' => $path,
                        'original_name' => $originalName,
                        'ext' => $extension,
                    ]);
                }
            }

            DB::commit();

            $this->referenceId = $referenceId;
            $this->showSuccess = true;
            $this->dispatch('submissionCompleted', ['referenceId' => $this->referenceId]);

            try {
                Mail::to($this->email)->send(new ApplicationSubmitted($applicationHead));
                Log::info('Email sent successfully to: ' . $this->email);
            } catch (\Exception $mailException) {
                Log::error('Email sending failed: ' . $mailException->getMessage());
            }

            $this->resetForm();

            // Redirect to thank you page
            return redirect()->route('thank-you.show', ['referenceId' => $referenceId]);

            } catch (ValidationException $e) {
                DB::rollBack();
                $this->isSubmitting = false;
                $this->dispatch('scrollToError');
                throw $e;
            }
        catch (\Exception $e) {
            DB::rollBack();
            Log::error('Application Submission Error: ' . $e->getMessage() . ' on line ' . $e->getLine());
            session()->flash('error', 'There was an error submitting your application. Please try again.');
            $this->dispatch('submissionError');
        }
        $this->isSubmitting = false;

    }

    private function resetForm()
    {
        $this->reset([
            'selectedReasons', 'name', 'fatherName', 'address', 'district', 'pincode', 'policeStation',
            'aadhaar', 'dob', 'bloodGroup', 'mobile', 'email', 'regNumber', 'regDate',
            'qualification', 'examination', 'heldIn', 'university', 'college', 'collegeDistrict', 'finalRollNo',
            'term', 'universityRegNo'
        ]);
        $this->uploadedFiles = [];
        $this->mount();
    }

    public function hideSuccess()
    {
        $this->showSuccess = false;
    }

    public function render()
    {
        return view('livewire.application-form');
    }
}
