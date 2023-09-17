const aletr_element = document.querySelector(".alert");
const aletr_content = aletr_element.textContent.trim();

const getAlert = () => {
  switch (aletr_content) {
    case "Name is empty":
    case "Name must be between 2 to 255 character":
    case "Email is empty":
    case "Password is empty":
    case "Password must be at least 5 characters":
    case "Password confirm is empty":
    case "Wrong password confirm":
    case "Image is empty":
    case "Image type is wrong":
    case "Image size is very small":
    case "Date is empty":
    case "Gender is empty":
    case "Old password is wrong":
    case "You have to enter old password first":
    case "You have to enter new password":
    case "Choose one of add image or get default":

        aletr_element.classList.add("alert-warning", "d-block");

        setTimeout(() => {
            aletr_element.classList.remove("alert-warning", "d-block");
            aletr_element.classList.add("d-none");
            aletr_element.textContent = "";

        }, 3000);
        break;
  
    case "Email has allready exist before":
    case "Error, can't signin":
    case "Error, can't add the admin":
    case "Error, can not update profile":
    case "Error, can't delete admin":
    case "Error, can't delete user":

        aletr_element.classList.add("alert-danger", "d-block");

        setTimeout(() => {
            aletr_element.classList.remove("alert-danger", "d-block");
            aletr_element.classList.add("d-none");
            aletr_element.textContent = "";

        }, 3000);
        break;
  
    case "Signin successfully":
    case "Admin added successfully":
    case "Profile update successfully":
    case "Admin deleted successfully":
    case "User deleted successfully":
    case "User blocked successfully":
    case "User unblocked successfully":

        aletr_element.classList.add("alert-success", "d-block");
        console.log("first")
        setTimeout(() => {
            aletr_element.classList.remove("alert-success", "d-block");
            aletr_element.classList.add("d-none");            
            aletr_element.textContent = "";

            if (aletr_content === "Admin added successfully" || aletr_content === "Admin deleted successfully") {
                window.location.href = "http://localhost/library/dashboard.php";
            } else if (aletr_content === "User blocked successfully" || aletr_content === "User unblocked successfully" || aletr_content === "User deleted successfully") {
                window.location.href = "http://localhost/library/all-users.php";
            } else {
                window.location.href = "http://localhost/library/index.php";
            }

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
