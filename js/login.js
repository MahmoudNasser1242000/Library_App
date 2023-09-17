const aletr_element = document.querySelector(".alert");
const aletr_content = aletr_element.textContent.trim();

const getAlert = () => {
  switch (aletr_content) {
    case "Email is empty":
    case "Password is empty":
    case "password must be at least 5 characters":

        aletr_element.classList.add("alert-warning", "d-block");

        setTimeout(() => {
            aletr_element.classList.remove("alert-warning", "d-block");
            aletr_element.classList.add("d-none");
            aletr_element.textContent = "";

        }, 3000);
        break;
  
    case "Email is wrong":
    case "Password is wrong":

        aletr_element.classList.add("alert-danger", "d-block");

        setTimeout(() => {
            aletr_element.classList.remove("alert-danger", "d-block");
            aletr_element.classList.add("d-none");
            aletr_element.textContent = "";

        }, 3000);
        break;
  
    case "Login in (Admin) susseccfully":
    case "Login in (User) susseccfully":

        aletr_element.classList.add("alert-success", "d-block");
        setTimeout(() => {
            aletr_element.classList.remove("alert-success", "d-block");
            aletr_element.classList.add("d-none");
            aletr_element.textContent = "";

            window.location.href = "http://localhost/library/index.php";
        }, 3000);
        break;
  
    default:
        aletr_element.classList.remove("alert-success", "alert-danger", "alert-warning", "d-block");
        aletr_element.classList.add("d-none");
        aletr_element.textContent = "";
    }
}

getAlert();

// ===================================================================
