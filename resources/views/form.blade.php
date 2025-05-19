@extends('layouts.main')

@section('content')
<div class="container my-5">
  <form id="multiStepForm" method="POST" action="{{ route('form.submit') }}">
    @csrf

    <!-- Page 1: Personal & Educational Information -->
    <div id="step-1" class="form-step">
      <h2 class="mb-4">Personal &amp; Educational Information</h2>

      <div class="form-group mb-3">
        <label for="first_name">First Name <span class="text-danger">*</span></label>
        <input type="text" id="first_name" name="first_name" required class="form-control">
      </div>

      <div class="form-group mb-3">
        <label for="middle_name">Middle Name (Optional)</label>
        <input type="text" id="middle_name" name="middle_name" class="form-control">
      </div>

      <div class="form-group mb-3">
        <label for="last_name">Last Name <span class="text-danger">*</span></label>
        <input type="text" id="last_name" name="last_name" required class="form-control">
      </div>

      <div class="form-group mb-3">
        <label for="suffix">Suffix</label>
        <input type="text" id="suffix" name="suffix" class="form-control" placeholder="Jr., III, etc.">
      </div>

      <div class="form-group mb-3">
        <label for="current_address">Current Address <span class="text-danger">*</span></label>
        <input type="text" id="current_address" name="current_address" required class="form-control">
      </div>

      <!-- College Selection as Checkboxes -->
      <div class="form-group mb-3">
        <label><b>Degree Program/s Obtained in CLSU</b><br>
        Select the College/s attended: <span class="text-danger">*</span></label>
        <div id="colleges_container">
          @foreach($colleges as $college)
            <div class="form-check form-check-inline">
              <input 
                class="form-check-input college-checkbox" 
                type="checkbox" 
                name="colleges[]" 
                id="college_{{ $college->id }}" 
                value="{{ $college->id }}">
              <label class="form-check-label" for="college_{{ $college->id }}">
                {{ $college->college }}
              </label>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Courses (populated dynamically) -->
      <div class="form-group mb-3" id="courses_group" style="display:none;">
        <label>Degree Programs (Select all applicable):<span class="text-danger">*</span></label>
        <div id="courses_container"><!-- courses appear here --></div>
      </div>


      <div class="form-group mb-3">
        <label for="field_of_specialization">Field of Specialization/s<span class="text-danger">*</span></label>
        <input type="text" id="field_of_specialization" name="field_of_specialization" required class="form-control">
      </div>

      <div class="form-group mb-3">
        <label for="graduation_date">Date Graduated <span class="text-danger">*</span></label>
        <input type="date" id="graduation_date" name="graduation_date" required class="form-control">
      </div>

      <div class="form-group mb-3">
        <label>Did you pursue graduate studies within 12 months after graduation? <span class="text-danger">*</span></label>
        <div>
          <label class="mr-3"><input type="radio" name="graduate_study_status" value="Yes" required> Yes</label>
          <label><input type="radio" name="graduate_study_status" value="No" required> No</label>
        </div>
      </div>

      <div class="form-group mb-3">
        <label for="present_employment_status">What is your present employment status? <span class="text-danger">*</span></label>
        <select id="present_employment_status" name="present_employment_status" required class="form-control">
          <option value="">Select...</option>
          <option value="Employed">Employed</option>
          <option value="Unemployed">Unemployed</option>
          <option value="Self-employed">Self-employed</option>
        </select>
      </div>

      <div class="d-flex justify-content-end">
        <button type="button" id="nextBtn" class="btn btn-primary">Next</button>
      </div>
    </div>

    <!-- Page 2: Employment Information -->
    <div id="step-2" class="form-step" style="display:none;">
      <h2 class="mb-4">Employment Information</h2>

      <!-- Conditional Question: If Unemployed or Self-employed -->
      <div class="form-group mb-3" id="job_experience_group" style="display:none;">
        <label>Do you have any previous work experience? <span class="text-danger">*</span></label>
        <div>
          <label class="mr-3"><input type="radio" name="job_experience_status" value="Yes"> Yes</label>
          <label><input type="radio" name="job_experience_status" value="No"> No</label>
        </div>
      </div>

      <div class="form-group mb-3">
        <label for="employment_date">Date of First Employment <span class="text-danger">*</span></label>
        <input type="date" id="employment_date" name="employment_date" required class="form-control">
      </div>

      <div class="form-group mb-3">
        <label for="first_workplace">Where is your first workplace? <span class="text-danger">*</span></label>
        <select id="first_workplace" name="first_workplace" required class="form-control">
          <option value="">Select...</option>
          <option value="Local">Local</option>
          <option value="Foreign Country">Foreign Country</option>
        </select>
      </div>

      <div class="form-group mb-3">
        <label for="position">Position in the Company/Institution <span class="text-danger">*</span></label>
        <input type="text" id="position" name="position" required class="form-control">
      </div>

      <div class="form-group mb-3">
        <label for="first_employer_name">Name of your first employer/company <span class="text-danger">*</span></label>
        <input type="text" id="first_employer_name" name="first_employer_name" required class="form-control">
      </div>

      <div class="form-group mb-3">
        <label for="office_address">Office Address <span class="text-danger">*</span></label>
        <input type="text" id="office_address" name="office_address" required class="form-control">
      </div>

      <div class="form-group mb-3">
        <label for="employer_contact">Employer's Contact Information (number or email) <span class="text-danger">*</span></label>
        <input type="text" id="employer_contact" name="employer_contact" required class="form-control">
      </div>

      <div class="form-group mb-3">
        <label for="time_to_first_job">How long did it take you to get your first job? <span class="text-danger">*</span></label>
        <select id="time_to_first_job" name="time_to_first_job" required class="form-control">
          <option value="">Select...</option>
          <option value="Within a year">Within a year</option>
          <option value="Within 2 years">Within 2 years</option>
          <option value="More than 2 years">More than 2 years</option>
        </select>
      </div>

      <div class="form-group mb-4">
        <label>Is your first job related to your degree program? <span class="text-danger">*</span></label>
        <div>
          <label class="mr-3"><input type="radio" name="job_related_to_degree" value="Yes" required> Yes</label>
          <label><input type="radio" name="job_related_to_degree" value="No" required> No</label>
        </div>
      </div>

      <div class="d-flex justify-content-between">
        <button type="button" id="prevBtn" class="btn btn-secondary">Previous</button>
        <button type="button" id="nextBtn2" class="btn btn-primary">Next</button>
      </div>
    </div>

    <!-- Page 3: Optional Questions -->
    <div id="step-3" class="form-step" style="display:none;">
      <h2 class="mb-4">Optional Questions</h2>
      <p class="mb-3">These questions are optional, but there are some features locked behind them. Please answer them if you wish to unlock those features!</p>

      <div class="form-group mb-4">
        <label>Group A:</label>
        <p>
          Statement 1. The timeliness/relevance of instructional delivery and supervision is good at CLSU<br>
          Statement 2. The training and academic preparation I have obtained from CLSU prepared me for my employment
        </p>
        <select name="optional_group_a" class="form-control">
          <option value="">Select an option (optional)</option>
          <option value="Agree with Both">I agree with both statements</option>
          <option value="Agree with statement 1 only">I agree with statement 1 only</option>
          <option value="Agree with statement 2 only">I agree with statement 2 only</option>
          <option value="Disagree with Both">I disagree with both statements</option>
        </select>
      </div>

      <div class="form-group mb-4">
        <label>Group B:</label>
        <p>
          Statement 3. The career service support in CLSU is sufficient to enable me to find my first job<br>
          Statement 4. The learning technologies and facilities of the University helped me become a competitive graduate
        </p>
        <select name="optional_group_b" class="form-control">
          <option value="">Select an option (optional)</option>
          <option value="Agree with Both">I agree with both statements</option>
          <option value="Agree with statement 3 only">I agree with statement 3 only</option>
          <option value="Agree with statement 4 only">I agree with statement 4 only</option>
          <option value="Disagree with Both">I disagree with both statements</option>
        </select>
      </div>

      <div class="d-flex justify-content-between">
        <button type="button" id="prevBtn2" class="btn btn-secondary">Previous</button>
        <button type="submit" class="btn btn-success">Submit</button>
      </div>
    </div>

  </form>

  <div id="progress-indicator" class="mt-4 text-center">Step 1 of 3</div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function(){
  var currentStep = 1;
  var totalSteps = 3;
  var formChanged = false;

  // Flag unsaved changes
  $("#multiStepForm").on("input change", function(){
    formChanged = true;
  });

  function updateProgress(){
    $("#progress-indicator").text("Step " + currentStep + " of " + totalSteps);
  }
  updateProgress();

  // Validate required fields for a step.
  // Note: Page 3 is optional, so skip validation if on step 3.
  function validateStep(step){
    if(step == 3) { return true; }
    var valid = true;
    
    // For step 2, special handling if unemployed with no experience
    if(step == 2) {
      var employmentStatus = $("#present_employment_status").val();
      var jobExperience = $("input[name='job_experience_status']:checked").val();
      
      // If unemployed and no experience, skip validation for job-related fields
      if(employmentStatus === "Unemployed" && jobExperience === "No") {
        return true;
      }
    }
    
    // Validate visible required fields only
    $("#step-" + step + " [required]:visible").each(function(){
      if(!$(this).val()){
        $(this).css("border", "1px solid red");
        valid = false;
      } else {
        $(this).css("border", "");
      }
    });
    
    // Custom check: If courses group is visible, ensure at least one course checkbox is checked.
    if(step == 1 && $("#colleges_container").is(":visible")){
      if($("#colleges_container input[type='checkbox']:checked").length === 0){
        valid = false;
        Swal.fire({
          icon: "error",
          title: "Incomplete",
          text: "Please select at least one college."
        });
        return false;
      }
      
      if($("#courses_container input[type='checkbox']:checked").length === 0){
        valid = false;
        Swal.fire({
          icon: "error",
          title: "Incomplete",
          text: "Please select at least one course."
        });
        return false;
      }
      
      // Check specialization fields for checked courses
      var specializationValid = true;
      $("#courses_container input[type='checkbox']:checked").each(function(){
        var courseId = $(this).attr("id").replace("course_", "");
        var specField = $("#specialization_" + courseId);
        
        if(specField.is(":visible") && !specField.val()){
          specField.css("border", "1px solid red");
          specializationValid = false;
        } else {
          specField.css("border", "");
        }
      });
      
      if(!specializationValid){
        valid = false;
        Swal.fire({
          icon: "error",
          title: "Incomplete",
          text: "Please fill in specialization for all selected courses."
        });
        return false;
      }
    }
    
    if(!valid){
      Swal.fire({
        icon: "error",
        title: "Incomplete",
        text: "Please fill in all required fields before proceeding."
      });
    }
    return valid;
  }

  // Next and Previous button events
  $("#nextBtn").click(function(){
    if(validateStep(currentStep)){
      $("#step-" + currentStep).hide();
      currentStep++;
      $("#step-" + currentStep).show();
      updateProgress();
    }
  });

  $("#nextBtn2").click(function(){
    if(validateStep(currentStep)){
      $("#step-" + currentStep).hide();
      currentStep++;
      $("#step-" + currentStep).show();
      updateProgress();
    }
  });

  $("#prevBtn").click(function(){
    $("#step-" + currentStep).hide();
    currentStep--;
    $("#step-" + currentStep).show();
    updateProgress();
  });

  $("#prevBtn2").click(function(){
    $("#step-" + currentStep).hide();
    currentStep--;
    $("#step-" + currentStep).show();
    updateProgress();
  });

  // Form submit confirmation with form summary
  $("#multiStepForm").submit(function(e){
    e.preventDefault();
    if(!validateStep(currentStep)) return;
    
    // Handle required fields for specializations - remove required from hidden ones
    // This is critical fix #1
    $('.specialization-field').each(function() {
      // Only set as required if the parent course is checked
      var courseId = $(this).attr('id').replace('specialization_', '');
      if (!$('#course_' + courseId).is(':checked')) {
        $(this).prop('required', false);
      }
    });
    
// Build summary HTML
var summaryHtml = '<div style="text-align:left; max-height:400px; overflow-y:auto;">';

// --- Personal Information ---
summaryHtml += '<h4>Personal Information</h4>';
summaryHtml += '<p><strong>Name:</strong> ' 
  + $("#first_name").val() + ' '
  + ($("#middle_name").val() ? $("#middle_name").val() + ' ' : '')
  + $("#last_name").val()
  + ($("#suffix").val() ? ' ' + $("#suffix").val() : '')
  + '</p>';
summaryHtml += '<p><strong>Current Address:</strong> ' + $("#current_address").val() + '</p>';

// --- Educational Information ---
summaryHtml += '<h4>Educational Information</h4>';
// Colleges
var collegesSelected = [];
$('.college-checkbox:checked').each(function() {
  collegesSelected.push($("label[for='college_" + $(this).val() + "']").text());
});
summaryHtml += '<p><strong>College(s):</strong> ' + (collegesSelected.join(", ") || 'None selected') + '</p>';

// Courses & specializations
summaryHtml += '<p><strong>Course(s) and Specialization(s):</strong></p><ul>';
$(".course-checkbox:checked").each(function() {
  var courseId = this.id.replace("course_", "");
  var courseCode = $(this).data("course-code");
  var courseName = $("label[for='" + this.id + "']").text();
  var spec = $("#specialization_" + courseId).val();
  summaryHtml += '<li><strong>' + courseName + '</strong>';
  if (spec) {
    summaryHtml += '<br><em>Specialization:</em> (' + courseCode + ') ' + spec;
  }
  summaryHtml += '</li>';
});
summaryHtml += '</ul>';

summaryHtml += '<p><strong>Date Graduated:</strong> ' + $("#graduation_date").val() + '</p>';
summaryHtml += '<p><strong>Graduate Studies:</strong> ' + $("input[name='graduate_study_status']:checked").val() + '</p>';

// --- Employment Information ---
var empStatus = $("#present_employment_status").val();
var jobExp = $("input[name='job_experience_status']:checked").val();

summaryHtml += '<h4>Employment Information</h4>';
summaryHtml += '<p><strong>Employment Status:</strong> ' + empStatus + '</p>';

// Have Work Experience (always show)
summaryHtml += '<p><strong>Have Work Experience:</strong> ' 
  + (empStatus === "Employed"
      ? 'Yes <em>(auto)</em>'
      : (jobExp || 'No'))
  + '</p>';

// Other employment details (unless explicitly unemployed with no exp)
if (!(empStatus === "Unemployed" && jobExp === "No")) {
  summaryHtml += '<p><strong>Date of First Employment:</strong> ' + ($("#employment_date").val() || 'N/A') + '</p>';
  summaryHtml += '<p><strong>First Workplace:</strong> ' + ($("#first_workplace").val() || 'N/A') + '</p>';
  summaryHtml += '<p><strong>Position:</strong> ' + ($("#position").val() || 'N/A') + '</p>';
  summaryHtml += '<p><strong>First Employer:</strong> ' + ($("#first_employer_name").val() || 'N/A') + '</p>';
  summaryHtml += '<p><strong>Office Address:</strong> ' + ($("#office_address").val() || 'N/A') + '</p>';
  summaryHtml += '<p><strong>Employer Contact:</strong> ' + ($("#employer_contact").val() || 'N/A') + '</p>';
  summaryHtml += '<p><strong>Time to First Job:</strong> ' + ($("#time_to_first_job").val() || 'N/A') + '</p>';
  summaryHtml += '<p><strong>Job Related to Degree:</strong> ' 
    + ($("input[name='job_related_to_degree']:checked").val() || 'N/A')
    + '</p>';
}

// --- Optional Questions ---
summaryHtml += '<h4>Optional Questions</h4>';

// Helper to get a control by name, and return its text (for <select>) or value
function getByNameText(name) {
  var $ctrl = $("[name='" + name + "']");
  if (!$ctrl.length) return "";
  // if it's a select, use the selected option's text
  if ($ctrl.is("select")) {
    var val = $ctrl.val();
    // if placeholder or empty, return empty
    if (!val) return "";
    return $ctrl.find("option:selected").text();
  }
  // others (e.g. <input>), just val()
  return $ctrl.val() || "";
}

// Group A
var grpAText = getByNameText("optional_group_a");
summaryHtml += '<p><strong>Group A:</strong> '
  + (grpAText ? grpAText : '<em>Not answered</em>')
  + '</p>';

// Group B
var grpBText = getByNameText("optional_group_b");
summaryHtml += '<p><strong>Group B:</strong> '
  + (grpBText ? grpBText : '<em>Not answered</em>')
  + '</p>';

summaryHtml += '</div>';

// Show the SweetAlert
Swal.fire({
  title: "Please review your submission",
  html: summaryHtml,
  icon: "info",
  showCancelButton: true,
  confirmButtonText: "Yes, submit",
  cancelButtonText: "No, cancel",
  width: '600px'
}).then(function(result){
  if (result.isConfirmed) {
    formChanged = false;

    // If unemployed with no experience, clear requirements
    if (empStatus === "Unemployed" && jobExp === "No") {
      $("#employment_date, #first_workplace, #first_employer_name, #office_address, #position, #employer_contact, #time_to_first_job").prop("required", false);
      $("input[name='job_related_to_degree']").prop("required", false);
    }

    $("#multiStepForm")[0].submit();
  }
});

  });

  // Warn on page unload
  window.onbeforeunload = function(){
    if(formChanged){
      return "You have unsaved changes, are you sure you want to leave?";
    }
  };

  // Build the college→courses map
  var coursesData = {!! json_encode($courses) !!};
  var coursesMap = {};
  $.each(coursesData, function(i, course){
    coursesMap[course.college_id] = coursesMap[course.college_id] || [];
    coursesMap[course.college_id].push(course);
  });

  // Remove the original specialization field completely
  $("#field_of_specialization").parent().remove();

  // Listen for any checkbox toggle for colleges
  $(document).on('change', '.college-checkbox', function() {
    var collegeId = $(this).val();
    var container = $("#courses_container");

    if (this.checked) {
      // Create a wrapper DIV for this college
      var wrapper = $("<div>", {
        id: "courses_college_" + collegeId,
        class: "mb-3"
      });
      // Header
      wrapper.append(
        $("<strong>").text(
          $("label[for='college_" + collegeId + "']").text() + ":"
        )
      );
      
      // Each course as a checkbox + description
      $.each(coursesMap[collegeId] || [], function(i, course){
        // Build the label text
        var labelText = "(" + course.course + ")";
        if (course.description) {
          labelText += " – " + course.description;
        }

        // Create course row
        var $row = $("<div>", { class: "form-check mb-2" });
        var $checkbox = $("<input>", {
          class: "form-check-input course-checkbox",
          type: "checkbox",
          name: "graduated_course[]",
          id: "course_" + course.id,
          value: course.course,
          "data-course-code": course.course
        });
        
        $checkbox.appendTo($row);
        
        $("<label>", {
          class: "form-check-label",
          for: "course_" + course.id,
          text: labelText
        }).appendTo($row);
        
        // Add course to wrapper
        wrapper.append($row);
        
        // Create specialization field that appears when checkbox is checked
        var $specRow = $("<div>", {
          id: "spec_" + course.id,
          class: "ml-4 mb-3",
          style: "display: none;"
        });
        
        $("<label>", {
          for: "specialization_" + course.id,
          text: "Specialization for " + course.course + ": ",
          class: "form-label"
        }).append($("<span class='text-danger'>*</span>")).appendTo($specRow);
        
        // Critical fix #2: Don't set required until the field is shown
        $("<input>", {
          type: "text",
          id: "specialization_" + course.id,
          class: "form-control specialization-field",
          name: "specialization_" + course.id,
          "data-course": course.course
        }).appendTo($specRow);
        
        wrapper.append($specRow);
        
        // Add listener to show/hide specialization field
        $checkbox.on('change', function() {
          if(this.checked) {
            $("#spec_" + course.id).show();
            // Only set required when checkbox is checked
            $("#specialization_" + course.id).prop("required", true);
          } else {
            $("#spec_" + course.id).hide();
            // Clear the value when unchecked and remove required
            $("#specialization_" + course.id).val("").prop("required", false);
          }
        });
      });
      
      container.append(wrapper);
      $("#courses_group").show();

    } else {
      // Remove that college's block
      $("#courses_college_" + collegeId).remove();
      if (container.children().length === 0) {
        $("#courses_group").hide();
      }
    }
  });

  // Show/hide job experience question based on employment status
  $("#present_employment_status").change(function () {
    var status = $(this).val();
    
    if (status === "Unemployed" || status === "Self-employed") {
      $("#job_experience_group").show();
      $("#job_experience_group input").prop("required", true).prop("checked", false);
    } else if (status === "Employed") {
      // Auto-answer "Yes" and hide the question
      $("#job_experience_group").hide();
      $("#job_experience_group input").prop("required", false);
      $("input[name='job_experience_status'][value='Yes']").prop("checked", true);
    } else {
      // Reset
      $("#job_experience_group").hide();
      $("#job_experience_group input").prop("required", false).prop("checked", false);
    }
    
    // Trigger the job experience handler to update fields based on new employment status
    updateJobFields();
  });
  
  // Handle the job experience radio button changes to manage required fields
  $(document).on('change', "input[name='job_experience_status']", function() {
    updateJobFields();
  });
  
  // Function to update job fields based on current selections
  function updateJobFields() {
    var jobExperience = $("input[name='job_experience_status']:checked").val();
    var employmentStatus = $("#present_employment_status").val();
    
    // If unemployed and no job experience, disable and clear job-related fields
    if (employmentStatus === "Unemployed" && jobExperience === "No") {
      // Disable all job-related fields
      $("#employment_date").prop("required", false).prop("disabled", true).val("");
      $("#first_workplace").prop("required", false).prop("disabled", true).val("");
      $("#first_employer_name").prop("required", false).prop("disabled", true).val("");
      $("#office_address").prop("required", false).prop("disabled", true).val("");
      $("#position").prop("required", false).prop("disabled", true).val("");
      $("#employer_contact").prop("required", false).prop("disabled", true).val("");
      $("#time_to_first_job").prop("required", false).prop("disabled", true).val("");
      $("input[name='job_related_to_degree']").prop("required", false).prop("disabled", true).prop("checked", false);
    } else {
      // Enable all job-related fields
      $("#employment_date").prop("required", true).prop("disabled", false);
      $("#first_workplace").prop("required", true).prop("disabled", false);
      $("#first_employer_name").prop("required", true).prop("disabled", false);
      $("#office_address").prop("required", true).prop("disabled", false);
      $("#position").prop("required", true).prop("disabled", false);
      $("#employer_contact").prop("required", true).prop("disabled", false);
      $("#time_to_first_job").prop("required", true).prop("disabled", false);
      $("input[name='job_related_to_degree']").prop("required", true).prop("disabled", false);
    }
  }
  
  // Before form submission, collect specialization data
  $("#multiStepForm").on("submit", function() {
    // Build a combined specialization string
    var specializations = [];
    
    // Critical fix #3: Only collect from visible or checked course specializations
    $('.specialization-field').each(function() {
      var courseId = $(this).attr('id').replace('specialization_', '');
      if ($('#course_' + courseId).is(':checked') && $(this).val()) {
        var courseCode = $(this).data('course');
        specializations.push("(" + courseCode + ")" + $(this).val());
      }
    });
    
    // Create a hidden input to store the combined specializations
    $("<input>")
      .attr("type", "hidden")
      .attr("name", "field_of_specialization")
      .val(specializations.join(", "))
      .appendTo($(this));
    
    // For disabled fields, create hidden fields with their values
    if($("#present_employment_status").val() === "Unemployed" && 
       $("input[name='job_experience_status']:checked").val() === "No") {
      
      // Re-enable disabled fields just before submission to include them in the form data
      $("#employment_date, #first_workplace, #first_employer_name, #office_address, #position, #employer_contact, #time_to_first_job")
        .prop("disabled", false);
      $("input[name='job_related_to_degree']").prop("disabled", false);
    }
  });
  
  // Initialize the form state
  $(document).ready(function() {
    // Call this on page load to set initial state
    updateJobFields();
  });
});
</script>
@endsection