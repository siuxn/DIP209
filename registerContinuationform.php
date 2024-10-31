<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <script src="script.js" defer></script>
    <title>Registraion Form</title>
    <style>
        :root {
    --primary-color: rgb(11, 78, 179);
  }
  
  *,
  *::before,
  *::after {
    box-sizing: border-box;
  }
  
  body {
    font-family: Montserrat, "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    display: grid;
    place-items: center;
    min-height: 100vh;
  }
  /* Global Stylings */
  label {
    display: block;
    margin-bottom: 0.5rem;
  }
  
  input {
    display: block;
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ccc;
    border-radius: 0.25rem;
  }
  
  .width-50 {
    width: 50%;
  }
  
  .ml-auto {
    margin-left: auto;
  }
  
  .text-center {
    text-align: center;
  }
  
  /* Progressbar */
  .progressbar {
    position: relative;
    display: flex;
    justify-content: space-between;
    counter-reset: step;
    margin: 2rem 0 4rem;
  }
  
  .progressbar::before,
  .progress {
    content: "";
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    height: 4px;
    width: 100%;
    background-color: #dcdcdc;
    z-index: -1;
  }
  
  .progress {
    background-color: var(--primary-color);
    width: 0%;
    transition: 0.3s;
  }
  
  .progress-step {
    width: 2.1875rem;
    height: 2.1875rem;
    background-color: #dcdcdc;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .progress-step::before {
    counter-increment: step;
    content: counter(step);
  }
  
  .progress-step::after {
    content: attr(data-title);
    position: absolute;
    top: calc(100% + 0.5rem);
    font-size: 0.85rem;
    color: #666;
  }
  
  .progress-step-active {
    background-color: var(--primary-color);
    color: #f3f3f3;
  }
  
  /* Form */
  .form {
    width: clamp(320px, 30%, 430px);
    margin: 0 auto;
    border: 1px solid #ccc;
    border-radius: 0.35rem;
    padding: 1.5rem;
  }
  
  .form-step {
    display: none;
    transform-origin: top;
    animation: animate 0.5s;
  }
  
  .form-step-active {
    display: block;
  }
  
  .input-group {
    margin: 2rem 0;
  }
  
  @keyframes animate {
    from {
      transform: scale(1, 0);
      opacity: 0;
    }
    to {
      transform: scale(1, 1);
      opacity: 1;
    }
  }
  
  /* Button */
  .btns-group {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
  }
  
  .btn {
    padding: 0.75rem;
    display: block;
    text-decoration: none;
    background-color: var(--primary-color);
    color: #f3f3f3;
    text-align: center;
    border-radius: 0.25rem;
    cursor: pointer;
    transition: 0.3s;
  }
  .btn:hover {
    box-shadow: 0 0 0 2px #fff, 0 0 0 3px var(--primary-color);
  }
    </style>
  </head>
  <body>
    <form action="processSignUp.php" class="form">
      <h1 class="text-center">Registration Form</h1>
      <!-- Progress bar -->
      <div class="progressbar">
        <div class="progress" id="progress"></div>
         
        <input type="hidden" name="name" value="<?php echo htmlspecialchars($_POST['name']); ?>">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($_POST['email']); ?>">
            <input type="hidden" name="password" value="<?php echo htmlspecialchars($_POST['password']); ?>">
        
        <div class="progress-step progress-step-active" data-title="Contact" ></div>
        <div class="progress-step" data-title="Intro"></div>
        <div class="progress-step" data-title="Profile Picture"></div>
      </div>

      <!-- Steps -->
      <div class="form-step form-step-active">
        <div class="input-group">
          <label for="phoneno">Phone Number</label>
          <input type="text" name="phoneno" id="phoneno" />
        </div>
     
        <div class="">
          <a href="#" class="btn btn-next width-50 ml-auto">Next</a>
        </div>
      </div>
      <div class="form-step">
        <div class="input-group">
            <label for="dob">Date of Birth</label>
            <input type="date" name="dob" id="dob" />
          </div>
          <div class="input-group">
            <label for="gender">Gender</label>
            <input type="number" name="gender" id="gender" />
          </div>
        <div class="btns-group">
          <a href="#" class="btn btn-prev">Previous</a>
          <a href="#" class="btn btn-next">Next</a>
        </div>
      </div>
     
      <div class="form-step">
        <div class="input-group">
          <label for="password">Profile</label>
          <input type="file" name="password" id="password" />
        </div>
        
        <div class="btns-group">
          <a href="#" class="btn btn-prev">Previous</a>
          <input type="submit" value="Submit" class="btn" />
        </div>
      </div>
    </form>
  </body>
  <script>
    const prevBtns = document.querySelectorAll(".btn-prev");
const nextBtns = document.querySelectorAll(".btn-next");
const progress = document.getElementById("progress");
const formSteps = document.querySelectorAll(".form-step");
const progressSteps = document.querySelectorAll(".progress-step");

let formStepsNum = 0;

nextBtns.forEach((btn) => {
  btn.addEventListener("click", () => {
    formStepsNum++;
    updateFormSteps();
    updateProgressbar();
  });
});

prevBtns.forEach((btn) => {
  btn.addEventListener("click", () => {
    formStepsNum--;
    updateFormSteps();
    updateProgressbar();
  });
});

function updateFormSteps() {
  formSteps.forEach((formStep) => {
    formStep.classList.contains("form-step-active") &&
      formStep.classList.remove("form-step-active");
  });

  formSteps[formStepsNum].classList.add("form-step-active");
}

function updateProgressbar() {
  progressSteps.forEach((progressStep, idx) => {
    if (idx < formStepsNum + 1) {
      progressStep.classList.add("progress-step-active");
    } else {
      progressStep.classList.remove("progress-step-active");
    }
  });

  const progressActive = document.querySelectorAll(".progress-step-active");

  progress.style.width =
    ((progressActive.length - 1) / (progressSteps.length - 1)) * 100 + "%";
}
  </script>
</html>