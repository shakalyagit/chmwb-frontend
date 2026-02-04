<div class="w-full p-2 sm:p-4 lg:p-6">
    <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 lg:p-8 w-full">
        <!-- Header -->
        <div class="text-center mb-8 sm:mb-12">
            <div class="w-full flex justify-center mb-4 sm:mb-6">
                <img src="{{ asset('images/Screenshot_192.png') }}"
                    class="rounded-full h-20 w-20 sm:h-24 sm:w-24 lg:h-32 lg:w-32 object-contain border-2 sm:border-4 border-blue-500 shadow-lg"
                    alt="Council of Homoeopathic Medicine, West Bengal  Logo" />
            </div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-2 sm:mb-4 px-2">
                Council of Homoeopathic Medicine, West Bengal
            </h1>
            <p class="text-base sm:text-lg font-semibold text-blue-600 mb-4 sm:mb-6">
                Doctor's & Student's Application Portal
            </p>
            <div
                class="bg-blue-50 border-2 border-blue-200 rounded-lg sm:rounded-xl p-4 sm:p-6 max-w-full sm:max-w-3xl mx-auto mt-3 bg-warning-subtle">
                <p class="text-sm sm:text-base text-gray-800 leading-relaxed text-danger">
                    <strong>Welcome! Start by selecting the reason(s) for your application, and the form will
                        show you exactly what information is needed. </strong>
                </p>

            </div>
            <div
                class="bg-yellow-50 border-2 border-yellow-200 rounded-lg sm:rounded-xl p-4 sm:p-6 max-w-full sm:max-w-3xl mx-auto mt-3">

                <p class=" text-start text-sm sm:text-base text-red-800 leading-relaxed">
                    <strong>Instructions :</strong> After final submission of the online application form, one print out
                    of the online application form & online payment receipt must be sent along with the “Original
                    Doctor’s Registration Certificate” / " Original Police Missing Diary" for Duplicate Registration /
                    "Affidavit of First Class Judicial Magistrate" for Corrections of Name or Surname / "Original
                    Declaration of Head of the Institution or Hospital Superintendent" for Provisional Registration
                    Certificate to Council of Homoeopathic Medicine, West Bengal via Speed Post / Physically to the
                    following address : The Registrar, Council of Homoeopathic Medicine, West Bengal, 9/1B, Mahatma
                    Gandhi Road (1st Floor), Kolkata - 700 009.
                </p>
            </div>
        </div>

        <!-- Success Message -->
        @if ($showSuccess)
            <div class="fixed top-4 right-4 bg-green-600 text-white p-6 rounded-xl shadow-lg z-50 max-w-md">
                <div class="flex items-center">
                    <div class="text-2xl mr-3">✅</div>
                    <div>
                        <h4 class="font-bold text-lg">Application Submitted Successfully!</h4>
                        <p class="text-sm mt-1">Reference ID: {{ $referenceId }}</p>
                        <p class="text-sm mt-1">You will receive a confirmation email shortly.</p>
                    </div>
                </div>
                <button wire:click="$set('showSuccess', false)"
                    class="absolute top-2 right-2 text-white hover:text-gray-200">
                    ×
                </button>
            </div>
        @endif

        <!-- Error Message -->
        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Global Validation Errors Summary -->
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-red-800 font-semibold">Please correct the following errors:</h3>
                </div>
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- <form wire:submit="submit" class="space-y-6 sm:space-y-8"> --}}
        <form wire:submit="submit" class="space-y-6 sm:space-y-8" enctype="multipart/form-data" novalidate>
            <!-- Step 1: Reason for Application -->
            <div class="form-section">
                <div class="flex items-center mb-4">
                    <div
                        class="bg-blue-600 text-white rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center text-base sm:text-lg font-bold mr-4">
                        1
                    </div>
                    <h2 class="section-header flex-1 mb-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 text-blue-600 inline" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Select Reason for Application

                    </h2>
                </div>
                <p class="help-text">
                    <strong>Important:</strong> Please select one or more reasons
                    below. The form will automatically show you the required fields
                    and documents based on your selection.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
                    @foreach ($applicationReasons as $reason)
                        @php
                            $state = $this->checkboxStates[$reason['id']];
                        @endphp
                        <label
                            class="checkbox-item cursor-pointer {{ $state['checked'] ? 'checked' : '' }} {{ $state['disabled'] ? 'disabled' : '' }}">
                            <input type="checkbox" wire:click="toggleReason('{{ $reason['id'] }}')"
                                {{ $state['checked'] ? 'checked' : '' }} {{ $state['disabled'] ? 'disabled' : '' }}
                                class="w-5 h-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $state['disabled'] ? 'opacity-50 cursor-not-allowed' : '' }}" />
                            <div class="flex-1">
                                <span
                                    class="font-medium text-gray-900 {{ $state['disabled'] ? 'text-gray-400' : '' }}">{{ $reason['label'] }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('selectedReasons')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Step 2: Personal Information -->
            <div class="form-section">
                <div class="flex items-center mb-4">
                    <div
                        class="bg-blue-600 text-white rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center text-base sm:text-lg font-bold mr-4">
                        2
                    </div>
                    <h2 class="section-header flex-1 mb-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 text-blue-600 inline" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Personal Information
                    </h2>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                    <div>
                        <label for="name" class="form-label">
                            Name

                        </label>
                        <input type="text" id="name" wire:model="name" class="form-input"
                            placeholder="Enter your full name" required />
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="fatherName" class="form-label">
                            Father's name
                        </label>
                        <input type="text" id="fatherName" wire:model="fatherName" class="form-input"
                            placeholder="Enter father's full name" />
                        @error('fatherName')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="lg:col-span-2">
                        <label for="address" class="form-label">
                            Address
                        </label>
                        <textarea id="address" wire:model="address" rows="3" class="form-textarea"
                            placeholder="Enter your complete address (House/Flat No., Street, Area)"></textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="district" class="form-label">
                            District
                        </label>
                        <input type="text" id="district" wire:model="district" class="form-input"
                            placeholder="Enter your district" />
                        @error('district')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="pincode" class="form-label">
                            Pincode
                        </label>
                        <input type="text" id="pincode" wire:model="pincode" class="form-input"
                            placeholder="6-digit pincode (e.g., 700001)" maxlength="6" />
                        @error('pincode')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="lg:col-span-2">
                        <label for="policeStation" class="form-label">
                            Police Station
                        </label>
                        <input type="text" id="policeStation" wire:model="policeStation" class="form-input"
                            placeholder="Enter your nearest police station" />
                        @error('policeStation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="aadhaar" class="form-label">
                            Aadhaar Number
                        </label>
                        <input type="text" id="aadhaar" wire:model="aadhaar" class="form-input"
                            placeholder="123456789012" maxlength="12" />
                        @error('aadhaar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    @if ($this->visibleFields['dob'])
                        <div>
                            <label for="dob" class="form-label">
                                Date of birth (DD/MM/YYYY)

                            </label>
                            <input type="text" id="dob" wire:model="dob"
                                placeholder="DD/MM/YYYY (e.g., 15/08/1990)" class="form-input" />
                            @error('dob')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                    <div class="lg:col-span-2">
                        <label for="bloodGroup" class="form-label">
                            Blood Group

                        </label>
                        <input type="text" id="bloodGroup" wire:model="bloodGroup" class="form-input max-w-xs"
                            placeholder="e.g., A+, B-, O+, AB-" />
                    </div>
                </div>
            </div>

            <!-- Step 3: Contact Information -->
            <div class="form-section">
                <div class="flex items-center mb-4">
                    <div
                        class="bg-blue-600 text-white rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center text-base sm:text-lg font-bold mr-4">
                        3
                    </div>
                    <h2 class="section-header flex-1 mb-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 text-blue-600 inline" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                            </path>
                        </svg>
                        Contact Information
                    </h2>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                    <div>
                        <label for="mobile" class="form-label">
                            Mobile Number
                        </label>
                        <input type="tel" id="mobile" wire:model="mobile" class="form-input"
                            placeholder="10-digit mobile number (e.g., 9876543210)" maxlength="10" />
                        @error('mobile')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="form-label">
                            E-mail ID
                        </label>
                        <input type="email" id="email" wire:model="email" class="form-input"
                            placeholder="your.email@example.com" />
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Step 4: Academic & Registration Information -->
            <div class="form-section">
                <div class="flex items-center mb-4">
                    <div
                        class="bg-blue-600 text-white rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center text-base sm:text-lg font-bold mr-4">
                        4
                    </div>
                    <h2 class="section-header flex-1 mb-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 text-blue-600 inline" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z">
                            </path>
                        </svg>
                        Academic & Registration Information
                    </h2>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 lg:gap-6">
                    @if ($this->visibleFields['regNumber'])
                        <div>
                            <label for="regNumber" class="form-label">
                                Registration number

                            </label>
                            <input type="text" id="regNumber" wire:model="regNumber" class="form-input"
                                placeholder="Enter your registration number" />
                            @error('regNumber')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                    @if ($this->visibleFields['regDate'])
                        <div>
                            <label for="regDate" class="form-label">
                                Date of registration (DD/MM/YYYY)

                            </label>
                            <input type="text" id="regDate" wire:model="regDate"
                                placeholder="DD/MM/YYYY (e.g., 15/06/2020)" class="form-input" />
                        </div>
                    @endif
                    @if ($this->visibleFields['qualification'])
                        <div>
                            <label for="qualification" class="form-label">
                                Name of the Qualification

                            </label>
                            <input type="text" id="qualification" wire:model="qualification" class="form-input"
                                placeholder="e.g., BHMS, DHMS, MD(Hom)" />
                            @error('qualification')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                    @if ($this->visibleFields['examination'])
                        <div class="lg:col-span-2 xl:col-span-3">
                            <label for="examination" class="form-label">
                                Examination passed with subject

                            </label>
                            <input type="text" id="examination" wire:model="examination" class="form-input"
                                placeholder="e.g., BHMS Final Examination - Homoeopathy" />
                        </div>
                    @endif
                    @if ($this->visibleFields['heldIn'])
                        <div>
                            <label for="heldIn" class="form-label">
                                Held In (Month & Year of Degree)

                            </label>
                            <input type="text" id="heldIn" wire:model="heldIn" placeholder="e.g., June 2023"
                                class="form-input" />
                        </div>
                    @endif
                    <div>
                        <label for="university" class="form-label">
                            Name of University
                        </label>
                        <input type="text" id="university" wire:model="university" class="form-input"
                            placeholder="e.g., West Bengal University of Health Sciences" />
                        @error('university')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="college" class="form-label">
                            Name of College
                        </label>
                        <input type="text" id="college" wire:model="college" class="form-input"
                            placeholder="Enter your college name" />
                        @error('college')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    @if (in_array('provisional-reg', $selectedReasons))
                        <div>
                            <label for="collegeDistrict" class="form-label">Name District (College) </label>
                            <select id="collegeDistrict" wire:model="collegeDistrict" class="form-input">
                                <option value="">Select District</option>
                                @foreach ($collegeDistricts as $districtOption)
                                    <option value="{{ $districtOption }}">{{ $districtOption }}</option>
                                @endforeach
                            </select>
                            @error('collegeDistrict')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                    @if ($this->visibleFields['finalRollNo'])
                        <div>
                            <label for="finalRollNo" class="form-label">
                                Final Roll No.

                            </label>
                            <input type="text" id="finalRollNo" wire:model="finalRollNo" class="form-input"
                                placeholder="Enter your final roll number and term" />
                        </div>
                    @endif
                    @if ($this->visibleFields['term'])
                        <div>
                            <label for="term" class="form-label">
                                Term

                            </label>
                            <input type="text" id="term" wire:model="term" class="form-input"
                                placeholder="Enter term (e.g., 1st Year, 2nd Year)" />
                        </div>
                    @endif
                    @if ($this->visibleFields['universityRegNo'])
                        <div>
                            <label for="universityRegNo" class="form-label">
                                University Registration No.

                            </label>
                            <input type="text" id="universityRegNo" wire:model="universityRegNo"
                                class="form-input" placeholder="Enter your university registration number" />
                            @error('universityRegNo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                </div>
            </div>

            <!-- Step 5: Document Uploads -->
            {{-- <div class="form-section">
                <div class="flex items-center mb-4">
                    <div
                        class="bg-blue-600 text-white rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center text-base sm:text-lg font-bold mr-4">
                        5
                    </div>
                    <h2 class="section-header flex-1 mb-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 text-blue-600 inline" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                        Document Uploads
                    </h2>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                    <!-- Document upload fields will be shown based on selected reasons -->
                    <div class="lg:col-span-2">
                        <p class="text-sm text-gray-600 mb-4">
                            📁 Please upload the required documents based on your selected application reason(s).
                            Accepted formats: PDF, JPG, PNG. Maximum file size varies per document type.
                        </p>
                    </div>

                    @if (count($this->requiredUploads) > 0)
                        @foreach ($this->requiredUploads as $uploadId)
                            @php
                                $uploadDef = $this->uploadDefinitions[$uploadId];
                            @endphp
                            <div
                                class="bg-white border-2 border-gray-200 rounded-lg p-6 hover:border-blue-500 transition-all duration-200">
                                <label for="{{ $uploadId }}" class="form-label block mb-2">
                                    {{ $uploadDef['label'] }}
                                </label>
                                <input type="file" id="{{ $uploadId }}"
                                    wire:model="uploadedFiles.{{ $uploadId }}"
                                    accept="{{ $uploadDef['accept'] }}"
                                    class="form-input file:mr-2 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:cursor-pointer"
                                    required />

                                <div wire:loading wire:target="uploadedFiles.{{ $uploadId }}"
                                    class="mt-2 text-sm text-blue-600">
                                    📤 Uploading...
                                </div>

                                @if (isset($uploadedFiles[$uploadId]) && $uploadedFiles[$uploadId])
                                    <div class="mt-2 text-sm text-green-600">
                                        ✅ File uploaded: {{ $uploadedFiles[$uploadId]->getClientOriginalName() }}
                                    </div>
                                @endif

                                <p class="mt-2 text-xs text-gray-600">
                                    📁 Accepted: {{ $uploadDef['fileType'] }} ({{ $uploadDef['maxSize'] }})
                                </p>
                                @error('uploadedFiles.' . $uploadId)
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    @else
                        <div class="lg:col-span-2 text-center py-8">
                            <div class="text-gray-400 mb-4">
                                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-lg font-medium">Select Application Reason(s)</p>
                            <p class="text-gray-400 text-sm mt-2">Document upload fields will appear based on your
                                selection</p>
                        </div>
                    @endif
                </div>
            </div> --}}

            <!-- Step 5: Document Uploads -->
            <div class="form-section">
                <div class="flex items-center mb-4">
                    <div
                        class="bg-blue-600 text-white rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center text-base sm:text-lg font-bold mr-4">
                        5
                    </div>
                    <h2 class="section-header flex-1 mb-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 text-blue-600 inline" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                        Document Uploads
                    </h2>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                    <div class="lg:col-span-2">
                        <p class="text-sm text-gray-600 mb-4">
                            Please upload the required documents from original & in colour format based on your selected
                            application reasons. Accepted formats: PDF, JPG, PNG. Maximum file size varies per document
                            type.
                        </p>
                    </div>

                    @if (count($this->requiredUploads) > 0)
                        @foreach ($this->requiredUploads as $uploadId)
                            @php
                                $uploadDef = $this->uploadDefinitions[$uploadId];
                            @endphp

                            <div
                                class="bg-white border-2 border-gray-200 rounded-lg p-6 hover:border-blue-500 transition-all duration-200">
                                <label for="{{ $uploadId }}" class="form-label block mb-2">
                                    {{ $uploadDef['label'] }}
                                </label>

                                <input type="file" id="{{ $uploadId }}"
                                    wire:model="uploadedFiles.{{ $uploadId }}"
                                    accept="{{ $uploadDef['accept'] }}"
                                    class="form-input file:mr-2 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:cursor-pointer"
                                    required />

                                <div wire:loading wire:target="uploadedFiles.{{ $uploadId }}"
                                    class="mt-2 text-sm text-blue-600">
                                    Uploading...
                                </div>

                                @if (isset($uploadedFiles[$uploadId]) && $uploadedFiles[$uploadId])
                                    <div class="mt-2 text-sm text-green-600">
                                        ✓ File uploaded: {{ $uploadedFiles[$uploadId]->getClientOriginalName() }}
                                    </div>
                                @endif

                                <p class="mt-2 text-xs text-gray-600">
                                    Accepted: {{ $uploadDef['fileType'] }} | {{ $uploadDef['maxSize'] }}
                                </p>

                                @error('uploadedFiles.' . $uploadId)
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    @else
                        <div class="lg:col-span-2 text-center py-8">
                            <div class="text-gray-400 mb-4">
                                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-lg font-medium">Select Application Reasons</p>
                            <p class="text-gray-400 text-sm mt-2">Document upload fields will appear based on your
                                selection</p>
                        </div>
                    @endif
                </div>
            </div>


            <!-- Final Submission -->
            <div class="form-section bg-green-50 border-green-200">
                <div class="text-center space-y-4 sm:space-y-6">
                    <h3 class="text-lg sm:text-xl font-bold text-green-800">
                        ✅ Ready to Submit?
                    </h3>
                    <p class="text-sm sm:text-base text-green-700 leading-relaxed px-2">
                        Please review all information carefully before submitting. Once
                        submitted, you will receive a confirmation email with your
                        application reference number.
                    </p>
                    <button type="submit"
                        class="btn bg-green-600 hover:bg-green-700 text-white shadow-lg transform hover:scale-105 w-full sm:w-auto px-6 sm:px-8 py-3"
                        wire:loading.attr="disabled" wire:target="submit">
                        <div wire:loading.remove wire:target="submit" class="flex items-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 sm:mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Submit My Application
                        </div>
                        <div wire:loading wire:target="submit" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Processing...
                        </div>
                    </button>
                    <p class="text-xs sm:text-sm text-green-600">
                        🔒 Your information is secure and will be processed
                        confidentially
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>
