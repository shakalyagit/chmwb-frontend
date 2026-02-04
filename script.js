document.addEventListener("DOMContentLoaded", () => {
    // --- DATA ---
    const applicationReasons = [{
            id: "change-surname",
            label: "Change of Surname sdjgfdjkfhgkjdfh gkjdfhgjdfjg"
        },
        {
            id: "change-address",
            label: "Change of Address"
        },
        {
            id: "duplicate-cert",
            label: "Request for Duplicate Certificate"
        },
        {
            id: "retention-reg",
            label: "Retention of Registration"
        },
        {
            id: "additional-qual",
            label: "Entry of Additional Qualification"
        },
        {
            id: "cancel-reg",
            label: "Cancellation of Registration"
        },
        {
            id: "doctor-id",
            label: "Request for Doctor's Identity Card"
        },
        {
            id: "provisional-reg",
            label: "Application for Provisional Registration",
        },
    ];

    const allUploads = {
        photo: {
            label: "Recent colour photo (JPEG, up to 200kb)",
        },
        dobProof: {
            label: "Date of Birth Proof - Class X Admit Card/Pan Card/Class X pass certificate",
        },
        regCert: {
            label: "Registration Certificate (PDF, up to 500kb) -- for Doctors only",
        },
        aadhaar: {
            label: "Aadhaar Card (PDF, up to 500kb)",
        },
        marksheet: {
            label: "Final DMS, DHMS, BHMS (Graded) & BHMS Mark-sheet (PDF, up to 500kb) -- for Doctors only",
        },
        internshipCert: {
            label: "Internship Completion Certificate for DHMS, BHMS (Graded) & BHMS. (PDF, up to 500kb) -- for Doctors only",
        },
        additionalQualCert: {
            label: "For entry of Additional Qualification : M.D. (Part-I, Part-II & Pass Certificate) / BHMS (Graded) (Part-I, Part-II & Pass Certificate) / Dip NIH (Part-I, Part-II & Pass Certificate). (PDF, up to 500kb) -- for Doctors only",
        },
        marriageCert: {
            label: "Marriage Registration Certificate (for surname change) / Affidavit of 1st Class Judicial Magistrate (for name & surname correction) / Police Missing Diary (for Duplicate). (PDF, up to 500kb)",
        },
        signature: {
            label: "Full signature of the Doctor/Student. (PDF, up to 500kb)",
        },
        provisionalDocs: {
            label: "University Registration Certificate BHMS Marksheet (1st BHMS to 4th BHMS), Declaration of Head of the instruction -- For provisional registration",
        },
        studentRegCert: {
            label: "Student Registration Certificate of Health University (PDF, up to 500kb)",
        },
        classXIIMarksheet: {
            label: "Class XII Marksheet (PDF, up to 500kb)",
        },
        paymentProof: {
            label: "QR Code / UPI Payment transaction document. (PDF, up to 500kb)",
        },
    };

    // --- ELEMENTS ---
    const reasonContainer = document.getElementById(
        "reason-for-application"
    );
    const uploadsContainer = document.getElementById(
        "document-uploads-container"
    );
    const uploadsSection = document.getElementById(
        "document-uploads-section"
    );
    const doctorOnlyFields = document.querySelectorAll(".doctor-only");
    const provisionalOnlyFields =
        document.querySelectorAll(".provisional-only");
    const form = document.getElementById("applicationForm");
    const bloodGroupContainer = document.getElementById(
        "blood-group-container"
    );

    // --- FUNCTIONS ---

    // 1. Function to disable/enable Group 2 checkboxes
    function toggleGroup2Checkboxes(disable) {
        const group2 = ["cancel-reg", "doctor-id", "provisional-reg"];

        group2.forEach(id => {
            const checkbox = document.getElementById(id);
            const checkboxItem = checkbox ? checkbox.closest(".checkbox-item") : null;

            if (checkbox && checkboxItem) {
                checkbox.disabled = disable;

                if (disable) {
                    // If disabling, uncheck the checkbox first
                    if (checkbox.checked) {
                        checkbox.checked = false;
                        checkboxItem.classList.remove("checked");
                    }
                    // Add disabled styling
                    checkboxItem.classList.add("disabled");
                } else {
                    // Remove disabled styling
                    checkboxItem.classList.remove("disabled");
                }
            }
        });
    }

    // Function to disable/enable Group 1 checkboxes
    function toggleGroup1Checkboxes(disable) {
        const group1 = ["change-surname", "change-address", "duplicate-cert", "retention-reg", "additional-qual"];

        group1.forEach(id => {
            const checkbox = document.getElementById(id);
            const checkboxItem = checkbox ? checkbox.closest(".checkbox-item") : null;

            if (checkbox && checkboxItem) {
                checkbox.disabled = disable;

                if (disable) {
                    // If disabling, uncheck the checkbox first
                    if (checkbox.checked) {
                        checkbox.checked = false;
                        checkboxItem.classList.remove("checked");
                    }
                    // Add disabled styling
                    checkboxItem.classList.add("disabled");
                } else {
                    // Remove disabled styling
                    checkboxItem.classList.remove("disabled");
                }
            }
        });
    }

    // Function to disable other Group 2 checkboxes when one is selected
    function toggleOtherGroup2Checkboxes(selectedId, disable) {
        const group2 = ["cancel-reg", "doctor-id", "provisional-reg"];

        group2.forEach(id => {
            if (id !== selectedId) {
                const checkbox = document.getElementById(id);
                const checkboxItem = checkbox ? checkbox.closest(".checkbox-item") : null;

                if (checkbox && checkboxItem) {
                    checkbox.disabled = disable;

                    if (disable) {
                        // If disabling, uncheck the checkbox first
                        if (checkbox.checked) {
                            checkbox.checked = false;
                            checkboxItem.classList.remove("checked");
                        }
                        // Add disabled styling
                        checkboxItem.classList.add("disabled");
                    } else {
                        // Remove disabled styling
                        checkboxItem.classList.remove("disabled");
                    }
                }
            }
        });
    }

    // 2. Populate checkboxes for application reasons
    function populateReasons() {
        reasonContainer.innerHTML = applicationReasons
            .map(
                (reason) => `
          <label for="${reason.id}" class="checkbox-item cursor-pointer">
            <input type="checkbox" id="${reason.id}" name="reasons" value="${reason.id}" class="w-5 h-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"/>
            <div class="flex-1">
              <span class="font-medium text-gray-900">${reason.label}</span>
            </div>
          </label>
        `
            )
            .join("");
    }

    // 3. Create a single upload field with correct styling
    function createUploadField(id, details) {
        // Extract file size from the label (e.g., "up to 200kb" or "up to 500kb")
        let fileSizeText = "Max 5MB"; // default
        if (details.label.includes("up to 200kb")) {
            fileSizeText = "Max 200KB";
        } else if (details.label.includes("up to 500kb")) {
            fileSizeText = "Max 500KB";
        }

        // Extract file type from the label and set appropriate accept attribute and display text
        let acceptTypes = ".pdf,.jpg,.jpeg,.png"; // default
        let fileTypeText = "PDF, JPG, PNG"; // default

        if (details.label.includes("(JPEG")) {
            acceptTypes = ".jpg,.jpeg";
            fileTypeText = "JPEG";
        } else if (details.label.includes("(PDF")) {
            acceptTypes = ".pdf";
            fileTypeText = "PDF";
        }

        return `
          <div class="bg-white border-2 border-gray-200 rounded-lg p-4 hover:border-blue-500 transition-all duration-200">
            <label for="${id}" class="form-label block mb-2">${details.label} <span class="required-indicator">*</span></label>
            <input type="file" id="${id}" name="${id}" accept="${acceptTypes}" class="form-input file:mr-2 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:cursor-pointer"/>
            <p class="mt-2 text-xs text-gray-600">📁 Accepted: ${fileTypeText} (${fileSizeText})</p>
          </div>
      `;
    }

    // 4. Show all document uploads
    function showAllUploads() {
        // Show all upload fields
        uploadsContainer.innerHTML = Object.keys(allUploads)
            .map((uploadId) =>
                createUploadField(uploadId, allUploads[uploadId])
            )
            .join("");
    }

    // Function to update field visibility based on selected checkboxes
    function updateFieldVisibility() {
        const checkedBoxes = document.querySelectorAll('input[type="checkbox"]:checked');
        const selectedIds = Array.from(checkedBoxes).map(cb => cb.id);

        // Check if provisional registration is selected
        const provisionalSelected = selectedIds.includes('provisional-reg');

        // Check if any Group 1 checkbox is selected
        const group1Selected = selectedIds.some(id => ['change-surname', 'change-address', 'duplicate-cert', 'retention-reg', 'additional-qual'].includes(id));

        // Fields to hide for provisional registration
        const provisionalFieldsToHide = [
            'regNumber',
            'regDate',
            'qualification',
            'heldIn',
            'examination'
        ];

        // Fields to hide for other Group 2 selections (doctor-id, cancel-reg)
        const otherGroup2FieldsToHide = [
            'finalRollNo',
            'universityRegNo',
            'term',
            'examination'
        ];

        // Fields to hide for Group 1 selections
        const group1FieldsToHide = [
            'finalRollNo',
            'universityRegNo',
            'term'
        ];

        // Check if cancellation of registration is selected
        const cancelRegSelected = selectedIds.includes('cancel-reg');

        // Check if doctor-id is selected
        const doctorIdSelected = selectedIds.includes('doctor-id');

        if (provisionalSelected) {
            // Hide fields for provisional registration
            provisionalFieldsToHide.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                const fieldContainer = field ? field.closest('div') : null;
                if (fieldContainer) {
                    fieldContainer.style.display = 'none';
                }
            });

            // Show fields specific to provisional registration (except examination)
            otherGroup2FieldsToHide.forEach(fieldId => {
                if (fieldId !== 'examination') { // Don't show examination for provisional registration
                    const field = document.getElementById(fieldId);
                    const fieldContainer = field ? field.closest('div') : null;
                    if (fieldContainer) {
                        fieldContainer.style.display = '';
                    }
                }
            });

            // Show specific upload fields for provisional registration
            showOnlySpecificUploads(['photo', 'dobProof', 'aadhaar', 'classXIIMarksheet', 'studentRegCert', 'paymentProof']);

        } else if (cancelRegSelected) {
            // Hide fields for cancellation of registration
            otherGroup2FieldsToHide.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                const fieldContainer = field ? field.closest('div') : null;
                if (fieldContainer) {
                    fieldContainer.style.display = 'none';
                }
            });

            // Show fields that provisional registration hides (except examination for cancellation)
            provisionalFieldsToHide.forEach(fieldId => {
                if (fieldId !== 'examination') { // Don't show examination for cancellation
                    const field = document.getElementById(fieldId);
                    const fieldContainer = field ? field.closest('div') : null;
                    if (fieldContainer) {
                        fieldContainer.style.display = '';
                    }
                }
            });

            // Ensure DOB field is visible for cancellation of registration
            const dobField = document.getElementById('dob');
            const dobContainer = dobField ? dobField.closest('div') : null;
            if (dobContainer) {
                dobContainer.style.display = '';
            }

            // Show upload fields for cancellation of registration
            showOnlySpecificUploads(['photo', 'dobProof', 'aadhaar', 'regCert', 'marksheet', 'internshipCert', 'signature', 'paymentProof']);

        } else if (doctorIdSelected) {
            // Hide fields for doctor's identity card
            otherGroup2FieldsToHide.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                const fieldContainer = field ? field.closest('div') : null;
                if (fieldContainer) {
                    fieldContainer.style.display = 'none';
                }
            });

            // Show fields that provisional registration hides (except examination for doctor-id)
            provisionalFieldsToHide.forEach(fieldId => {
                if (fieldId !== 'examination') { // Don't show examination for doctor-id
                    const field = document.getElementById(fieldId);
                    const fieldContainer = field ? field.closest('div') : null;
                    if (fieldContainer) {
                        fieldContainer.style.display = '';
                    }
                }
            });

            // Hide DOB field for doctor's identity card
            const dobField = document.getElementById('dob');
            const dobContainer = dobField ? dobField.closest('div') : null;
            if (dobContainer) {
                dobContainer.style.display = 'none';
            }

            // Show upload fields for doctor's identity card (excluding dobProof since DOB is hidden)
            showOnlySpecificUploads(['photo', 'aadhaar', 'regCert', 'signature', 'paymentProof']);

        } else if (group1Selected) {
            // Hide specific fields for Group 1 selections
            group1FieldsToHide.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                const fieldContainer = field ? field.closest('div') : null;
                if (fieldContainer) {
                    fieldContainer.style.display = 'none';
                }
            });

            // Show fields that other groups hide (but not term for Group 1)
            provisionalFieldsToHide.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                const fieldContainer = field ? field.closest('div') : null;
                if (fieldContainer) {
                    fieldContainer.style.display = '';
                }
            });

            // Show upload fields for Group 1 (excluding provisional registration uploads)
            showOnlySpecificUploads(['photo', 'dobProof', 'aadhaar', 'regCert', 'marksheet', 'internshipCert', 'additionalQualCert', 'marriageCert', 'signature', 'paymentProof']);

        } else {
            // Show all fields when no checkboxes are selected
            [...provisionalFieldsToHide, ...otherGroup2FieldsToHide, ...group1FieldsToHide].forEach(fieldId => {
                const field = document.getElementById(fieldId);
                const fieldContainer = field ? field.closest('div') : null;
                if (fieldContainer) {
                    fieldContainer.style.display = '';
                }
            });

            // Show all upload fields
            showAllUploads();
        }
    }

    // Function to show only specific upload fields
    function showOnlySpecificUploads(allowedUploads) {
        uploadsContainer.innerHTML = allowedUploads
            .map((uploadId) => createUploadField(uploadId, allUploads[uploadId]))
            .join("");
    }

    // --- INITIALIZATION ---
    populateReasons();
    showAllUploads(); // Show all uploads on load
    lucide.createIcons();

    // Add event listener with better accessibility and group logic
    reasonContainer.addEventListener("change", (e) => {
        if (e.target.type === "checkbox") {
            const checkboxItem = e.target.closest(".checkbox-item");
            const checkboxId = e.target.id;


            // Define the two groups
            const group1 = ["change-surname", "change-address", "duplicate-cert", "retention-reg", "additional-qual"];
            const group2 = ["cancel-reg", "doctor-id", "provisional-reg"];

            if (e.target.checked) {
                // If this checkbox is being checked
                if (group1.includes(checkboxId)) {
                    // Group 1 selected - uncheck all Group 2 checkboxes and disable them
                    group2.forEach(id => {
                        const otherCheckbox = document.getElementById(id);
                        if (otherCheckbox && otherCheckbox.checked) {
                            otherCheckbox.checked = false;
                            otherCheckbox.closest(".checkbox-item").classList.remove("checked");
                        }
                    });
                    // Disable Group 2 checkboxes
                    toggleGroup2Checkboxes(true);
                } else if (group2.includes(checkboxId)) {
                    // Group 2 selected - uncheck all Group 1 checkboxes and disable them
                    group1.forEach(id => {
                        const otherCheckbox = document.getElementById(id);
                        if (otherCheckbox && otherCheckbox.checked) {
                            otherCheckbox.checked = false;
                            otherCheckbox.closest(".checkbox-item").classList.remove("checked");
                        }
                    });
                    // Disable Group 1 checkboxes
                    toggleGroup1Checkboxes(true);
                    // Disable other Group 2 checkboxes (only one allowed in Group 2)
                    toggleOtherGroup2Checkboxes(checkboxId, true);
                }
            } else {
                // Checkbox is being unchecked
                if (group2.includes(checkboxId)) {
                    // If a Group 2 checkbox is unchecked, re-enable other Group 2 checkboxes
                    toggleOtherGroup2Checkboxes(checkboxId, false);
                }
            }

            // Update visual state for current checkbox
            checkboxItem.classList.toggle("checked", e.target.checked);

            // Check if any Group 1 checkboxes are still selected
            const anyGroup1Selected = group1.some(id => {
                const checkbox = document.getElementById(id);
                return checkbox && checkbox.checked;
            });

            // If no Group 1 checkboxes are selected, re-enable Group 2
            if (!anyGroup1Selected) {
                toggleGroup2Checkboxes(false);
            }

            // Check if any Group 2 checkboxes are still selected
            const anyGroup2Selected = group2.some(id => {
                const checkbox = document.getElementById(id);
                return checkbox && checkbox.checked;
            });

            // If no Group 2 checkboxes are selected, re-enable Group 1
            if (!anyGroup2Selected) {
                toggleGroup1Checkboxes(false);
            }

            // Handle conditional field visibility
            updateFieldVisibility();
        }
    });

    // Add keyboard navigation support
    reasonContainer.addEventListener("keydown", (e) => {
        if (e.key === "Enter" || e.key === " ") {
            const checkbox = e.target.querySelector('input[type="checkbox"]');
            if (checkbox) {
                e.preventDefault();
                checkbox.checked = !checkbox.checked;
                checkbox.dispatchEvent(new Event("change", {
                    bubbles: true
                }));
            }
        }
    });

    // Handle form submission with better feedback
    form.addEventListener("submit", (e) => {
        e.preventDefault();

        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML =
            '<i data-lucide="loader-2" class="w-6 h-6 mr-3 animate-spin"></i>Processing...';
        submitBtn.disabled = true;

        // Simulate processing time
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;

            // Show success message
            const successDiv = document.createElement("div");
            successDiv.className =
                "fixed top-4 right-4 bg-green-600 text-white p-6 rounded-xl shadow-lg z-50 max-w-md";
            successDiv.innerHTML = `
        <div class="flex items-center">
          <div class="text-2xl mr-3">✅</div>
          <div>
            <h4 class="font-bold text-lg">Application Submitted Successfully!</h4>
            <p class="text-sm mt-1">Reference ID: CHM${Date.now()}</p>
            <p class="text-sm mt-1">You will receive a confirmation email shortly.</p>
          </div>
        </div>
      `;
            document.body.appendChild(successDiv);

            // Remove success message after 5 seconds
            setTimeout(() => {
                successDiv.remove();
            }, 5000);

            console.log("Form submitted successfully!");
        }, 2000);
    });

    // Add form validation feedback
    form.addEventListener("input", (e) => {
        if (e.target.hasAttribute("required") && e.target.value.trim()) {
            e.target.style.borderColor = "#10b981"; // green
            e.target.style.backgroundColor = "#f0fdf4"; // light green
        } else if (e.target.hasAttribute("required")) {
            e.target.style.borderColor = "";
            e.target.style.backgroundColor = "";
        }
    });

    // Add helpful tooltips and focus enhancements for older users
    document
        .querySelectorAll("input, textarea, select")
        .forEach((field) => {
            field.addEventListener("focus", () => {
                field.style.transform = "scale(1.02)";
                field.style.boxShadow = "0 0 0 4px rgba(59, 130, 246, 0.3)";
            });

            field.addEventListener("blur", () => {
                field.style.transform = "";
                field.style.boxShadow = "";
            });
        });
});
